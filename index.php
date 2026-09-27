<?php
/*
=========================================================
 PRIVATE RECRUITMENT APPLICATION PORTAL
 SINGLE FILE VERSION
 Save this file as: index.php

 REQUIREMENT:
 PHP 8.0+
 cURL enabled
 SQLite enabled
 GD enabled
 Writable current folder

 IMPORTANT:
 Replace the Razorpay credentials below.
 NEVER put the Razorpay Secret Key in JavaScript.
=========================================================
*/

session_start();

/* =========================
   1. BASIC CONFIGURATION
========================= */

const ORG_NAME = 'Uttar Pradesh airport privatelimited';
const PORTAL_TITLE = 'Private Recruitment Application Portal';
const APPLICATION_FEE = 100;

/*
 * Put your real Razorpay credentials here.
 * Keep SECRET KEY only on the PHP server.
 */
const RAZORPAY_KEY_ID = 'rzp_test_Th31FHt1DJeCEE';
const RAZORPAY_KEY_SECRET = 'oVN635xlJF4c21i8oaN9AipO';

/*
 * Replace these with your authorized organization details.
 */
const ORG_EMAIL = 'lekhpal205364@gmail.com';
const ORG_PHONE = '7819019217';
const ORG_ADDRESS = 'airport agencyAUTHORIZED REGISTERED OFFICE Ghaziabad Uttar Pradesh';

/* =========================
   2. FOLDERS / DATABASE
========================= */

$baseDir = __DIR__;
$dataDir = $baseDir . '/private_data';
$uploadDir = $dataDir . '/uploads';

if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

/* Try to protect private_data */
$htaccess = $dataDir . '/.htaccess';

if (!file_exists($htaccess)) {
    @file_put_contents(
        $htaccess,
        "Deny from all\n<IfModule mod_authz_core.c>\nRequire all denied\n</IfModule>"
    );
}

$db = new PDO('sqlite:' . $dataDir . '/applications.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("
CREATE TABLE IF NOT EXISTS applications (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    registration_no TEXT UNIQUE,
    name TEXT NOT NULL,
    father_name TEXT,
    mother_name TEXT,
    mobile TEXT NOT NULL,
    email TEXT NOT NULL,
    gender TEXT,
    dob TEXT,
    category TEXT,
    village TEXT,
    post TEXT,
    tehsil TEXT,
    district TEXT,
    state TEXT,
    pincode TEXT,
    permanent_address TEXT,
    correspondence_address TEXT,

    hs_board TEXT,
    hs_school TEXT,
    hs_year TEXT,
    hs_roll TEXT,
    hs_marksheet TEXT,
    hs_total TEXT,
    hs_obtained TEXT,
    hs_percentage TEXT,

    inter_board TEXT,
    inter_college TEXT,
    inter_year TEXT,
    inter_roll TEXT,
    inter_marksheet TEXT,
    inter_total TEXT,
    inter_obtained TEXT,
    inter_percentage TEXT,

    other_qualification TEXT,
    other_institution TEXT,
    other_year TEXT,
    other_percentage TEXT,

    photo_path TEXT,
    signature_path TEXT,

    amount INTEGER DEFAULT 100,
    payment_status TEXT DEFAULT 'pending',
    razorpay_order_id TEXT,
    razorpay_payment_id TEXT,
    razorpay_signature TEXT,

    created_at TEXT,
    paid_at TEXT
)
");

/* =========================
   3. HELPERS
========================= */

function jsonResponse($data, $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function clean($value)
{
    return trim((string)$value);
}

function postValue($name)
{
    return clean($_POST[$name] ?? '');
}

function csrfToken()
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf'];
}

function checkCsrf()
{
    $token = $_POST['csrf'] ?? '';

    if (
        empty($_SESSION['csrf']) ||
        !hash_equals($_SESSION['csrf'], $token)
    ) {
        jsonResponse([
            'success' => false,
            'message' => 'Invalid security token.'
        ], 403);
    }
}

function validEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validMobile($mobile)
{
    return preg_match('/^[6-9][0-9]{9}$/', $mobile);
}

function generateRegistrationNumber($db)
{
    do {
        $number = 'REG' .
            date('Y') .
            date('md') .
            '-' .
            strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));

        $stmt = $db->prepare(
            "SELECT id FROM applications WHERE registration_no=?"
        );

        $stmt->execute([$number]);

    } while ($stmt->fetch());

    return $number;
}

/*
 * Strict image validation:
 * JPG/JPEG/PNG only
 * 5 KB minimum
 * 1 MB maximum
 */
function saveImageUpload($file, $folder, $prefix)
{
    if (
        !isset($file) ||
        !isset($file['error']) ||
        $file['error'] !== UPLOAD_ERR_OK
    ) {
        throw new Exception('Photo/Signature upload failed.');
    }

    $size = (int)$file['size'];

    if ($size < 5 * 1024) {
        throw new Exception(
            'Photo/Signature must be at least 5 KB.'
        );
    }

    if ($size > 1024 * 1024) {
        throw new Exception(
            'Photo/Signature must not exceed 1 MB.'
        );
    }

    $tmp = $file['tmp_name'];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp);

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png'
    ];

    if (!isset($allowed[$mime])) {
        throw new Exception(
            'Only JPG/JPEG and PNG images are allowed.'
        );
    }

    /*
     * Extra image check
     */
    $imageInfo = @getimagesize($tmp);

    if ($imageInfo === false) {
        throw new Exception('Invalid image file.');
    }

    $extension = $allowed[$mime];

    $safeName =
        $prefix . '_' .
        bin2hex(random_bytes(10)) .
        '.' .
        $extension;

    $destination = $folder . '/' . $safeName;

    if (!move_uploaded_file($tmp, $destination)) {
        throw new Exception(
            'Could not save uploaded image.'
        );
    }

    return $destination;
}

