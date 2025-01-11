<?php
require(__DIR__ . "/../../partials/nav.php");
?>
<div class="container-fluid">
    <h4 class="game-title">:</h4>
    <canvas id="canvas" tabindex="1"></canvas>
</div>

<?php
  $user_id = get_user_id();
  $user_theme = $user_id <= 0 ? -1 : get_theme($user_id);
?>

<script type="module">

import { themes, themesList } from "./clauster/themes.js"

//Clauster

/* ***User*** */
const user = <?php echo json_encode($user_id); ?>;

/* ***Canvas*** */
var canvas = document.getElementById('canvas');

// Set the width and height of the canvas
const screenWidth = window.innerWidth;
var canvasSize = 800;
if (screenWidth <= 1000) {
  canvasSize = 500;
}
if (screenWidth <= 550) {
  canvasSize = 300;
}
canvas.width = canvasSize;
canvas.height = canvasSize;

// Get canvas context and units based on its size
var context = canvas.getContext('2d');
var sizingConstant = canvas.width / 800;
context.textBaseline = "middle"

/* ***Physical attributes of game members*** */
var charSize = 40 * sizingConstant;
var bulletSpeed = 1500 * sizingConstant;
var bulletSize = 15 * sizingConstant;
var clusterRadius = 80 * sizingConstant;
var enemySpeed = 400 * sizingConstant;
var enemyHealth = 1;
var bossSpeed = 200 * sizingConstant;
var bossHealth = 3;

/* ***Theme*** */
// Get user's theme or set default
var theme = themesList[getTheme()];

/* ***Button properties for menus*** */
var buttonWidth = 250*sizingConstant;
var buttonHeight = 50*sizingConstant;

/* ***Mouse Input*** */
// Get mouse coordinates relative to canvas element
var mouseX = 0;
var mouseY = 0;
var docWidth = window.innerWidth;
var docLength = window.innerHeight;

document.addEventListener('mousemove', (event) => {
  var rect = canvas.getBoundingClientRect();
  var canvasPosX = rect.left + window.scrollX;
  var canvasPosY = rect.top + window.scrollY;
  mouseX = event.clientX - canvasPosX;
  mouseY = event.clientY - canvasPosY;
});


/* ***Keyboard input*** */
// Key Codes
var w = 87;

// Keep track of pressed keys
var keys = {w: false};

// Listen for key press
canvas.addEventListener('keydown', function(e) {
  if (e.keyCode === w) {
    keys.w = true;
  }
});

// Listen for keyup events
canvas.addEventListener('keyup', function(e) {
  if (e.keyCode === w) { // UP 
    keys.w = false;
  }
});

document.addEventListener("touchstart", (e) => {
  var rect = canvas.getBoundingClientRect();
  var canvasPosX = rect.left + window.scrollX;
  var canvasPosY = rect.top + window.scrollY;
  clientX = e.touches[0].clientX;
  clientY = e.touches[0].clientY;
  mouseX = clientX - canvasPosX;
  mouseY = clientY - canvasPosY;
  keys.w = true;
});

document.addEventListener("touchend", () => {
  keys.w = false;
});

/* ***Helper functions*** */

//Function to make a square character
function makeSquare(x, y, length, speed, inCircle=false, originalHealth=0, health=0, showHealth=false) {
  return {
    x: x,
    y: y,
    l: length,
    s: speed,
    c: false,
    h: health,
    oh: originalHealth,
    draw: function() {
      context.fillRect(this.x - length/2, this.y - length/2, this.l, this.l);
      if (showHealth) {
        if (this.h >= 6) {
          context.fillStyle = "#FF0000";
        }
        else if (this.h >= 4) {
          context.fillStyle = "#FFA700";
        }
        else if (this.h >= 2) {
          context.fillStyle = "#FFF400";
        }
        else {
          context.fillStyle = "#A3FF00";
        }
        context.fillRect(this.x - length/2, this.y - length, this.l * this.h/this.oh, this.l/5);
      }
    }
  };
}

// Draws a circle at (x,y) with radius r and colored color
function makeCircle(x, y, r, color) {
  context.beginPath();
  context.arc(x, y, r, 0, 2 * Math.PI, false);
  context.lineWidth = 4 * sizingConstant;
  context.strokeStyle = color;
  context.stroke();
}

// Check if number a is in the range b to c (exclusive)
function isWithin(a, b, c) {
  return (a >= b && a <= c);
}

