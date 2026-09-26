<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GameZone Pro Dashboard</title>
  <style>
    :root {
      --primary: #00ffcc;
      --accent: #ff0055;
      --bg-dark: #0a0a0c;
      --card-bg: rgba(22, 22, 30, 0.9);
    }
    body {
      margin: 0; padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: var(--bg-dark);
      color: #ffffff;
      overflow-x: hidden;
    }
    .bg-effects {
      position: fixed; top: 0; left: 0; width: 100%; height: 100%;
      pointer-events: none; opacity: 0.12;
      background-image: url('https://www.transparenttextures.com/patterns/cubes.png');
      z-index: -1;
    }
    .container { width: 92%; max-width: 1200px; margin: 0 auto; padding: 20px 0; }
    
    /* Header */
    header {
      display: flex; justify-content: space-between; align-items: center;
      padding: 15px 20px; background: rgba(15, 15, 20, 0.8);
      border-radius: 15px; border: 1px solid #222230;
      backdrop-filter: blur(10px);
    }
    .logo { font-size: 26px; font-weight: 900; color: var(--primary); text-shadow: 0 0 10px rgba(0, 255, 204, 0.5); }
    .user-controls { display: flex; align-items: center; gap: 15px; }
    .login-btn {
      background: linear-gradient(135deg, var(--accent), #ff5500);
      color: white; border: none; padding: 10px 20px; border-radius: 8px;
      cursor: pointer; font-weight: bold; transition: 0.3s;
    }
    .wallet-section {
      background: #16161f; padding: 8px 18px; border-radius: 10px;
      border: 1px solid #333345; display: flex; align-items: center; gap: 12px;
    }
    .balance span { color: #ffd700; font-size: 20px; font-weight: bold; }
    
    /* Hero Banner */
    .hero { text-align: center; padding: 40px 0; }
    .hero h1 { font-size: 40px; margin-bottom: 10px; background: linear-gradient(to right, #00ffcc, #0099ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

    /* Games Section Grid */
    .games-grid {
      display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px; margin-top: 30px;
    }
    .game-card {
      background: var(--card-bg); border-radius: 16px; padding: 20px;
      border: 1px solid #2a2a3d; text-align: center; position: relative;
      transition: 0.3s; box-shadow: 0 8px 25px rgba(0,0,0,0.4);
    }
    .game-card:hover { transform: translateY(-5px); border-color: var(--primary); }
    .game-icon { font-size: 45px; margin-bottom: 10px; }
    .game-title { font-size: 20px; margin-bottom: 8px; color: var(--primary); }
    .game-desc { font-size: 13px; color: #8888a0; margin-bottom: 15px; }
    .play-btn {
      background: linear-gradient(135deg, var(--primary), #00a8ff);
      color: #000; border: none; padding: 10px 25px; border-radius: 25px;
      font-weight: bold; cursor: pointer; text-transform: uppercase; width: 100%;
    }

    /* Modal for Games */
    .modal {
      display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.85); z-index: 1000; justify-content: center; align-items: center;
    }
    .modal-content {
      background: #151520; padding: 30px; border-radius: 20px;
      border: 1px solid var(--primary); text-align: center; width: 320px; position: relative;
    }
    .close-modal { position: absolute; top: 10px; right: 15px; font-size: 24px; cursor: pointer; color: #aaa; }
    
    /* Spin Wheel Canvas */
    #wheel { border-radius: 50%; border: 4px solid var(--primary); margin: 15px 0; }
    
    /* Referral Section */
    .referral-card {
      margin-top: 40px; background: #12121a; padding: 25px;
      border-radius: 15px; border: 1px dashed var(--primary); text-align: center;
    }
    .ref-code { font-size: 22px; color: #ffd700; font-weight: bold; letter-spacing: 2px; }
  </style>
</head>
<body>
  <div class="bg-effects"></div>

  <div class="container">
    <header>
      <div class="logo">GameZone</div>
      <div class="user-controls">
        <button class="login-btn" onclick="loginUser()">Google Login</button>
        <div class="wallet-section">
          <span>🪙</span>
          <div class="balance">Wallet: <span id="wallet-balance">₹0.00</span></div>
          <!-- Razorpay Payment Button -->
          <form style="display:inline;">
            <script src="https://checkout.razorpay.com/v1/payment-button.js" data-payment_button_id="pl_Tf1agSEA3hN5mU" async></script>
          </form>
        </div>
      </div>
    </header>

    <section class="hero">
      <h1>Play & Win Real Rewards</h1>
      <p>Choose your favorite arena and start winning!</p>
    </section>

    <!-- 5 Live Playable Games -->
    <div class="games-grid">
      <!-- Game 1 -->
      <div class="game-card">
        <div class="game-icon">🎰</div>
        <div class="game-title">Spin & Win</div>
        <div class="game-desc">Spin the wheel and double your money. Entry: ₹10</div>
        <button class="play-btn" onclick="openGame('spin')">Play Now</button>
      </div>
      <!-- Game 2 -->
      <div class="game-card">
        <div class="game-icon">🪙</div>
        <div class="game-title">Head or Tail</div>
        <div class="game-desc">50/50 Chance! Predict and win instantly. Entry: ₹10</div>
        <button class="play-btn" onclick="openGame('toss')">Play Now</button>
      </div>
      <!-- Game 3 -->
      <div class="game-card">
        <div class="game-icon">🎲</div>
        <div class="game-title">Lucky Dice</div>
        <div class="game-desc">Roll above 3 to win double payout! Entry: ₹10</div>
        <button class="play-btn" onclick="openGame('dice')">Play Now</button>
      </div>
      <!-- Game 4 -->
      <div class="game-card">
        <div class="game-icon">📦</div>
        <div class="game-title">Mystery Box</div>
        <div class="game-desc">Pick 1 of 3 boxes to unlock jackpot! Entry: ₹20</div>
        <button class="play-btn" onclick="openGame('mystery')">Play Now</button>
      </div>
      <!-- Game 5 -->
      <div class="game-card">
        <div class="game-icon">🎯</div>
        <div class="game-title">Color Prediction</div>
        <div class="game-desc">Pick Red or Green to double cash. Entry: ₹10</div>
        <button class="play-btn" onclick="openGame('color')">Play Now</button>
      </div>
    </div>

    <div class="referral-card">
      <h3>Refer & Earn</h3>
      <p class="ref-code">GZ-9982</p>
      <p>Share with friends to get <strong>₹10 Instant Bonus</strong> per signup!</p>
    </div>
  </div>

  <!-- Game Popups (Modals) -->
  <div class="modal" id="gameModal">
    <div class="modal-content">
      <span class="close-modal" onclick="closeModal()">&times;</span>
      <h2 id="modalTitle" style="color:var(--primary)">Game</h2>
      <div id="gameBody"></div>
    </div>
  </div>

  <script>
    let currentBalance = 0;

    // Sounds
    const loginSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2013/2013-preview.mp3');
    const coinSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2019/2019-preview.mp3');

    function updateBalance(amount) {
      currentBalance += amount;
      document.getElementById('wallet-balance').innerText = "₹" + currentBalance.toFixed(2);
    }

    function loginUser() {
      loginSound.play();
      alert("Login Successful! Welcome to GameZone.");
      updateBalance(10); // ₹10 Referral/Signup bonus
    }

    // Modal Control
    function openGame(gameType) {
      const modal = document.getElementById('gameModal');
      const title = document.getElementById('modalTitle');
      const body = document.getElementById('gameBody');
      modal.style.display = 'flex';

      if (gameType === 'spin') {
        title.innerText = "Spin & Win (Entry ₹10)";
        body.innerHTML = `
          <p>Click Spin to win up to ₹50!</p>
          <button class="play-btn" onclick="playSpin()">SPIN WHEEL</button>
          <h3 id="spinResult"></h3>
        `;
      } else if (gameType === 'toss') {
        title.innerText = "Head or Tail (Entry ₹10)";
        body.innerHTML = `
          <button class="play-btn" style="margin-bottom:10px" onclick="playToss('Head')">Pick HEAD</button>
          <button class="play-btn" onclick="playToss('Tail')">Pick TAIL</button>
          <h3 id="tossResult"></h3>
        `;
      } else if (gameType === 'dice') {
        title.innerText = "Lucky Dice (Entry ₹10)";
        body.innerHTML = `
          <p>Roll dice! 4, 5 or 6 wins double cash.</p>
          <button class="play-btn" onclick="playDice()">ROLL DICE 🎲</button>
          <h3 id="diceResult"></h3>
        `;
      } else if (gameType === 'mystery') {
        title.innerText = "Mystery Box (Entry ₹20)";
        body.innerHTML = `
          <p>Choose 1 Box:</p>
          <button class="play-btn" onclick="playBox(1)" style="width:30%">📦 1</button>
          <button class="play-btn" onclick="playBox(2)" style="width:30%">📦 2</button>
          <button class="play-btn" onclick="playBox(3)" style="width:30%">📦 3</button>
          <h3 id="boxResult"></h3>
        `;
      } else if (gameType === 'color') {
        title.innerText = "Color Predict (Entry ₹10)";
        body.innerHTML = `
          <button class="play-btn" style="background:#ff0055; color:white; margin-bottom:10px" onclick="playColor('Red')">RED</button>
          <button class="play-btn" style="background:#00ffcc; color:black" onclick="playColor('Green')">GREEN</button>
          <h3 id="colorResult"></h3>
        `;
      }
    }

    function closeModal() {
      document.getElementById('gameModal').style.display = 'none';
    }

    // Game Logics
    function playSpin() {
      if(currentBalance < 10) { alert("Insufficient Balance! Deposit Money first."); return; }
      updateBalance(-10);
      let win = Math.random() > 0.4 ? 20 : 0;
      setTimeout(() => {
        if(win > 0) { coinSound.play(); updateBalance(win); document.getElementById('spinResult').innerText = "🎉 You Won ₹" + win; }
        else { document.getElementById('spinResult').innerText = "❌ Better Luck Next Time!"; }
      }, 500);
    }

    function playToss(choice) {
      if(currentBalance < 10) { alert("Insufficient Balance!"); return; }
      updateBalance(-10);
      let outcome = Math.random() > 0.5 ? 'Head' : 'Tail';
      if(choice === outcome) {
        coinSound.play(); updateBalance(20);
        document.getElementById('tossResult').innerText = "🎉 Result: " + outcome + ". You Won ₹20!";
      } else {
        document.getElementById('tossResult').innerText = "❌ Result: " + outcome + ". You Lost!";
      }
    }

    function playDice() {
      if(currentBalance < 10) { alert("Insufficient Balance!"); return; }
      updateBalance(-10);
      let roll = Math.floor(Math.random() * 6) + 1;
      if(roll > 3) {
        coinSound.play(); updateBalance(20);
        document.getElementById('diceResult').innerText = "🎲 Rolled: " + roll + ". You Won ₹20!";
      } else {
        document.getElementById('diceResult').innerText = "🎲 Rolled: " + roll + ". Better Luck Next Time!";
      }
    }

    function playBox(num) {
      if(currentBalance < 20) { alert("Insufficient Balance!"); return; }
      updateBalance(-20);
      let prizes = [0, 15, 40];
      let won = prizes[Math.floor(Math.random() * prizes.length)];
      if(won > 0) { coinSound.play(); updateBalance(won); }
      document.getElementById('boxResult').innerText = "📦 You Unlocked ₹" + won + "!";
    }

    function playColor(color) {
      if(currentBalance < 10) { alert("Insufficient Balance!"); return; }
      updateBalance(-10);
      let winColor = Math.random() > 0.5 ? 'Red' : 'Green';
      if(color === winColor) {
        coinSound.play(); updateBalance(20);
        document.getElementById('colorResult').innerText = "🎯 Color: " + winColor + ". Won ₹20!";
      } else {
        document.getElementById('colorResult').innerText = "🎯 Color: " + winColor + ". Lost!";
      }
    }
  </script>
</body>
</html>