/* =========================
   4. RAZORPAY API
========================= */

function razorpayRequest($method, $url, $payload = null)
{
    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERPWD =>
            RAZORPAY_KEY_ID . ':' . RAZORPAY_KEY_SECRET,
        CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
        ]
    ]);

    if ($payload !== null) {
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            json_encode($payload)
        );
    }

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);

        throw new Exception(
            'Payment server connection failed: ' . $error
        );
    }

    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $data = json_decode($response, true);

    if ($status < 200 || $status >= 300) {
        throw new Exception(
            'Razorpay API error.'
        );
    }

    return $data;
}

/* =========================
   5. API ACTIONS
========================= */

$action = $_GET['action'] ?? '';

/*
 * CREATE APPLICATION + RAZORPAY ORDER
 */
if ($action === 'create_order') {

    checkCsrf();

    try {

        $name = postValue('name');
        $father = postValue('father_name');
        $mother = postValue('mother_name');
        $mobile = postValue('mobile');
        $email = postValue('email');
        $gender = postValue('gender');
        $dob = postValue('dob');
        $category = postValue('category');

        $village = postValue('village');
        $post = postValue('post');
        $tehsil = postValue('tehsil');
        $district = postValue('district');
        $state = postValue('state');
        $pincode = postValue('pincode');

        $permanent = postValue('permanent_address');
        $correspondence = postValue('correspondence_address');

        $hsBoard = postValue('hs_board');
        $hsSchool = postValue('hs_school');
        $hsYear = postValue('hs_year');
        $hsRoll = postValue('hs_roll');
        $hsMarksheet = postValue('hs_marksheet');
        $hsTotal = postValue('hs_total');
        $hsObtained = postValue('hs_obtained');
        $hsPercentage = postValue('hs_percentage');

        $interBoard = postValue('inter_board');
        $interCollege = postValue('inter_college');
        $interYear = postValue('inter_year');
        $interRoll = postValue('inter_roll');
        $interMarksheet = postValue('inter_marksheet');
        $interTotal = postValue('inter_total');
        $interObtained = postValue('inter_obtained');
        $interPercentage = postValue('inter_percentage');

        $otherQualification = postValue('other_qualification');
        $otherInstitution = postValue('other_institution');
        $otherYear = postValue('other_year');
        $otherPercentage = postValue('other_percentage');

        if ($name === '') {
            throw new Exception('Applicant name is required.');
        }

        if (!validMobile($mobile)) {
            throw new Exception(
                'Enter a valid 10-digit mobile number.'
            );
        }

        if (!validEmail($email)) {
            throw new Exception(
                'Enter a valid email address.'
            );
        }

        if ($category === '') {
            throw new Exception(
                'Please select a vacancy/post.'
            );
        }

        /*
         * Photo and signature
         */
        $photo = saveImageUpload(
            $_FILES['photo'] ?? null,
            $uploadDir,
            'photo'
        );

        $signature = saveImageUpload(
            $_FILES['signature'] ?? null,
            $uploadDir,
            'signature'
        );

        /*
         * Insert application as PENDING.
         */
        $stmt = $db->prepare("
            INSERT INTO applications (
                name,
                father_name,
                mother_name,
                mobile,
                email,
                gender,
                dob,
                category,

                village,
                post,
                tehsil,
                district,
                state,
                pincode,
                permanent_address,
                correspondence_address,

                hs_board,
                hs_school,
                hs_year,
                hs_roll,
                hs_marksheet,
                hs_total,
                hs_obtained,
                hs_percentage,

                inter_board,
                inter_college,
                inter_year,
                inter_roll,
                inter_marksheet,
                inter_total,
                inter_obtained,
                inter_percentage,

                other_qualification,
                other_institution,
                other_year,
                other_percentage,

                photo_path,
                signature_path,

                amount,
                payment_status,
                created_at
            )
            VALUES (
                ?,?,?,?,?,?,?,?,
                ?,?,?,?,?,?,?,?,
                ?,?,?,?,?,?,?,?,
                ?,?,?,?,?,?,?,?,
                ?,?,?,?,
                ?,?,
                ?,?,?
            )
        ");

        $stmt->execute([
            $name,
            $father,
            $mother,
            $mobile,
            $email,
            $gender,
            $dob,
            $category,

            $village,
            $post,
            $tehsil,
            $district,
            $state,
            $pincode,
            $permanent,
            $correspondence,

            $hsBoard,
            $hsSchool,
            $hsYear,
            $hsRoll,
            $hsMarksheet,
            $hsTotal,
            $hsObtained,
            $hsPercentage,

            $interBoard,
            $interCollege,
            $interYear,
            $interRoll,
            $interMarksheet,
            $interTotal,
            $interObtained,
            $interPercentage,

            $otherQualification,
            $otherInstitution,
            $otherYear,
            $otherPercentage,

            $photo,
            $signature,

            APPLICATION_FEE,
            'pending',
            date('Y-m-d H:i:s')
        ]);

        $applicationId = $db->lastInsertId();

        /*
         * Razorpay amount is in paise.
         * ₹100 = 10000 paise.
         */
        $order = razorpayRequest(
            'POST',
            'https://api.razorpay.com/v1/orders',
            [
                'amount' => APPLICATION_FEE * 100,
                'currency' => 'INR',
                'receipt' => 'APP_' . $applicationId,
                'notes' => [
                    'application_id' => $applicationId
                ]
            ]
        );

        $stmt = $db->prepare("
            UPDATE applications
            SET razorpay_order_id=?
            WHERE id=?
        ");

        $stmt->execute([
            $order['id'],
            $applicationId
        ]);

        $_SESSION['application_id'] = $applicationId;

        jsonResponse([
            'success' => true,
            'application_id' => $applicationId,
            'order_id' => $order['id'],
            'key_id' => RAZORPAY_KEY_ID,
            'amount' => APPLICATION_FEE * 100,
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile
        ]);

    } catch (Throwable $e) {

        jsonResponse([
            'success' => false,
            'message' => $e->getMessage()
        ], 400);
    }
}

/*
 * VERIFY PAYMENT
 */
if ($action === 'verify_payment') {

    checkCsrf();

    try {

        $applicationId = (int)($_POST['application_id'] ?? 0);

        $paymentId = clean(
            $_POST['razorpay_payment_id'] ?? ''
        );

        $orderId = clean(
            $_POST['razorpay_order_id'] ?? ''
        );

        $signature = clean(
            $_POST['razorpay_signature'] ?? ''
        );

        if (!$applicationId ||
            !$paymentId ||
            !$orderId ||
            !$signature
        ) {
            throw new Exception(
                'Incomplete payment information.'
            );
        }

        $stmt = $db->prepare("
            SELECT *
            FROM applications
            WHERE id=?
            LIMIT 1
        ");

        $stmt->execute([$applicationId]);

        $application = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$application) {
            throw new Exception(
                'Application not found.'
            );
        }

        if (
            $application['razorpay_order_id']
            !== $orderId
        ) {
            throw new Exception(
                'Order ID mismatch.'
            );
        }

        /*
         * Razorpay signature verification
         */
        $expectedSignature = hash_hmac(
            'sha256',
            $orderId . '|' . $paymentId,
            RAZORPAY_KEY_SECRET
        );

        if (!hash_equals(
            $expectedSignature,
            $signature
        )) {
            throw new Exception(
                'Payment signature verification failed.'
            );
        }

        /*
         * Also fetch payment from Razorpay.
         * This prevents relying only on browser data.
         */
        $payment = razorpayRequest(
            'GET',
            'https://api.razorpay.com/v1/payments/' .
            rawurlencode($paymentId)
        );

        if (
            !isset($payment['status']) ||
            $payment['status'] !== 'captured'
        ) {
            throw new Exception(
                'Payment has not been captured.'
            );
        }

        if (
            (int)$payment['amount']
            !== APPLICATION_FEE * 100
        ) {
            throw new Exception(
                'Payment amount mismatch.'
            );
        }

        $registration = generateRegistrationNumber($db);

        $stmt = $db->prepare("
            UPDATE applications
            SET
                registration_no=?,
                payment_status='paid',
                razorpay_payment_id=?,
                razorpay_signature=?,
                paid_at=?
            WHERE id=?
        ");

        $stmt->execute([
            $registration,
            $paymentId,
            $signature,
            date('Y-m-d H:i:s'),
            $applicationId
        ]);

        $_SESSION['paid_application'] = $applicationId;

        jsonResponse([
            'success' => true,
            'registration_no' => $registration,
            'message' =>
                'Payment verified successfully.'
        ]);

    } catch (Throwable $e) {

        jsonResponse([
            'success' => false,
            'message' => $e->getMessage()
        ], 400);
    }
}

/*
 * GET APPLICATION DETAILS AFTER PAYMENT
 */
if ($action === 'get_application') {

    checkCsrf();

    $applicationId =
        (int)($_SESSION['paid_application'] ?? 0);

    if (!$applicationId) {
        jsonResponse([
            'success' => false,
            'message' => 'Application not found.'
        ], 404);
    }

    $stmt = $db->prepare("
        SELECT *
        FROM applications
        WHERE id=?
        AND payment_status='paid'
        LIMIT 1
    ");

    $stmt->execute([$applicationId]);

    $app = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$app) {
        jsonResponse([
            'success' => false,
            'message' => 'Paid application not found.'
        ], 404);
    }

    /*
     * Convert images to data URLs for client-side PDF.
     */
    function imageData($path)
    {
        if (!$path || !file_exists($path)) {
            return '';
        }

        $mime = mime_content_type($path);

        $data = base64_encode(
            file_get_contents($path)
        );

        return 'data:' . $mime . ';base64,' . $data;
    }

    $app['photo_data'] =
        imageData($app['photo_path']);

    $app['signature_data'] =
        imageData($app['signature_path']);

    jsonResponse([
        'success' => true,
        'application' => $app,
        'organization' => ORG_NAME,
        'address' => ORG_ADDRESS,
        'email' => ORG_EMAIL,
        'phone' => ORG_PHONE
    ]);
}

/* =========================
   6. LOGOUT / RESET
========================= */

if ($action === 'reset') {

    $_SESSION = [];

    session_destroy();

    header(
        'Location: ' .
        strtok($_SERVER['REQUEST_URI'], '?')
    );

    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
<?= htmlspecialchars(PORTAL_TITLE) ?>
</title>

<style>

*{
    box-sizing:border-box;
}

body{
    margin:0;
    font-family:Arial,Helvetica,sans-serif;
    background:#f2f5f8;
    color:#202124;
}

.container{
    max-width:1050px;
    margin:20px auto;
    padding:10px;
}

.header{
    background:#ffffff;
    border-radius:15px;
    padding:20px;
    text-align:center;
    box-shadow:0 4px 18px rgba(0,0,0,.08);
    margin-bottom:15px;
}

.header h1{
    margin:5px 0;
    font-size:28px;
}

.header p{
    margin:7px 0;
    color:#555;
}

.notice{
    background:#fff4d6;
    border:1px solid #e2bd5c;
    padding:12px;
    border-radius:10px;
    margin-top:12px;
    font-size:14px;
}

.steps{
    display:flex;
    gap:7px;
    margin:15px 0;
}

.stepIndicator{
    flex:1;
    padding:11px 5px;
    text-align:center;
    background:#dfe4e8;
    border-radius:8px;
    font-size:13px;
    font-weight:bold;
}

.stepIndicator.active{
    background:#1976d2;
    color:#fff;
}

.stepIndicator.done{
    background:#2e7d32;
    color:#fff;
}

.card{
    background:#fff;
    border-radius:15px;
    padding:22px;
    box-shadow:0 4px 18px rgba(0,0,0,.07);
}

.step{
    display:none;
}

.step.active{
    display:block;
}

h2{
    margin-top:0;
}

h3{
    border-bottom:1px solid #ddd;
    padding-bottom:8px;
}

.grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:15px;
}

.field{
    display:flex;
    flex-direction:column;
}

.field.full{
    grid-column:1/-1;
}

label{
    font-weight:bold;
    margin-bottom:6px;
    font-size:14px;
}

input,
select,
textarea{
    width:100%;
    padding:12px;
    border:1px solid #bbb;
    border-radius:8px;
    font-size:15px;
}

textarea{
    min-height:90px;
    resize:vertical;
}

input:focus,
select:focus,
textarea:focus{
    outline:none;
    border-color:#1976d2;
}

small{
    color:#666;
    margin-top:4px;
}

.buttons{
    display:flex;
    justify-content:space-between;
    gap:10px;
    margin-top:25px;
}

button{
    border:0;
    border-radius:8px;
    padding:13px 22px;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
}

.btnNext{
    background:#1976d2;
    color:white;
}

.btnBack{
    background:#777;
    color:white;
}

.btnPay{
    background:#138a3d;
    color:#fff;
}

button:disabled{
    opacity:.5;
    cursor:not-allowed;
}

.previewBox{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
    margin-top:10px;
}

.previewBox img{
    width:120px;
    height:150px;
    object-fit:cover;
    border:1px solid #aaa;
    border-radius:8px;
}

.signaturePreview{
    height:80px !important;
    object-fit:contain !important;
}

.review{
    background:#f7f9fb;
    padding:15px;
    border-radius:10px;
    line-height:1.8;
}

.paymentBox{
    text-align:center;
    padding:25px;
    border:2px dashed #bbb;
    border-radius:12px;
}

.price{
    font-size:32px;
    font-weight:bold;
    margin:15px;
}

.success{
    text-align:center;
    padding:30px;
}

.registration{
    font-size:30px;
    font-weight:bold;
    background:#e7f6eb;
    padding:15px;
    border-radius:10px;
    display:inline-block;
    margin:15px;
}

.footer{
    text-align:center;
    color:#666;
    padding:20px;
    font-size:13px;
}

@media(max-width:700px){

    .grid{
        grid-template-columns:1fr;
    }

    .steps{
        overflow-x:auto;
    }

    .stepIndicator{
        min-width:110px;
    }

    .header h1{
        font-size:22px;
    }

    .card{
        padding:15px;
    }

    .buttons{
        flex-direction:column;
    }

    button{
        width:100%;
    }
}

</style>

</head>

<body>

<div class="container">

<div class="header">

<h1>
<?= htmlspecialchars(ORG_NAME) ?>
</h1>

<p>
<?= htmlspecialchars(PORTAL_TITLE) ?>
</p>

<p>
Recruitment Application 2026
</p>

<div class="notice">
<strong>Important:</strong>
This is a private recruitment/application portal.
It is not a Government of India or State Government website
unless expressly authorized.
</div>

</div>


<div class="steps">

<div class="stepIndicator active" id="indicator1">
1. Personal
</div>

<div class="stepIndicator" id="indicator2">
2. Personal Info
</div>

<div class="stepIndicator" id="indicator3">
3. Address
</div>

<div class="stepIndicator" id="indicator4">
4. Qualification
</div>

<div class="stepIndicator" id="indicator5">
Payment
</div>

</div>


<div class="card">

<form
    id="applicationForm"
    enctype="multipart/form-data"
>

<input
    type="hidden"
    name="csrf"
    value="<?= htmlspecialchars(csrfToken()) ?>"
>


<!-- STEP 1 -->

<div class="step active" id="step1">

<h2>Step 1 — Personal Details</h2>

<div class="grid">

<div class="field">

<label>Applicant Full Name *</label>

<input
    type="text"
    name="name"
    required
    maxlength="100"
>

</div>


<div class="field">

<label>Father's Name *</label>

<input
    type="text"
    name="father_name"
    required
    maxlength="100"
>

</div>


<div class="field">

<label>Mother's Name *</label>

<input
    type="text"
    name="mother_name"
    required
    maxlength="100"
>

</div>


<div class="field">

<label>Mobile Number *</label>

<input
    type="tel"
    name="mobile"
    required
    maxlength="10"
    pattern="[6-9][0-9]{9}"
>

</div>


<div class="field">

<label>Email Address *</label>

<input
    type="email"
    name="email"
    required
>

</div>


<div class="field">

<label>Gender *</label>

<select name="gender" required>

<option value="">
Select
</option>

<option>Male</option>

<option>Female</option>

<option>Other</option>

</select>

</div>


<div class="field full">

<label>Select Post *</label>

<select name="category" required>

<option value="">
Select Vacancy
</option>

<option value="Customer Service Associate (CSA)">
Customer Service Associate (CSA) — 10+2 Pass
</option>

<option value="Housekeeping / Loader">
Housekeeping / Loader — 10th Pass
</option>

</select>

</div>


<div class="field">

<label>Passport Size Photo *</label>

<input
    type="file"
    name="photo"
    id="photo"
    accept=".jpg,.jpeg,.png,image/jpeg,image/png"
    required
>

<small>
JPG/JPEG/PNG only — 5 KB to 1 MB
</small>

</div>


<div class="field">

<label>Signature *</label>

<input
    type="file"
    name="signature"
    id="signature"
    accept=".jpg,.jpeg,.png,image/jpeg,image/png"
    required
>

<small>
JPG/JPEG/PNG only — 5 KB to 1 MB
</small>

</div>

</div>


<div class="previewBox">

<div>

<strong>Photo Preview</strong>

<br>

<img
    id="photoPreview"
    style="display:none"
>

</div>

<div>

<strong>Signature Preview</strong>

<br>

<img
    id="signaturePreview"
    class="signaturePreview"
    style="display:none"
>

</div>

</div>


<div class="buttons">

<div></div>

<button
    type="button"
    class="btnNext"
    onclick="nextStep()"
>
Next →
</button>

</div>

</div>


<!-- STEP 2 -->

<div class="step" id="step2">

<h2>Step 2 — Date of Birth / Personal Information</h2>

<div class="grid">

<div class="field">

<label>Date of Birth *</label>

<input
    type="date"
    name="dob"
    required
>

</div>


<div class="field">

<label>Category</label>

<select name="category_dummy">

<option value="">
Select Category
</option>

<option>General</option>

<option>OBC</option>

<option>SC</option>

<option>ST</option>

</select>

</div>

</div>


<div class="buttons">

<button
    type="button"
    class="btnBack"
    onclick="previousStep()"
>
← Back
</button>

<button
    type="button"
    class="btnNext"
    onclick="nextStep()"
>
Next →
</button>

</div>

</div>


<!-- STEP 3 -->

<div class="step" id="step3">

<h2>Step 3 — Address Details</h2>

<div class="grid">

<div class="field">

<label>Village / Town *</label>

<input
    type="text"
    name="village"
    required
>

</div>


<div class="field">

<label>Post Office *</label>

<input
    type="text"
    name="post"
    required
>

</div>


<div class="field">

<label>Tehsil *</label>

<input
    type="text"
    name="tehsil"
    required
>

</div>


<div class="field">

<label>District *</label>

<input
    type="text"
    name="district"
    required
>

</div>


<div class="field">

<label>State *</label>

<input
    type="text"
    name="state"
    value="Uttar Pradesh"
    required
>

</div>


<div class="field">

<label>PIN Code *</label>

<input
    type="text"
    name="pincode"
    maxlength="6"
    pattern="[0-9]{6}"
    required
>

</div>


<div class="field full">

<label>Permanent Address *</label>

<textarea
    name="permanent_address"
    required
></textarea>

</div>


<div class="field full">

<label>Correspondence Address</label>

<textarea
    name="correspondence_address"
></textarea>

</div>

</div>


<div class="buttons">

<button
    type="button"
    class="btnBack"
    onclick="previousStep()"
>
← Back
</button>

<button
    type="button"
    class="btnNext"
    onclick="nextStep()"
>
Next →
</button>

</div>

</div>


<!-- STEP 4 -->

<div class="step" id="step4">

<h2>Step 4 — Educational Qualification</h2>

<h3>High School / 10th</h3>

<div class="grid">

<div class="field">

<label>Board *</label>

<input
    type="text"
    name="hs_board"
    required
>

</div>


<div class="field">

<label>School Name *</label>

<input
    type="text"
    name="hs_school"
    required
>

</div>


<div class="field">

<label>Passing Year *</label>

<input
    type="text"
    name="hs_year"
    maxlength="4"
    required
>

</div>


<div class="field">

<label>Roll Number</label>

<input
    type="text"
    name="hs_roll"
>

</div>


<div class="field">

<label>Marksheet Number</label>

<input
    type="text"
    name="hs_marksheet"
>

</div>


<div class="field">

<label>Total Marks</label>

<input
    type="number"
    name="hs_total"
>

</div>


<div class="field">

<label>Obtained Marks</label>

<input
    type="number"
    name="hs_obtained"
>

</div>


<div class="field">

<label>Percentage</label>

<input
    type="number"
    step="0.01"
    name="hs_percentage"
>

</div>

</div>


<h3>Intermediate / 12th</h3>

<div class="grid">

<div class="field">

<label>Board</label>

<input
    type="text"
    name="inter_board"
>

</div>


<div class="field">

<label>School / College Name</label>

<input
    type="text"
    name="inter_college"
>

</div>


<div class="field">

<label>Passing Year</label>

<input
    type="text"
    name="inter_year"
    maxlength="4"
>

</div>


<div class="field">

<label>Roll Number</label>

<input
    type="text"
    name="inter_roll"
>

</div>


<div class="field">

<label>Marksheet Number</label>

<input
    type="text"
    name="inter_marksheet"
>

</div>


<div class="field">

<label>Total Marks</label>

<input
    type="number"
    name="inter_total"
>

</div>


<div class="field">

<label>Obtained Marks</label>

<input
    type="number"
    name="inter_obtained"
>

</div>


<div class="field">

<label>Percentage</label>

<input
    type="number"
    step="0.01"
    name="inter_percentage"
>

</div>

</div>


<h3>Other Qualification</h3>

<div class="grid">

<div class="field">

<label>Qualification</label>

<input
    type="text"
    name="other_qualification"
>

</div>


<div class="field">

<label>Institution</label>

<input
    type="text"
    name="other_institution"
>

</div>


<div class="field">

<label>Passing Year</label>

<input
    type="text"
    name="other_year"
>

</div>


<div class="field">

<label>Percentage</label>

<input
    type="text"
    name="other_percentage"
>

</div>

</div>


<div class="review">

<strong>Declaration</strong>

<br><br>

I confirm that the information provided by me is
true and correct to the best of my knowledge.
I understand that the application will be processed
only after successful payment verification.

<br><br>

<label>

<input
    type="checkbox"
    id="declaration"
    required
>

I agree to the declaration.

</label>

</div>


<div class="buttons">

<button
    type="button"
    class="btnBack"
    onclick="previousStep()"
>
← Back
</button>

<button
    type="button"
    class="btnNext"
    onclick="reviewApplication()"
>
Review & Payment →
</button>

</div>

</div>


<!-- PAYMENT -->

<div class="step" id="step5">

<h2>Application Fee & Payment</h2>

<div class="paymentBox">

<p>
Application Fee
</p>

<div class="price">
₹100
</div>

<p>
Your application will not be finally submitted
until the payment is successfully verified.
</p>

<button
    type="button"
    class="btnPay"
    id="payButton"
    onclick="startPayment()"
>
Pay ₹100 & Continue
</button>

<div
    id="paymentMessage"
    style="margin-top:15px"
></div>

</div>


<div
    id="reviewArea"
    class="review"
    style="margin-top:20px"
></div>

</div>


</form>


<!-- SUCCESS -->

<div
    id="successBox"
    style="display:none"
    class="success"
>

<h2>
Application Submitted Successfully
</h2>

<p>
Your payment has been verified and your application
has been registered.
</p>

<div
    class="registration"
    id="registrationNumber"
>
</div>

<p>
<strong>Payment Status:</strong>
PAID ✓
</p>

<button
    type="button"
    class="btnPay"
    onclick="downloadPDF()"
>
Download Application PDF
</button>

<br><br>

<button
    type="button"
    class="btnBack"
    onclick="window.location.href='?action=reset'"
>
Start New Application
</button>

</div>

</div>


<div class="footer">

<?= htmlspecialchars(ORG_NAME) ?><br>

<?= htmlspecialchars(ORG_ADDRESS) ?><br>

<?= htmlspecialchars(ORG_EMAIL) ?> |
<?= htmlspecialchars(ORG_PHONE) ?>

<br><br>

Private recruitment portal. Use only authorized
recruitment information.

</div>

</div>


<!-- Razorpay Checkout -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<!-- jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>

/* ============================================
   JAVASCRIPT
============================================ */

let currentStep = 1;

let applicationId = null;

let registrationNumber = null;

let finalApplication = null;

const form =
    document.getElementById('applicationForm');


function showStep(number)
{
    document
        .querySelectorAll('.step')
        .forEach(function(el){

            el.classList.remove('active');

        });

    const selected =
        document.getElementById(
            'step' + number
        );

    if(selected){
        selected.classList.add('active');
    }

    document
        .querySelectorAll('.stepIndicator')
        .forEach(function(el){

            el.classList.remove('active');

        });

    for(
        let i=1;
        i<=5;
        i++
    ){

        const indicator =
            document.getElementById(
                'indicator' + i
            );

        if(!indicator) continue;

        indicator.classList.remove('done');

        if(i < number){
            indicator.classList.add('done');
        }

        if(i === number){
            indicator.classList.add('active');
        }

    }

    window.scrollTo({
        top:0,
        behavior:'smooth'
    });
}


function validateCurrentStep()
{
    const activeStep =
        document.querySelector(
            '.step.active'
        );

    if(!activeStep){
        return false;
    }

    const fields =
        activeStep.querySelectorAll(
            'input,select,textarea'
        );

    for(
        const field of fields
    ){

        if(
            field.type === 'checkbox'
            ||
            field.type === 'file'
        ){

            if(
                field.required &&
                !field.files?.length &&
                field.type === 'file'
            ){

                alert(
                    'Please select ' +
                    field.previousElementSibling.innerText
                );

                return false;
            }

            if(
                field.required &&
                field.type === 'checkbox' &&
                !field.checked
            ){

                alert(
                    'Please accept the declaration.'
                );

                return false;
            }

            continue;
        }

        if(
            field.required &&
            !field.checkValidity()
        ){

            field.reportValidity();

            return false;
        }
    }

    return true;
}


function nextStep()
{
    if(!validateCurrentStep()){
        return;
    }

    if(currentStep < 4){
        currentStep++;
        showStep(currentStep);
    }
}


function previousStep()
{
    if(currentStep > 1){
        currentStep--;
        showStep(currentStep);
    }
}


/* ============================================
   IMAGE VALIDATION
============================================ */

function validateImage(
    input,
    preview
){

    const file =
        input.files[0];

    if(!file){
        return false;
    }

    const allowed = [
        'image/jpeg',
        'image/png'
    ];

    if(
        !allowed.includes(file.type)
    ){

        alert(
            'Only JPG/JPEG and PNG images are allowed.'
        );

        input.value='';

        return false;
    }

    if(
        file.size < 5 * 1024 ||
        file.size > 1024 * 1024
    ){

        alert(
            'Image size must be between 5 KB and 1 MB.'
        );

        input.value='';

        return false;
    }

    const reader =
        new FileReader();

    reader.onload =
        function(e){

            preview.src =
                e.target.result;

            preview.style.display =
                'block';

        };

    reader.readAsDataURL(file);

    return true;
}


document
    .getElementById('photo')
    .addEventListener(
        'change',
        function(){

            validateImage(
                this,
                document.getElementById(
                    'photoPreview'
                )
            );

        }
    );


document
    .getElementById('signature')
    .addEventListener(
        'change',
        function(){

            validateImage(
                this,
                document.getElementById(
                    'signaturePreview'
                )
            );

        }
    );


/* ============================================
   REVIEW
============================================ */

function reviewApplication()
{
    if(!validateCurrentStep()){
        return;
    }

    const data =
        new FormData(form);

    let html = '';

    html += '<h3>Application Review</h3>';

    html +=
        '<strong>Name:</strong> ' +
        escapeHtml(data.get('name')) +
        '<br>';

    html +=
        '<strong>Father:</strong> ' +
        escapeHtml(data.get('father_name')) +
        '<br>';

    html +=
        '<strong>Mother:</strong> ' +
        escapeHtml(data.get('mother_name')) +
        '<br>';

    html +=
        '<strong>Mobile:</strong> ' +
        escapeHtml(data.get('mobile')) +
        '<br>';

    html +=
        '<strong>Email:</strong> ' +
        escapeHtml(data.get('email')) +
        '<br>';

    html +=
        '<strong>DOB:</strong> ' +
        escapeHtml(data.get('dob')) +
        '<br>';

    html +=
        '<strong>Post:</strong> ' +
        escapeHtml(data.get('category')) +
        '<br>';

    html +=
        '<strong>Address:</strong> ' +
        escapeHtml(data.get('village')) +
        ', ' +
        escapeHtml(data.get('post')) +
        ', ' +
        escapeHtml(data.get('district')) +
        ', ' +
        escapeHtml(data.get('state')) +
        ' - ' +
        escapeHtml(data.get('pincode')) +
        '<br><br>';

    html +=
        '<strong>Application Fee:</strong> ₹100';

    document.getElementById(
        'reviewArea'
    ).innerHTML = html;

    currentStep = 5;

    showStep(5);
}


/* ============================================
   PAYMENT
============================================ */

async function startPayment()
{
    const button =
        document.getElementById(
            'payButton'
        );

    button.disabled = true;

    document.getElementById(
        'paymentMessage'
    ).innerText =
        'Creating secure payment order...';

    try{

        const formData =
            new FormData(form);

        const response =
            await fetch(
                '?action=create_order',
                {
                    method:'POST',
                    body:formData
                }
            );

        const result =
            await response.json();

        if(!result.success){

            throw new Error(
                result.message ||
                'Could not create payment order.'
            );

        }

        applicationId =
            result.application_id;

        const options = {

            key:
                result.key_id,

            amount:
                result.amount,

            currency:'INR',

            name:
                '<?= addslashes(ORG_NAME) ?>',

            description:
                'Recruitment Application Fee',

            order_id:
                result.order_id,

            prefill:{

                name:
                    result.name,

                email:
                    result.email,

                contact:
                    result.mobile

            },

            theme:{
                color:'#1976d2'
            },

            handler:
                async function(payment){

                    await verifyPayment(
                        payment
                    );

                },

            modal:{

                ondismiss:
                    function(){

                        button.disabled =
                            false;

                        document.getElementById(
                            'paymentMessage'
                        ).innerText =
                            'Payment window closed.';

                    }

            }

        };

        const rzp =
            new Razorpay(options);

        rzp.open();

    }catch(error){

        alert(
            error.message
        );

        button.disabled =
            false;

        document.getElementById(
            'paymentMessage'
        ).innerText =
            '';

    }
}


/* ============================================
   VERIFY PAYMENT
============================================ */

async function verifyPayment(
    payment
)
{
    document.getElementById(
        'paymentMessage'
    ).innerText =
        'Verifying payment...';

    const body =
        new URLSearchParams();

    body.append(
        'csrf',
        '<?= htmlspecialchars(csrfToken()) ?>'
    );

    body.append(
        'application_id',
        applicationId
    );

    body.append(
        'razorpay_payment_id',
        payment.razorpay_payment_id
    );

    body.append(
        'razorpay_order_id',
        payment.razorpay_order_id
    );

    body.append(
        'razorpay_signature',
        payment.razorpay_signature
    );

    try{

        const response =
            await fetch(
                '?action=verify_payment',
                {
                    method:'POST',
                    headers:{
                        'Content-Type':
                            'application/x-www-form-urlencoded'
                    },
                    body:body.toString()
                }
            );

        const result =
            await response.json();

        if(!result.success){

            throw new Error(
                result.message ||
                'Payment verification failed.'
            );

        }

        registrationNumber =
            result.registration_no;

        await loadPaidApplication();

    }catch(error){

        alert(
            'Payment was received but could not be verified. Please contact the portal support team with your Razorpay payment ID.\n\n' +
            error.message
        );

        document.getElementById(
            'payButton'
        ).disabled = false;

    }
}


/* ============================================
   LOAD PAID APPLICATION
============================================ */

async function loadPaidApplication()
{
    const response =
        await fetch(
            '?action=get_application',
            {
                method:'POST',
                headers:{
                    'Content-Type':
                        'application/x-www-form-urlencoded'
                },
                body:
                    'csrf=' +
                    encodeURIComponent(
                        '<?= htmlspecialchars(csrfToken()) ?>'
                    )
            }
        );

    const result =
        await response.json();

    if(!result.success){

        alert(
            result.message
        );

        return;
    }

    finalApplication =
        result.application;

    registrationNumber =
        result.application.registration_no;

    document.getElementById(
        'applicationForm'
    ).style.display =
        'none';

    document.querySelector(
        '.steps'
    ).style.display =
        'none';

    document.getElementById(
        'successBox'
    ).style.display =
        'block';

    document.getElementById(
        'registrationNumber'
    ).innerText =
        registrationNumber;

    window.scrollTo({
        top:0,
        behavior:'smooth'
    });
}


/* ============================================
   PDF DOWNLOAD
============================================ */

async function downloadPDF()
{
    if(!finalApplication){

        alert(
            'Application data not loaded.'
        );

        return;
    }

    const {
        jsPDF
    } = window.jspdf;

    const doc =
        new jsPDF({
            orientation:'portrait',
            unit:'mm',
            format:'a4'
        });

    const a =
        finalApplication;

    let y = 15;

    doc.setFontSize(16);

    doc.text(
        'RECRUITMENT APPLICATION',
        105,
        y,
        {align:'center'}
    );

    y += 8;

    doc.setFontSize(10);

    doc.text(
        'Private Recruitment Application Portal',
        105,
        y,
        {align:'center'}
    );

    y += 10;

    /*
     * Photo
     */
    if(a.photo_data){

        doc.addImage(
            a.photo_data,
            a.photo_data.includes('png')
                ? 'PNG'
                : 'JPEG',
            165,
            20,
            30,
            38
        );

    }

    doc.setFontSize(11);

    doc.text(
        'Registration No: ' +
        (a.registration_no || ''),
        15,
        y
    );

    y += 7;

    doc.text(
        'Application Date: ' +
        (a.created_at || ''),
        15,
        y
    );

    y += 10;

    doc.setFontSize(13);

    doc.text(
        'Personal Details',
        15,
        y
    );

    y += 7;

    doc.setFontSize(10);

    y = pdfLine(
        doc,
        'Name',
        a.name,
        y
    );

    y = pdfLine(
        doc,
        "Father's Name",
        a.father_name,
        y
    );

    y = pdfLine(
        doc,
        "Mother's Name",
        a.mother_name,
        y
    );

    y = pdfLine(
        doc,
        "Mobile",
        a.mobile,
        y
    );

    y = pdfLine(
        doc,
        "Email",
        a.email,
        y
    );

    y = pdfLine(
        doc,
        "Gender",
        a.gender,
        y
    );

    y = pdfLine(
        doc,
        "Date of Birth",
        a.dob,
        y
    );

    y = pdfLine(
        doc,
        "Post",
        a.category,
        y
    );

    y += 4;

    doc.setFontSize(13);

    doc.text(
        'Address',
        15,
        y
    );

    y += 7;

    doc.setFontSize(10);

    y = pdfLine(
        doc,
        'Village/Town',
        a.village,
        y
    );

    y = pdfLine(
        doc,
        'Post Office',
        a.post,
        y
    );

    y = pdfLine(
        doc,
        'Tehsil',
        a.tehsil,
        y
    );

    y = pdfLine(
        doc,
        'District',
        a.district,
        y
    );

    y = pdfLine(
        doc,
        'State',
        a.state,
        y
    );

    y = pdfLine(
        doc,
        'PIN',
        a.pincode,
        y
    );

    y += 4;

    doc.setFontSize(13);

    doc.text(
        'Educational Qualification',
        15,
        y
    );

    y += 7;

    doc.setFontSize(10);

    y = pdfLine(
        doc,
        '10th Board',
        a.hs_board,
        y
    );

    y = pdfLine(
        doc,
        '10th School',
        a.hs_school,
        y
    );

    y = pdfLine(
        doc,
        '10th Year',
        a.hs_year,
        y
    );

    y = pdfLine(
        doc,
        '10th Marks',
        a.hs_obtained +
        ' / ' +
        a.hs_total,
        y
    );

    y = pdfLine(
        doc,
        '10th Percentage',
        a.hs_percentage,
        y
    );

    y = pdfLine(
        doc,
        '12th Board',
        a.inter_board,
        y
    );

    y = pdfLine(
        doc,
        '12th College',
        a.inter_college,
        y
    );

    y = pdfLine(
        doc,
        '12th Year',
        a.inter_year,
        y
    );

    y = pdfLine(
        doc,
        '12th Marks',
        a.inter_obtained +
        ' / ' +
        a.inter_total,
        y
    );

    y = pdfLine(
        doc,
        '12th Percentage',
        a.inter_percentage,
        y
    );

    y += 5;

    doc.setFontSize(13);

    doc.text(
        'Payment Details',
        15,
        y
    );

    y += 7;

    doc.setFontSize(10);

    y = pdfLine(
        doc,
        'Application Fee',
        'Rs. 100',
        y
    );

    y = pdfLine(
        doc,
        'Payment Status',
        'PAID',
        y
    );

    y = pdfLine(
        doc,
        'Payment ID',
        a.razorpay_payment_id,
        y
    );

    /*
     * Signature
     */
    if(
        y > 250
    ){

        doc.addPage();

        y = 20;

    }

    doc.text(
        'Applicant Signature',
        150,
        y + 10
    );

    if(a.signature_data){

        doc.addImage(
            a.signature_data,
            a.signature_data.includes('png')
                ? 'PNG'
                : 'JPEG',
            150,
            y + 13,
            40,
            20
        );

    }

    y += 45;

    doc.setFontSize(8);

    doc.text(
        'This application was submitted through the private recruitment portal.',
        105,
        y,
        {align:'center'}
    );

    doc.save(
        (a.registration_no || 'application') +
        '.pdf'
    );
}


function pdfLine(
    doc,
    label,
    value,
    y
){

    value =
        value === null ||
        value === undefined ||
        value === ''
            ? '-'
            : String(value);

    let text =
        label + ': ' + value;

    /*
     * Basic wrapping
     */
    const lines =
        doc.splitTextToSize(
            text,
            170
        );

    doc.text(
        lines,
        15,
        y
    );

    return y +
        (lines.length * 5) +
        1;
}


function escapeHtml(value)
{
    return String(
        value ?? ''
    )
    .replaceAll('&','&amp;')
    .replaceAll('<','&lt;')
    .replaceAll('>','&gt;')
    .replaceAll('"','&quot;')
    .replaceAll("'","&#039;");
}

</script>

</body>
</html>