// Return true if two squares a and b are colliding, false otherwise
function isColliding(a, b) {
  var result = false;
  if ((a.x + a.l/2 >= b.x - b.l/2) && (a.x - a.l/2 <= b.x + b.l/2)) {
    if ((a.y + a.l/2 >= b.y - b.l/2) && (a.y - a.l/2 <= b.y + b.l/2)) {
      result = true;
    }
  }
  return result;
}

// Makes a bullet pointing towards (x,y) and updates bullets
function makeBullet(x,y) { 
  bullets.push(makeSquare(canvas.width/2, canvas.height/2, bulletSize, normalize([x - canvas.width/2, y - canvas.height/2], bulletSpeed)));
}

// Make enemy come from edges of screen (returns [x,y])
function getEdge() {
  var sides = [0, canvas.width, 0, canvas.height];
  var index = Math.round(Math.random() * 5);
  var horizontal = [sides[index], Math.round(Math.random() * (canvas.height - charSize * 2)) + charSize]; //random place on left or right side
  var vertical = [Math.round(Math.random() * (canvas.width - charSize * 2)) + charSize, sides[index]]; //random place on top or bottom side
  var result = (index < 2) ? horizontal : vertical;
  return result;
}

// Normalize a vector
function normalize(vector, speed) {
  var mag = Math.sqrt(vector[0]*vector[0] + vector[1]*vector[1]);
  return [speed * vector[0] / mag, speed * vector[1] / mag];
}

// Make enemy
function makeEnemy() { 
  var position = getEdge();
  var enemyX = position[0];
  var enemyY = position[1];
  enemies.push(makeSquare(enemyX, enemyY, charSize, normalize([enemyX - canvas.width/2, enemyY - canvas.height/2], enemySpeed), false, enemyHealth, enemyHealth, true));
}

// Make boss enemy
function makeBossEnemy() { 
  var position = getEdge();
  var enemyX = position[0];
  var enemyY = position[1];
  enemies.push(makeSquare(enemyX, enemyY, charSize, normalize([enemyX - canvas.width/2, enemyY - canvas.height/2], bossSpeed), false, bossHealth, bossHealth, true));
}
// Clear the canvas
function erase() {
  context.fillStyle = theme.backgroundColor;
  context.fillRect(0, 0, canvas.width, canvas.height);
}

/* Click handlers for each screen */
let menuClickListener;
function handleMenuClick (startX, startY, instructionsX, instructionsY, themesX, themesY) {
  // Start game
  if (mouseX > startX - buttonWidth/2 && mouseX < startX + buttonWidth/2 && mouseY > startY - buttonHeight/2 && mouseY < startY + buttonHeight/2) {
    startGame();
  }
  // Instructions screen
  else if (mouseX > instructionsX - buttonWidth/2 && mouseX < instructionsX + buttonWidth/2 && mouseY > instructionsY - buttonHeight/2 && mouseY < instructionsY + buttonHeight/2) {
    instructionPage();
  }
  // Themes screen
  else if (mouseX > themesX - buttonWidth/2 && mouseX < themesX + buttonWidth/2 && mouseY > themesY - buttonHeight/2 && mouseY < themesY + buttonHeight/2) {
    themePage();
  }
}

let instructionsClickListener;
function handleInstructionsClick () {
  menu();
}

let themesClickListener;
function handleThemesClick (themeButtons, themeWidth, themeHeight, returnX, returnY) {
  // Handle theme buttons click
  for (let i = 0; i < themeButtons.length; i++) {
    let x = themeButtons[i][0];
    let y = themeButtons[i][1];
    let thisTheme = themeButtons[i][2];

    if (mouseX > x - themeWidth/2 && mouseX < x + themeWidth/2 && mouseY > y - themeHeight/2 && mouseY < y + themeHeight/2) {
      setTheme(i);
    }
  }
  
  // Handle return to menu click
  if (mouseX > returnX - buttonWidth/2 && mouseX < returnX + buttonWidth/2 && mouseY > returnY - buttonHeight/2 && mouseY < returnY + buttonHeight/2) {
    menu();
  }
}

/*  ***Pausing*** */

// Pause button properties
var pauseWidth = canvas.width / 50;
var pauseHeight = canvas.height / 50;
var pauseX = canvas.width - 20 * sizingConstant;
var pauseY = 20*sizingConstant;

// Track if game is paused
var isPaused = false;

// Last frame of animation
var lst;

/* ***Enemy spawning*** */

// Configuration settings
const enemyConfig = {
    timeBetweenEnemies: 1000, 
    bossSpawnMinTime: 5000,   
    bossSpawnMaxTime: 7000,   
    initialDelay: 1000,       
};

