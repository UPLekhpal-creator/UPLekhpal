<!DOCTYPE html>
<html b:version='2' class='v2' expr:dir='data:blog.languageDirection' xmlns='http://www.w3.org/1999/xhtml' xmlns:b='http://www.google.com/2005/gml/b' xmlns:data='http://www.google.com/2005/gml/data' xmlns:expr='http://www.google.com/2005/gml/expr'>
<head>
<meta charset='UTF-8'/>
<meta content='width=device-width, initial-scale=1' name='viewport'/>
<title><data:blog.pageTitle/></title>
<meta content='LEKHPAL DIGITAL SERVICES - Online Digital &amp; Documentation Assistance' name='description'/>
<b:skin><![CDATA[

:root{
 --navy:#071d3d;--blue:#1456a0;--cyan:#12a6c9;--orange:#ff6a2a;
 --green:#13a878;--gold:#f5b942;--bg:#f4f8fc;--text:#17263b;--muted:#66768b;
 --white:#fff;--line:#dce6f0;--shadow:0 18px 55px rgba(8,35,70,.12);
}
*{box-sizing:border-box;scroll-behavior:smooth}
body{margin:0;font-family:Arial,"Noto Sans Devanagari",sans-serif;background:var(--bg);color:var(--text)}
a{text-decoration:none;color:inherit}
.topbar{background:linear-gradient(90deg,var(--navy),#0b3971);color:#fff;text-align:center;padding:8px 15px;font-size:13px}
header{position:sticky;top:0;z-index:50;background:rgba(255,255,255,.96);backdrop-filter:blur(10px);border-bottom:1px solid var(--line)}
.nav{max-width:1200px;margin:auto;padding:13px 20px;display:flex;align-items:center;justify-content:space-between;gap:20px}
.brand{display:flex;align-items:center;gap:12px}
.logo{width:54px;height:54px;border-radius:17px;background:linear-gradient(145deg,#0b3971,#12a6c9 65%,#13a878);display:grid;place-items:center;color:white;font-weight:900;font-size:25px;box-shadow:0 9px 25px rgba(20,86,160,.28);position:relative}
.logo:after{content:"✓";position:absolute;right:-5px;bottom:-5px;width:20px;height:20px;border-radius:50%;background:var(--orange);display:grid;place-items:center;font-size:12px;border:2px solid white}
.brand h1{margin:0;font-size:19px;color:var(--navy)}
.brand small{color:var(--muted);font-size:11px}
nav{display:flex;gap:17px;align-items:center;font-weight:700;font-size:13px;color:#25466f}
nav a:hover{color:var(--orange)}
.menu{display:none;background:var(--navy);color:white;border:0;border-radius:8px;padding:9px}
.hero{background:
 radial-gradient(circle at 80% 20%,rgba(18,166,201,.16),transparent 28%),
 radial-gradient(circle at 15% 80%,rgba(19,168,120,.12),transparent 30%),
 linear-gradient(135deg,#eaf5ff,#fff 55%,#eafcf7);
padding:72px 20px}
.hero-inner{max-width:1200px;margin:auto;display:grid;grid-template-columns:1.2fr .8fr;gap:38px;align-items:center}
.badge{display:inline-block;padding:8px 13px;border-radius:999px;background:#fff;border:1px solid #dbe8f4;color:#0b5a79;font-weight:800;font-size:12px;box-shadow:0 8px 25px #123f7a12}
.hero h2{font-size:48px;line-height:1.05;margin:18px 0 15px;color:var(--navy)}
.hero h2 span{color:var(--orange)}
.hero p{font-size:17px;line-height:1.75;color:#526276}
.cta{display:flex;gap:12px;flex-wrap:wrap;margin-top:25px}
.btn{display:inline-block;padding:13px 19px;border-radius:11px;font-weight:800}
.btn-primary{background:linear-gradient(135deg,var(--orange),#e74d19);color:#fff}
.btn-secondary{background:var(--navy);color:#fff}
.btn-green{background:var(--green);color:#fff}
.hero-card{background:#fff;border:1px solid #dce7f2;border-radius:25px;padding:28px;box-shadow:var(--shadow)}
.hero-card h3{margin-top:0;color:var(--navy)}
.hero-card li{margin:13px 0;color:#526276}
section{padding:65px 20px}
.wrap{max-width:1200px;margin:auto}
.section-head{text-align:center;margin-bottom:36px}
.section-head h2{font-size:32px;color:var(--navy);margin:0 0 9px}
.section-head p{color:var(--muted);margin:0}
.services{display:grid;grid-template-columns:repeat(4,1fr);gap:17px}
.service{background:#fff;border:1px solid var(--line);border-radius:18px;padding:21px;box-shadow:0 9px 30px rgba(10,45,80,.055);transition:.2s}
.service:hover{transform:translateY(-3px);box-shadow:0 15px 35px rgba(10,45,80,.11)}
.icon{font-size:27px}
.service h3{font-size:16px;color:#173e72;margin:10px 0 7px}
.service p{font-size:13px;line-height:1.55;color:var(--muted);margin:0}
.service .tag{display:inline-block;margin-top:10px;font-size:11px;color:#0b6d55;background:#e7f8f2;padding:5px 8px;border-radius:20px;font-weight:700}
.dark{background:var(--navy);color:#fff}
.dark .section-head h2,.dark .section-head p{color:#fff}
.prices{display:grid;grid-template-columns:repeat(4,1fr);gap:17px}
.price{background:#fff;color:var(--text);border-radius:18px;padding:22px;text-align:center}
.price h3{font-size:15px;color:#23456e}
.price strong{display:block;font-size:31px;color:var(--orange);margin:8px}
.price p{font-size:12px;color:var(--muted)}
.booking-area{background:linear-gradient(135deg,#edf8ff,#f7fffc)}
.booking{max-width:950px;margin:auto;background:#fff;border:1px solid var(--line);border-radius:24px;padding:27px;box-shadow:var(--shadow)}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:17px}
.full{grid-column:1/-1}
label{display:block;font-weight:800;color:#28496f;font-size:13px;margin:0 0 7px}
input,select,textarea{width:100%;padding:13px 14px;border:1px solid #d6e1ed;border-radius:10px;background:#fbfdff;font:inherit;color:#1e2d40}
input:focus,select:focus,textarea:focus{outline:2px solid #b8ddf1;border-color:#1595bd}
.notice{margin:18px 0;padding:15px;background:#fff8e8;border:1px solid #f2d494;border-radius:12px;color:#6b4d17;font-size:13px;line-height:1.6}
.submit{width:100%;border:0;padding:15px;border-radius:11px;background:linear-gradient(135deg,var(--green),#087f60);color:white;font-weight:900;font-size:16px;cursor:pointer}
.small{font-size:12px;color:#758398;line-height:1.55}
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}
.info-card{background:#fff;border:1px solid var(--line);border-radius:18px;padding:24px}
.info-card h3{color:var(--navy);margin-top:0}
.info-card p,.info-card li{color:#5e6d80;line-height:1.7;font-size:14px}
.contact-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}
.contact-card{background:#fff;border-radius:20px;border:1px solid var(--line);padding:25px}
.contact-item{padding:14px 0;border-bottom:1px solid #edf1f5}
.contact-item:last-child{border-bottom:0}
.contact-item b{color:#173e72}
footer{background:#06172f;color:#cbd9e9;padding:38px 20px}
.footer-grid{max-width:1200px;margin:auto;display:grid;grid-template-columns:2fr 1fr 1fr;gap:30px}
.footer-grid h3{color:#fff}
.footer-grid a{display:block;margin:9px 0}
.copyright{max-width:1200px;margin:25px auto 0;border-top:1px solid #ffffff1c;padding-top:17px;text-align:center;font-size:12px}
.disclaimer{background:#fff2ed;border-top:1px solid #ffd2c1;border-bottom:1px solid #ffd2c1;padding:13px 20px;text-align:center;color:#7b3c24;font-size:12px;line-height:1.55}
@media(max-width:900px){
 nav{display:none}.menu{display:block}.hero-inner{grid-template-columns:1fr}.services,.prices{grid-template-columns:repeat(2,1fr)}.hero h2{font-size:39px}
}
@media(max-width:600px){
 .services,.prices,.info-grid,.contact-grid,.form-grid{grid-template-columns:1fr}.full{grid-column:auto}.hero{padding:48px 16px}.hero h2{font-size:32px}section{padding:48px 16px}.footer-grid{grid-template-columns:1fr}.brand h1{font-size:16px}
}

]]></b:skin>
</head>
<body>


<div class='topbar'>LEKHPAL DIGITAL SERVICES &#8226; Digital Service &amp; Online Assistance &#8226; Uttar Pradesh</div>

<header>
<div class='nav'>
 <a class='brand' href='#home'>
  <div class='logo'>L</div>
  <div><h1>LEKHPAL DIGITAL SERVICES</h1><small>Online Digital &amp; Documentation Assistance</small></div>
 </a>
 <nav>
  <a href='#home'>Home</a><a href='#services'>Services</a><a href='#booking'>Book Service</a>
  <a href='#charges'>Charges</a><a href='#about'>About</a><a href='#contact'>Contact</a>
 </nav>
 <button class='menu' onclick='document.querySelector(&apos;nav&apos;).style.display=document.querySelector(&apos;nav&apos;).style.display===&apos;flex&apos;?&apos;none&apos;:&apos;flex&apos;'>&#9776;</button>
</div>
</header>

<main id='home'>
<section class='hero'>
<div class='hero-inner'>
 <div>
  <span class='badge'>PROFESSIONAL DIGITAL ASSISTANCE</span>
  <h2>आपक&#2366; क&#2366;म, <span>ड&#2367;ज&#2367;टल तर&#2368;क&#2375; स&#2375;</span></h2>
  <p>Online forms, documents, applications, typing, printing, transport-related assistance, recharge, utility services और अन&#2381;य ड&#2367;ज&#2367;टल क&#2366;र&#2381;य&#2379;&#2306; क&#2375; ल&#2367;ए एक ह&#2368; जगह पर सह&#2366;यत&#2366;&#2404;</p>
  <div class='cta'>
   <a class='btn btn-primary' href='#booking'>Service Book कर&#2375;&#2306;</a>
   <a class='btn btn-green' href='https://wa.me/917819019217?text=Hello%20LEKHPAL%20DIGITAL%20SERVICES%2C%20mujhe%20service%20ki%20jankari%20chahiye.'>WhatsApp कर&#2375;&#2306;</a>
  </div>
  <p class='small'>हम सह&#2366;यत&#2366;/ऑनल&#2366;इन आव&#2375;दन स&#2375;व&#2366; प&#2381;रद&#2366;न करत&#2375; ह&#2376;&#2306;&#2404; क&#2367;स&#2368; सरक&#2366;र&#2368; प&#2381;रम&#2366;णपत&#2381;र, ल&#2366;इस&#2375;&#2306;स य&#2366; दस&#2381;त&#2366;व&#2375;ज&#2364; क&#2368; अ&#2306;त&#2367;म स&#2381;व&#2368;क&#2371;त&#2367;/ज&#2366;र&#2368; करन&#2375; क&#2366; अध&#2367;क&#2366;र स&#2306;ब&#2306;ध&#2367;त व&#2367;भ&#2366;ग क&#2366; ह&#2379;त&#2366; ह&#2376;&#2404;</p>
 </div>
 <div class='hero-card'>
  <h3>एक ह&#2368; जगह पर ड&#2367;ज&#2367;टल सह&#2366;यत&#2366;</h3>
  <ul>
   <li>Online Form &amp; Application Assistance</li>
   <li>Certificate &amp; Document Assistance</li>
   <li>Driving Licence / RC related assistance</li>
   <li>Typing, Printing, Scanning &amp; Photo Services</li>
   <li>Mobile / DTH Recharge &amp; Utility Assistance</li>
   <li>TV &amp; AC Installation Booking</li>
  </ul>
 </div>
</div>
</section>

<section id='services'>
<div class='wrap'>
 <div class='section-head'><h2>हम&#2366;र&#2368; स&#2375;व&#2366;ए&#2305;</h2><p>ग&#2381;र&#2366;हक अपन&#2368; जर&#2370;रत क&#2375; अन&#2369;स&#2366;र स&#2375;व&#2366; च&#2369;नकर ऑनल&#2366;इन booking request भ&#2375;ज सकत&#2366; ह&#2376;&#2404;</p></div>
 <div class='services'>
  <div class='service'><div class='icon'>📝</div><h3>Online Form Filling</h3><p>व&#2367;भ&#2367;न&#2381;न ऑनल&#2366;इन forms और applications म&#2375;&#2306; data-entry सह&#2366;यत&#2366;&#2404;</p><span class='tag'>Digital Assistance</span></div>
  <div class='service'><div class='icon'>📄</div><h3>Application Assistance</h3><p>उपलब&#2381;ध ऑनल&#2366;इन स&#2375;व&#2366;ओ&#2306; क&#2375; ल&#2367;ए application preparation और submission सह&#2366;यत&#2366;&#2404;</p><span class='tag'>Online Support</span></div>
  <div class='service'><div class='icon'>🏛&#65039;</div><h3>आय / ज&#2366;त&#2367; / न&#2367;व&#2366;स</h3><p>उत&#2381;तर प&#2381;रद&#2375;श म&#2375;&#2306; प&#2366;त&#2381;र आव&#2375;दक&#2379;&#2306; क&#2375; certificate application process म&#2375;&#2306; सह&#2366;यत&#2366;&#2404;</p><span class='tag'>UP Services</span></div>
  <div class='service'><div class='icon'>📜</div><h3>Certificate Assistance</h3><p>उपलब&#2381;ध birth, death तथ&#2366; अन&#2381;य certificate application processes म&#2375;&#2306; सह&#2366;यत&#2366;&#2404;</p><span class='tag'>Application Support</span></div>
  <div class='service'><div class='icon'>🪪</div><h3>PAN / ID Assistance</h3><p>PAN तथ&#2366; अन&#2381;य eligible identity-document application/correction process म&#2375;&#2306; सह&#2366;यत&#2366;&#2404;</p><span class='tag'>Document Support</span></div>
  <div class='service'><div class='icon'>🆔</div><h3>Aadhaar Assistance</h3><p>उपलब&#2381;ध Aadhaar-related online information/application assistance; final update authorized centre पर&#2404;</p><span class='tag'>Assistance</span></div>
  <div class='service'><div class='icon'>🚗</div><h3>Driving Licence</h3><p>उत&#2381;तर प&#2381;रद&#2375;श म&#2375;&#2306; eligible driving licence application, renewal और related online process assistance&#2404;</p><span class='tag'>Transport</span></div>
  <div class='service'><div class='icon'>📋</div><h3>RC / Vehicle Services</h3><p>Eligible RC और vehicle-related online application/process assistance&#2404;</p><span class='tag'>Transport</span></div>
  <div class='service'><div class='icon'>🗳&#65039;</div><h3>Voter Services</h3><p>Voter-related eligible online application और correction process म&#2375;&#2306; सह&#2366;यत&#2366;&#2404;</p><span class='tag'>Online Assistance</span></div>
  <div class='service'><div class='icon'>🍚</div><h3>Ration Card Assistance</h3><p>Eligible ration-card application/correction process म&#2375;&#2306; digital assistance&#2404;</p><span class='tag'>Online Support</span></div>
  <div class='service'><div class='icon'>🏦</div><h3>Government Scheme Assistance</h3><p>उपलब&#2381;ध सरक&#2366;र&#2368; य&#2379;जन&#2366;ओ&#2306; क&#2368; online application प&#2381;रक&#2381;र&#2367;य&#2366; म&#2375;&#2306; सह&#2366;यत&#2366;&#2404;</p><span class='tag'>Scheme Support</span></div>
  <div class='service'><div class='icon'>🖨&#65039;</div><h3>Print / Scan / PDF</h3><p>Printing, scanning, PDF conversion और digital document preparation&#2404;</p><span class='tag'>Document</span></div>
  <div class='service'><div class='icon'>&#9000;&#65039;</div><h3>Typing &amp; Letter</h3><p>Hindi/English typing, applications, letters और basic document drafting&#2404;</p><span class='tag'>Typing</span></div>
  <div class='service'><div class='icon'>📸</div><h3>Photo / Digital Photo</h3><p>Document-size photo preparation और basic digital photo service&#2404;</p><span class='tag'>Photo Service</span></div>
  <div class='service'><div class='icon'>📱</div><h3>Mobile Recharge</h3><p>Supported operators क&#2375; mobile recharge म&#2375;&#2306; assistance&#2404;</p><span class='tag'>Recharge</span></div>
  <div class='service'><div class='icon'>📺</div><h3>TV / DTH Recharge</h3><p>Supported DTH/TV services क&#2375; recharge म&#2375;&#2306; assistance&#2404;</p><span class='tag'>Recharge</span></div>
  <div class='service'><div class='icon'>💡</div><h3>Utility Bill Assistance</h3><p>Eligible electricity तथ&#2366; अन&#2381;य digital utility payment assistance&#2404;</p><span class='tag'>Utility</span></div>
  <div class='service'><div class='icon'>🔧</div><h3>TV Installation</h3><p>TV installation service booking. Service charge &#8377;1,100.</p><span class='tag'>Home Service</span></div>
  <div class='service'><div class='icon'>&#10052;&#65039;</div><h3>AC Installation</h3><p>AC installation service booking. Service charge &#8377;1,200.</p><span class='tag'>Home Service</span></div>
  <div class='service'><div class='icon'>📑</div><h3>Online Registration</h3><p>Eligible online registration, appointment और application processes म&#2375;&#2306; सह&#2366;यत&#2366;&#2404;</p><span class='tag'>Registration</span></div>
  <div class='service'><div class='icon'>🧾</div><h3>Document Formatting</h3><p>Documents क&#2379; स&#2366;फ format करन&#2366;, PDF बन&#2366;न&#2366; और digital file preparation&#2404;</p><span class='tag'>Documentation</span></div>
 </div>
</div>
</section>

<section class='dark' id='charges'>
<div class='wrap'>
 <div class='section-head'><h2>Service Charges</h2><p>न&#2368;च&#2375; क&#2369;छ स&#2366;म&#2366;न&#2381;य assistance/service charges ह&#2376;&#2306;&#2404; ल&#2366;ग&#2370; सरक&#2366;र&#2368;/third-party fees अलग ह&#2379; सकत&#2368; ह&#2376;&#2306;&#2404;</p></div>
 <div class='prices'>
  <div class='price'><h3>Document Assistance</h3><strong>&#8377;49</strong><p>Basic document assistance</p></div>
  <div class='price'><h3>Form Filling</h3><strong>&#8377;100</strong><p>Basic online form assistance</p></div>
  <div class='price'><h3>Application Assistance</h3><strong>&#8377;199</strong><p>Online application support</p></div>
  <div class='price'><h3>Digital Service</h3><strong>&#8377;399</strong><p>Extended digital assistance</p></div>
  <div class='price'><h3>TV Installation</h3><strong>&#8377;1,100</strong><p>Service booking charge</p></div>
  <div class='price'><h3>AC Installation</h3><strong>&#8377;1,200</strong><p>Service booking charge</p></div>
 </div>
</div>
</section>

<section class='booking-area' id='booking'>
<div class='wrap'>
 <div class='section-head'><h2>Service Booking</h2><p>न&#2366;म, म&#2379;ब&#2366;इल, ईम&#2375;ल और प&#2370;र&#2366; पत&#2366; भर&#2375;&#2306;&#2404; Submit करन&#2375; पर WhatsApp म&#2375;&#2306; booking message ख&#2369;ल ज&#2366;एग&#2366;&#2404;</p></div>
 <form class='booking' id='bookingForm'>
  <div class='form-grid'>
   <div><label>Customer Name *</label><input id='name' placeholder='पूरा नाम' required='required'/></div>
   <div><label>Mobile Number *</label><input id='phone' inputmode='numeric' maxlength='10' placeholder='10 अंकों का मोबाइल नंबर' required='required' type='tel'/></div>
   <div><label>Email ID</label><input id='email' placeholder='example@email.com' type='email'/></div>
   <div><label>Select Service *</label>
    <select id='service' required='required'>
     <option value=''>-- स&#2375;व&#2366; च&#2369;न&#2375;&#2306; --</option>
     <option>Document Assistance - &#8377;49</option><option>Online Form Filling - &#8377;100</option>
     <option>Application Assistance - &#8377;199</option><option>Digital Service - &#8377;399</option>
     <option>आय / ज&#2366;त&#2367; / न&#2367;व&#2366;स Assistance</option><option>Certificate Assistance</option>
     <option>PAN / ID Assistance</option><option>Aadhaar Assistance</option><option>Driving Licence Assistance</option>
     <option>RC / Vehicle Services</option><option>Voter Services</option><option>Ration Card Assistance</option>
     <option>Government Scheme Assistance</option><option>Print / Scan / PDF</option><option>Typing &amp; Letter</option>
     <option>Photo / Digital Photo</option><option>Mobile Recharge</option><option>TV / DTH Recharge</option>
     <option>Utility Bill Assistance</option><option>TV Installation - &#8377;1,100</option><option>AC Installation - &#8377;1,200</option>
     <option>Online Registration</option><option>Document Formatting</option>
    </select>
   </div>
   <div><label>Preferred Date</label><input id='date' type='date'/></div>
   <div class='full'><label>प&#2370;र&#2366; Address *</label><textarea id='address' placeholder='मकान/गाँव, पोस्ट, तहसील, जिला, राज्य, पिनकोड' required='required' rows='3'/></div>
   <div class='full'><label>क&#2366;म/सर&#2381;व&#2367;स क&#2366; व&#2367;वरण</label><textarea id='details' placeholder='आपको किस काम की आवश्यकता है?' rows='3'/></div>
  </div>
  <div class='notice'>
   <b>&#8377;100 Booking / Advance:</b> यद&#2367; आपक&#2368; च&#2369;न&#2368; ह&#2369;ई स&#2375;व&#2366; पर &#8377;100 advance ल&#2366;ग&#2370; ह&#2376;, त&#2379; WhatsApp पर booking details भ&#2375;जन&#2375; क&#2375; ब&#2366;द payment instructions/link द&#2367;य&#2366; ज&#2366;एग&#2366;&#2404; Payment करन&#2375; स&#2375; पहल&#2375; service और final amount क&#2368; प&#2369;ष&#2381;ट&#2367; कर&#2375;&#2306;&#2404;
  </div>
  <button class='submit' type='submit'>Booking Submit कर&#2375;&#2306; &#8594; WhatsApp</button>
  <p class='small'>Submit करन&#2375; पर WhatsApp आपक&#2375; म&#2379;ब&#2366;इल म&#2375;&#2306; ख&#2369;ल&#2375;ग&#2366; और message म&#2375;&#2306; आपक&#2368; booking details द&#2367;ख&#2366;ई द&#2375;&#2306;ग&#2368;&#2404; ग&#2381;र&#2366;हक स&#2381;वय&#2306; Send दब&#2366;कर message भ&#2375;ज सकत&#2366; ह&#2376;&#2404;</p>
 </form>
</div>
</section>

<section id='about'>
<div class='wrap'>
 <div class='section-head'><h2>About LEKHPAL DIGITAL SERVICES</h2><p>Individual digital service business</p></div>
 <div class='info-grid'>
  <div class='info-card'><h3>हम&#2366;र&#2375; ब&#2366;र&#2375; म&#2375;&#2306;</h3><p>LEKHPAL DIGITAL SERVICES एक individual digital service business ह&#2376; ज&#2379; online application, documentation, typing, printing, scanning और अन&#2381;य digital assistance प&#2381;रद&#2366;न करत&#2366; ह&#2376;&#2404;</p><p>हम क&#2367;स&#2368; सरक&#2366;र&#2368; व&#2367;भ&#2366;ग क&#2375; न&#2366;म स&#2375; क&#2366;र&#2381;य करन&#2375; क&#2366; द&#2366;व&#2366; नह&#2368;&#2306; करत&#2375;&#2404; स&#2306;ब&#2306;ध&#2367;त व&#2367;भ&#2366;ग/अध&#2367;क&#2371;त स&#2306;स&#2381;थ&#2366; ह&#2368; अ&#2306;त&#2367;म approval, issuance य&#2366; update करत&#2368; ह&#2376;&#2404;</p></div>
  <div class='info-card'><h3>Customer Guidelines</h3><ul><li>ग&#2381;र&#2366;हक सह&#2368; और व&#2376;ध ज&#2366;नक&#2366;र&#2368; द&#2375;&#2306;&#2404;</li><li>जह&#2366;&#2305; ल&#2366;ग&#2370; ह&#2379;, सरक&#2366;र&#2368;/third-party fee अलग ह&#2379; सकत&#2368; ह&#2376;&#2404;</li><li>Application approval स&#2306;ब&#2306;ध&#2367;त authority पर न&#2367;र&#2381;भर ह&#2376;&#2404;</li><li>Service श&#2369;र&#2370; ह&#2379;न&#2375; स&#2375; पहल&#2375; applicable charge क&#2368; प&#2369;ष&#2381;ट&#2367; कर&#2375;&#2306;&#2404;</li></ul></div>
 </div>
</div>
</section>

<section>
<div class='wrap'>
 <div class='section-head'><h2>Policies</h2><p>ग&#2381;र&#2366;हक&#2379;&#2306; क&#2375; ल&#2367;ए स&#2381;पष&#2381;ट service terms</p></div>
 <div class='info-grid'>
  <div class='info-card'><h3>Terms &amp; Conditions</h3><p>Services selected=&quot;selected&quot; by the customer are provided according to the service description. Customer is responsible for accurate information/documents. Government or third-party approval is not guaranteed.</p></div>
  <div class='info-card'><h3>Refund &amp; Cancellation</h3><p>Cancellation/refund requests are considered according to work status. Completed work and non-refundable third-party/government charges may not be refundable. Contact support with booking details.</p></div>
  <div class='info-card'><h3>Privacy Policy</h3><p>Customer details are used for providing the requested service and customer support, subject to applicable law. We do not intentionally sell customer information.</p></div>
  <div class='info-card'><h3>Grievance &amp; Support</h3><p>For complaints, corrections or service questions, contact us by phone, email or WhatsApp. Please keep your booking details available.</p></div>
 </div>
</div>
</section>

<section id='contact' style='background:#edf5fc'>
<div class='wrap'>
 <div class='section-head'><h2>Contact &amp; WhatsApp Booking</h2><p>Service enquiry और booking क&#2375; ल&#2367;ए स&#2368;ध&#2375; स&#2306;पर&#2381;क कर&#2375;&#2306;&#2404;</p></div>
 <div class='contact-grid'>
  <div class='contact-card'>
   <div class='contact-item'><b>Business Name</b><br/>LEKHPAL DIGITAL SERVICES</div>
   <div class='contact-item'><b>Address</b><br/>Mainpuri district Mainpuri , Mainpuri, Uttar Pradesh &#8211; 205264</div>
   <div class='contact-item'><b>Phone / WhatsApp</b><br/><a href='tel:7819019217'>7819019217</a></div>
   <div class='contact-item'><b>Email</b><br/><a href='mailto:kmpooja0851997@gmail.com'>kmpooja0851997@gmail.com</a></div>
  </div>
  <div class='contact-card'>
   <h3>Quick Contact</h3>
   <p class='small'>अगर booking form नह&#2368;&#2306; भरन&#2366; च&#2366;हत&#2375; ह&#2376;&#2306; त&#2379; WhatsApp पर स&#2368;ध&#2375; enquiry भ&#2375;ज सकत&#2375; ह&#2376;&#2306;&#2404;</p>
   <div class='cta'>
    <a class='btn btn-green' href='https://wa.me/917819019217?text=Hello%20LEKHPAL%20DIGITAL%20SERVICES%2C%20mujhe%20service%20book%20karni%20hai.'>WhatsApp Now</a>
    <a class='btn btn-primary' href='tel:7819019217'>Call Now</a>
   </div>
  </div>
 </div>
</div>
</section>

<div class='disclaimer'>LEKHPAL DIGITAL SERVICES स&#2381;वत&#2306;त&#2381;र digital assistance business ह&#2376;&#2404; यह व&#2375;बस&#2366;इट क&#2367;स&#2368; सरक&#2366;र&#2368; व&#2367;भ&#2366;ग/CSC/UIDAI/RTO अथव&#2366; अन&#2381;य authority क&#2368; official website नह&#2368;&#2306; ह&#2376;&#2404; Government/third-party services क&#2368; final approval, issuance और processing स&#2306;ब&#2306;ध&#2367;त authority क&#2375; न&#2367;यम&#2379;&#2306; क&#2375; अन&#2369;स&#2366;र ह&#2379;त&#2368; ह&#2376;&#2404;</div>
</main>

<footer>
<div class='footer-grid'>
 <div><h3>LEKHPAL DIGITAL SERVICES</h3><p>Online Digital &amp; Documentation Assistance</p><p>Mainpuri district , Karhal, Mainpuri, Uttar Pradesh &#8211; 205264</p></div>
 <div><h3>Quick Links</h3><a href='#services'>Services</a><a href='#booking'>Book Service</a><a href='#charges'>Charges</a><a href='#contact'>Contact</a></div>
 <div><h3>Support</h3><a href='tel:7819019217'>7819019217</a><a href='mailto:kmpooja0851997@gmail.com'>kmpooja0851997@gmail.com</a><a href='https://wa.me/917819019217'>WhatsApp</a></div>
</div>
<div class='copyright'>&#169; 2026 LEKHPAL DIGITAL SERVICES. All rights reserved.</div>
</footer>

<script>
document.getElementById(&quot;bookingForm&quot;).addEventListener(&quot;submit&quot;,function(e){
 e.preventDefault();
 const name=document.getElementById(&quot;name&quot;).value.trim();
 const phone=document.getElementById(&quot;phone&quot;).value.trim();
 const email=document.getElementById(&quot;email&quot;).value.trim() || &quot;Not provided&quot;;
 const service=document.getElementById(&quot;service&quot;).value;
 const date=document.getElementById(&quot;date&quot;).value || &quot;Not specified&quot;;
 const address=document.getElementById(&quot;address&quot;).value.trim();
 const details=document.getElementById(&quot;details&quot;).value.trim() || &quot;Not provided&quot;;
 if(!/^[0-9]{10}$/.test(phone)){alert(&quot;क&#2371;पय&#2366; सह&#2368; 10 अ&#2306;क&#2379;&#2306; क&#2366; म&#2379;ब&#2366;इल न&#2306;बर ड&#2366;ल&#2375;&#2306;&#2404;&quot;);return;}
 const msg=`*LEKHPAL DIGITAL SERVICES - NEW BOOKING*%0A%0ACustomer Name: ${encodeURIComponent(name)}%0AMobile: ${encodeURIComponent(phone)}%0AEmail: ${encodeURIComponent(email)}%0AService: ${encodeURIComponent(service)}%0APreferred Date: ${encodeURIComponent(date)}%0AAddress: ${encodeURIComponent(address)}%0AWork Details: ${encodeURIComponent(details)}%0A%0ABooking/Advance: &#8377;100 (if applicable)%0A%0APlease confirm service availability, final charges and payment instructions.`;
 window.location.href=&quot;https://wa.me/917819019217?text=&quot;+msg;
});
</script>

<b:section class='footer' id='blogger-footer' maxwidgets='1' showaddelement='no'>
  <b:widget id='HTML99' locked='true' title='' type='HTML' version='2' visible='true'>
    <b:includable id='main'>
<div style='display:none'>LEKHPAL DIGITAL SERVICES</div>
</b:includable>
  </b:widget>
</b:section>
</body>
</html>