var enemyTimeoutId;
var bossTimeoutId;
var bossRandomDelay;

var enemyTimerStarted;
var enemyTimerStopped;   
var bossTimerStarted;
var bossTimerStopped;

// Spawn regular enemies
function spawnEnemies() {
  enemyTimerStarted = Date.now();
  enemyTimeoutId = setTimeout(() => {
    makeEnemy(); 
    spawnEnemies(); 
  }, enemyConfig.timeBetweenEnemies);
}

// Spawn boss enemies
function spawnBossEnemy() {
  bossTimerStarted = Date.now();
  bossRandomDelay = getRandomInt(enemyConfig.bossSpawnMinTime, enemyConfig.bossSpawnMaxTime);
  bossTimeoutId = setTimeout(() => {
    makeBossEnemy(); 
    spawnBossEnemy(); 
  }, bossRandomDelay);
}

// Stop spawning enemies
function stopSpawning() {
  enemyTimerStopped = Date.now();
  clearTimeout(enemyTimeoutId);
  bossTimerStopped = Date.now();
  clearTimeout(bossTimeoutId);
}

// Start spawning enemies again
function startSpawning() {
  // Resume last enemy interval
  let enemyElapsed = enemyTimerStopped - enemyTimerStarted;
  enemyTimeoutId = setTimeout(() => {
    makeEnemy(); 
    spawnEnemies(); 
  }, enemyConfig.timeBetweenEnemies - enemyElapsed);

  // Resume last boss interval
  let bossElapsed = bossTimerStopped - bossTimerStarted;
  bossTimeoutId = setTimeout(() => {
    makeBossEnemy(); 
    spawnBossEnemy(); 
  }, bossRandomDelay - bossElapsed);
}

// Function to toggle pause
function togglePause() {
  lst = Date.now();
  isPaused = !isPaused;
  if (isPaused) {
    stopSpawning(); 
  } 
  else {
    startSpawning(); 
    window.requestAnimationFrame(draw); 
  }
}

let pauseClickListener;
function handlePauseClick (w, h, x, y) {
  if (mouseX > x - w/2 && mouseX < x + w/2 && mouseY > y - h/2 && mouseY < y + h/2) {
    togglePause();
  }
}

let tabChangeListener;
function handleTabChange () {
  if (document.visibilityState == "hidden" && !isPaused) {
    togglePause();
  }
}

// Character
var character = makeSquare(canvas.width/2, canvas.height/2, charSize, 0);

// Array of all bullets
var bullets = [];

// Array of all enemies
var enemies = [];

// Keep track of the score
var score = 0;

// Show the menu
function menu() {
  erase();
  const zeroX = canvas.width / 2;
  const zeroY = canvas.height / 2;
  
  /* Game title */
  context.fillStyle = theme.menuTextColor;
  context.strokeStyle = theme.buttonBorderColor;
  context.font = (80 * sizingConstant) + 'px Courier New';
  context.textAlign = 'center';
  context.fillText('Clauster', zeroX, zeroY - 200*sizingConstant);
  
  /* Buttons */
  context.font = (25 * sizingConstant) + 'px Arial';
  
  // Start
  var startX = zeroX;
  var startY = canvas.height / 2.3;
  context.fillText('Start', startX, startY);
  context.strokeRect(startX - buttonWidth / 2, startY - buttonHeight / 2, buttonWidth, buttonHeight);

  // How to play
  var instructionsX = zeroX;
  var instructionsY = startY + 100*sizingConstant;
  context.fillText('How to Play', instructionsX, instructionsY);
  context.strokeRect(instructionsX - buttonWidth / 2, instructionsY - buttonHeight / 2, buttonWidth, buttonHeight);

  // Themes
  var themesX = zeroX;
  var themesY = instructionsY + 100*sizingConstant;
  context.fillText('Themes', themesX, themesY);
  context.strokeRect(themesX - buttonWidth / 2, themesY - buttonHeight / 2, buttonWidth, buttonHeight);

  /* Handle click */

  // Remove other page click listeners
  canvas.removeEventListener('click', instructionsClickListener);
  canvas.removeEventListener('click', themesClickListener);
  canvas.removeEventListener('click', pauseClickListener);
  document.removeEventListener('visibilitychange', tabChangeListener);

  // Add menu click listener
  menuClickListener = () => {
    handleMenuClick(startX, startY, instructionsX, instructionsY, themesX, themesY);
  };
  canvas.addEventListener('click', menuClickListener);
}

// Show instructions page
function instructionPage() {
  erase();
  context.fillStyle = theme.menuTextColor;

  /* Text */

  // Instructions
  context.font = (60 * sizingConstant) + 'px Courier New';
  context.textAlign = 'center';
  context.fillText('Instructions', canvas.width / 2, canvas.height / 5);
  // You are claustrophobic!
  context.font = (36 * sizingConstant) + 'px Arial';
  context.fillText('You are claustrophobic!', canvas.width / 2, canvas.height / 2.7);
  // Instructions
  context.font = (30 * sizingConstant) + 'px Arial';
  context.fillText('Do not let 5 enemies touch your comfort zone circle!', canvas.width / 2, canvas.height / 2);
  context.fillText('PC: use your MOUSE to aim and press \'W\' to shoot', canvas.width / 2, canvas.height / 1.7);
  context.fillText('Mobile: tap where you want to shoot', canvas.width / 2, canvas.height / 1.5);
  // Click anywhere for menu
  context.font = (36 * sizingConstant) + 'px Arial';
  context.fillText('Click anywhere to go back to the menu', canvas.width / 2, canvas.height / 1.2);

  /* Handle click */

  // Remove other page click listeners
  canvas.removeEventListener('click', menuClickListener);
  canvas.removeEventListener('click', themesClickListener);
  canvas.removeEventListener('click', pauseClickListener);
  document.removeEventListener('visibilitychange', tabChangeListener);

  // Add instructions click listener
  instructionsClickListener = () => {
    handleInstructionsClick();
  };
  canvas.addEventListener('click', instructionsClickListener);
}

// Show theme select page
function themePage() {
  erase();
  const zeroX = canvas.width / 2;
  const zeroY = canvas.height / 2;

  /* Themes page title */
  context.fillStyle = theme.menuTextColor;
  context.font = (80 * sizingConstant) + 'px Courier New';
  context.textAlign = 'center';
  context.fillText('Themes', zeroX, zeroY - 300*sizingConstant);

  /* Select themes */
  context.font = (25 * sizingConstant) + 'px Arial';
  context.fillText('Select a theme below.', zeroX, zeroY - 175*sizingConstant);

  /* Theme buttons */
  context.font = (25 * sizingConstant) + 'px Arial';
  context.lineWidth = 10;
  var themeWidth = 200*sizingConstant;
  var themeHeight = 100*sizingConstant;
  
  var themeButtons = [];
  var themeNum = 0;
  let posY = 0;
  for (const [key, currTheme] of Object.entries(themes)) {
    // Three themes per row
    let row = parseInt(themeNum / 3);
    let col = themeNum % 3;

    // Theme button
    let posX = zeroX - (1 - col) * (themeWidth + 50 * sizingConstant);
    posY = zeroY - 50*sizingConstant + row * themeHeight;
    
    context.strokeStyle = currTheme.game.zoneColor;
    context.strokeRect(posX - themeWidth / 2, posY - themeHeight / 2, themeWidth, themeHeight);
    context.fillStyle = currTheme.backgroundColor;
    context.fillRect(posX - themeWidth / 2, posY - themeHeight / 2, themeWidth, themeHeight);
    context.fillStyle = currTheme.game.gameTextColor;
    context.fillText(currTheme.name, posX, posY);

    themeButtons.push([posX, posY, currTheme])
    themeNum++;
  } 

  /* Return to menu */
  var returnX = zeroX;
  var returnY = posY + 175*sizingConstant;

  context.fillStyle = theme.menuTextColor;
  context.strokeStyle = theme.buttonBorderColor;
  context.lineWidth = 1;
  context.fillText("Return to Menu", returnX, returnY);
  context.strokeRect(returnX - buttonWidth / 2, returnY - buttonHeight / 2, buttonWidth, buttonHeight);

  /* Handle clicks */
  // Remove other page click listeners
  canvas.removeEventListener('click', menuClickListener);
  canvas.removeEventListener('click', instructionsClickListener);
  canvas.removeEventListener('click', pauseClickListener);
  document.removeEventListener('visibilitychange', tabChangeListener);

  // Add menu click listener
  themesClickListener = () => {
    handleThemesClick(themeButtons, themeWidth, themeHeight, returnX, returnY);
  };
  canvas.addEventListener('click', themesClickListener);

  // Set reload page code back to menu
  sessionStorage.setItem("pageCode", 0);
}

var gameStarted = false;

// Start the game
function startGame() {
  // Prevent multiple start game threads 
  if (gameStarted) {
    return;
  }
  gameStarted = true;
  
  // Kick off enemy spawning
   spawnEnemies();

  // Kick off boss enemy spawning
  spawnBossEnemy();

  // Remove other page click listeners
  canvas.removeEventListener('click', menuClickListener);
  canvas.removeEventListener('click', instructionsClickListener);
  canvas.removeEventListener('click', themesClickListener);

  // Add pause click listener
  pauseClickListener = () => {
    handlePauseClick(pauseWidth, pauseHeight, pauseX, pauseY);
  };
  canvas.addEventListener('click', pauseClickListener);

  // Pause the game when the tab visibility changes
  tabChangeListener = () => {
    handleTabChange();
  }
  document.addEventListener('visibilitychange', tabChangeListener);

  // Set reload page code back to menu
  sessionStorage.setItem("pageCode", 0);

  draw();
}



// Utility function to get a random integer between min and max (inclusive)
function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

// Show the end game screen
function endGame() {
  document.removeEventListener('visibilitychange', tabChangeListener);

  erase();
  var saveStatus;
  var saveColor;
  if (score <= 0) {
    saveStatus = 'Note: Scores of 0 are not saved';
    saveColor = '#FF6200';
  }
  else {
    saveStatus = (user <= 0) ? 'Note: Score will not be saved (not logged in)' : 'Your score has successfully been saved!';
    saveColor = (user <= 0) ? '#FF0000' : '#00FF00';
  }
  context.fillStyle = theme.endGameTextColor;
  context.font = (50 * sizingConstant) + 'px Arial';
  context.textAlign = 'center';
  context.fillText('Game Over!', canvas.width/2, canvas.height/3);
  context.font = (36 * sizingConstant) + 'px Arial';
  context.fillText('Score: ' + score, canvas.width/2, canvas.height/2.2);
  context.fillStyle = saveColor;
  context.fillText(saveStatus, canvas.width/2, canvas.height/1.75);
  context.fillStyle = theme.endGameTextColor;
  context.fillText('CLICK TO RETURN TO MENU', canvas.width / 2, canvas.height / 1.5);
  
  //Add score to table
  var finalScore = score;

  let data = {
    score: finalScore
  }

  let http = new XMLHttpRequest();
  http.onreadystatechange = () => {
    if (http.readyState == 4) {
      if (http.status === 200) {
        let data = JSON.parse(http.responseText);
        console.log("received data", data);
        console.log("Saved score");
      }
      canvas.addEventListener('click', () => {
        sessionStorage.setItem("pageCode", 0);
        window.location.reload();
      });
    }
  }
  http.open("POST", "api/save_score.php", true);
  http.setRequestHeader('Content-Type', 'application/json');
  http.send(JSON.stringify({"data": data}));
}

function setTheme (themeNum) {
  // Set theme for user
  let data = {
    themeNum: themeNum
  }
  let http = new XMLHttpRequest();
  http.onreadystatechange = () => {
    if (http.readyState == 4) {
      if (http.status === 200) {
        let data = JSON.parse(http.responseText);
        console.log("received data", data);
        console.log("Set theme");
      }
    }
  }
  http.open("POST", "api/set_theme.php", true);
  http.setRequestHeader('Content-Type', 'application/json');
  http.send(JSON.stringify({"data": data}));
  
  // Set theme in localstorage
  sessionStorage.setItem("Theme", themeNum);

  // Reload to apply the theme
  sessionStorage.setItem("pageCode", 1);
  window.location.reload();
}

function getTheme () {
  // If logged in, get user theme
  if (user > 0) {
    let userTheme = <?php echo json_encode($user_theme); ?>;
    sessionStorage.setItem("Theme", userTheme);
    return userTheme;  
  }
  // If not logged in, get theme from sessionStorage
  if (!sessionStorage.getItem("Theme")) {
    sessionStorage.setItem("Theme", 0);
  }
  return sessionStorage.getItem("Theme");
}

// Taken spots
var takenSpots = 0;

// User can only shoot one bullet at a time
var isShotOnce = false;

// Track if game is over
var gameOver = false;



// Main draw loop
function draw() {
  // Pause overlay
  if (isPaused) {
    context.fillStyle = 'rgba(0, 0, 0, 0.5)';
    context.fillRect(0, 0, canvas.width, canvas.height);
    context.fillStyle = 'white';
    context.font = (32 * sizingConstant) + 'px Arial';
    context.textAlign = 'center';
    context.fillText('Click Pause Again to Resume', canvas.width / 2, canvas.height / 2);
    return;
  }

  erase();

  // Variable fps
  let delta = (lst === undefined) ? 1/1000.0 : (Date.now() - lst) / 1000.0;
  lst = Date.now();

  // Draw pause button
  context.fillStyle = theme.game.gameTextColor;
  context.fillRect(pauseX - pauseWidth/2, pauseY - pauseHeight/2, pauseWidth/3, pauseHeight);
  context.fillRect(pauseX - pauseWidth/2 + (2/3) * pauseWidth, pauseY - pauseHeight/2, pauseWidth/3, pauseHeight);

  //Draw aim line
  context.strokeStyle = theme.game.aimColor;
  context.beginPath();
  context.setLineDash([5 * sizingConstant, 15 * sizingConstant]);
  context.moveTo(canvas.width/2, canvas.height/2);
  context.lineTo(mouseX, mouseY);
  context.stroke();
  
  // Draw the score
  context.fillStyle = theme.game.gameTextColor;
  context.font = (24 * sizingConstant) + 'px Arial';
  context.textAlign = 'center';
  context.fillText('Score: ' + score, canvas.width/2, canvas.height/15);
  context.fillText('Enemies in Comfort Zone: ' + takenSpots, canvas.width/2, canvas.height/8 );

  // Comfort zone circle
  context.setLineDash([]);
  makeCircle(canvas.width/2, canvas.height/2, clusterRadius, theme.game.zoneColor);
  
  // Move and draw the enemies
  enemies.forEach(function(enemy) {
    enemy.x -= enemy.s[0] * delta;
    enemy.y -= enemy.s[1] * delta;
    if (!enemy.c && Math.sqrt(Math.pow(enemy.x - canvas.width/2,2) + Math.pow(enemy.y - canvas.height/2,2)) <= clusterRadius) {
      enemy.s[0] = 0;
      enemy.s[1] = 0;
      takenSpots++;
      enemy.c = true;
    }
    context.fillStyle = theme.game.enemyColor;
    enemy.draw();
  });

  //Draw bullets
  bullets.forEach(function(bullet) {
    bullet.x += bullet.s[0] * delta;
    bullet.y += bullet.s[1] * delta;
    context.fillStyle = theme.game.bulletColor;
    bullet.draw();
  });

  //Make enemy lose 1 health if collide with bullet
  for (let i = 0; i < enemies.length; i++) {
    var enemy = enemies[i];
    for (let j = 0; j < bullets.length; j++) {
      var bullet = bullets[j];
      if (!enemies[i].c && isColliding(enemy, bullet)) {
        enemies[i].h -= 1;
        if (enemies[i].h <= 0) {
          enemies.splice(i, 1);
        }
        bullets.splice(j, 1);
        score++;
      }
    }
  }

  // Draw the character
  context.fillStyle = theme.game.charColor;
  character.draw();

  //Prevents bullet from being shot multiple times at once
  if (keys.w) {
    if (!isShotOnce) {
      makeBullet(mouseX, mouseY);
      isShotOnce = true;
    }
  }
  else {
    isShotOnce = false;
  }

  // Deletes bullets that are outside game
  for (let k = 0; k < bullets.length; k++) {
      var bullet = bullets[k];
      if (bullet.x > canvas.width || bullet.x < 0 || bullet.y < 0 || bullet.y > canvas.height) {
        bullets.splice(k, 1);
      }
  }

  // Adjust boss health and speed as game progresses
  bossHealth = score >= 50 ? 3 + (score - 40) / 10 : 3;
  bossSpeed = score >= 50 ? Math.max(100 * sizingConstant, (200 * sizingConstant) - ((score - 50) / 100) * sizingConstant) : (200 * sizingConstant);

  // End the game or keep going
  if (takenSpots >= 5) {
    endGame();
  }
  else {
  	window.requestAnimationFrame(draw);
  }
}

// Show the menu to start the game
if (sessionStorage.getItem("pageCode")) {
  if (sessionStorage.getItem("pageCode") == 0) {
    menu();
  }
  else if (sessionStorage.getItem("pageCode") == 1) {
    themePage();
  }
}
else {
  menu();
}


//canvas.focus();
</script>

<?php
require_once(__DIR__ . "/../../partials/flash.php");
?>

<style>
    div h1 {
        text-align: center;
    }
    body {
        overflow: hidden;
        touch-action: manipulation;
    }
    canvas {
        display: block;
        border: 3px solid black;
        margin: auto;
    }

</style>