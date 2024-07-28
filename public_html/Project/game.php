<?php
require(__DIR__ . "/../../partials/nav.php");
?>
<div class="container-fluid">
    <h1 class="game-title">:</h1>
    <canvas id="canvas" tabindex="1"></canvas>
</div>

<?php
  $user_id = get_user_id();
?>

<script>
  
//Clauster
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

/* ***Physical attributes of game members*** */
var charSize = 40 * sizingConstant;
var enemySpeed = 3 * sizingConstant;
var bulletSpeed = 10 * sizingConstant;
var bulletSize = 15 * sizingConstant;
var bulletColor = '#000000';
var clusterRadius = 80 * sizingConstant;
var zoneColor = '#FF8800';
var enemyColor = '#FF9191';
var charColor = '#5E9CFF';
var aimColor = '#FF0000';

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
function makeSquare(x, y, length, speed, inCircle) {
  return {
    x: x,
    y: y,
    l: length,
    s: speed,
    c: false,
    draw: function() {
      context.fillRect(this.x - length/2, this.y - length/2, this.l, this.l);
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
  enemies.push(makeSquare(enemyX, enemyY, charSize, normalize([enemyX - canvas.width/2, enemyY - canvas.height/2], enemySpeed)));
}

// Clear the canvas
function erase() {
  context.fillStyle = '#FFFFFF';
  context.fillRect(0, 0, canvas.width, canvas.height);
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
  // Show the menu
  context.fillStyle = '#000000';
  context.font = (60 * sizingConstant) + 'px Courier New';
  context.textAlign = 'center';
  context.fillText('Clauster', canvas.width / 2, canvas.height / 5);
  context.font = (36 * sizingConstant) + 'px Arial';
  context.fillText('You are claustrophobic!', canvas.width / 2, canvas.height / 2.7);
  context.font = (30 * sizingConstant) + 'px Arial';
  context.fillText('Do not let 5 enemies touch your comfort zone circle!', canvas.width / 2, canvas.height / 2);
  context.fillText('PC: use your MOUSE to aim and press \'W\' to shoot', canvas.width / 2, canvas.height / 1.7);
  context.fillText('Mobile: tap where you want to shoot', canvas.width / 2, canvas.height / 1.5);
  context.font = (36 * sizingConstant) + 'px Arial';
  context.fillText('CLICK TO START', canvas.width / 2, canvas.height / 1.2);
  // Start the game on a click
  canvas.addEventListener('click', startGame);
}

// The delay between enemies (in milliseconds)
var timeBetweenEnemies = 1000;

// ID to track the spawn timeout
var timeoutId = null;

// Start the game
function startGame() {   
  // Kick off the enemy spawn interval
  timeoutId = setInterval(makeEnemy, timeBetweenEnemies);

  // Make the first enemy
  setTimeout(makeEnemy, 1000);

  // Don't accept any more clicks
  canvas.removeEventListener('click', startGame);
  draw();
}

// Show the end game screen
function endGame() {
  erase();
  user = <?php echo json_encode($user_id); ?>;
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
  context.fillStyle = '#000000';
  context.font = (50 * sizingConstant) + 'px Arial';
  context.textAlign = 'center';
  context.fillText('Game Over!', canvas.width/2, canvas.height/3);
  context.font = (36 * sizingConstant) + 'px Arial';
  context.fillText('Score: ' + score, canvas.width/2, canvas.height/2.2);
  context.fillStyle = saveColor;
  context.fillText(saveStatus, canvas.width/2, canvas.height/1.75);
  context.fillStyle = '#000000';
  context.fillText('CLICK TO PLAY AGAIN', canvas.width / 2, canvas.height / 1.5);
  
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
        window.location.reload();
      });
    }
  }
  http.open("POST", "api/save_score.php", true);
  http.setRequestHeader('Content-Type', 'application/json');
  http.send(JSON.stringify({"data": data}));
}

// Taken spots
var takenSpots = 0;

// User can only shoot one bullet at a time
var isShotOnce = false;

// Main draw loop
function draw() {
  erase();

  var gameOver = false;
  if (takenSpots == 5) {
    gameOver = true;
  }

  //Draw aim line
  context.strokeStyle = aimColor;
  context.beginPath();
  context.setLineDash([5 * sizingConstant, 15 * sizingConstant]);
  context.moveTo(canvas.width/2, canvas.height/2);
  context.lineTo(mouseX, mouseY);
  context.stroke();
  
  // Draw the score
  context.fillStyle = '#000000';
  context.font = (24 * sizingConstant) + 'px Arial';
  context.textAlign = 'center';
  context.fillText('Score: ' + score, canvas.width/2, canvas.height/15);
  context.fillText('Enemies in Comfort Zone: ' + takenSpots, canvas.width/2, canvas.height/8 );

  // Comfort zone circle
  context.setLineDash([]);
  makeCircle(canvas.width/2, canvas.height/2, clusterRadius, zoneColor);
  
  // Move and draw the enemies
  enemies.forEach(function(enemy) {
    enemy.x -= enemy.s[0];
    enemy.y -= enemy.s[1];
    if (!enemy.c && Math.sqrt(Math.pow(enemy.x - canvas.width/2,2) + Math.pow(enemy.y - canvas.height/2,2)) <= clusterRadius) {
      enemy.s[0] = 0;
      enemy.s[1] = 0;
      takenSpots++;
      enemy.c = true;
    }
    context.fillStyle = enemyColor;
    enemy.draw();
  });

  //Draw bullets
  bullets.forEach(function(bullet) {
    bullet.x += bullet.s[0];
    bullet.y += bullet.s[1];
    context.fillStyle = bulletColor;
    bullet.draw();
  });

  //Make bullet and enemy disappear when they collide
  for (let i = 0; i < enemies.length; i++) {
    var enemy = enemies[i];
    for (let j = 0; j < bullets.length; j++) {
      var bullet = bullets[j];
      if (!enemies[i].c && isColliding(enemy, bullet)) {
        enemies.splice(i, 1);
        bullets.splice(j, 1);
        score++;
      }
    }
  }

  // Draw the character
  context.fillStyle = charColor;
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
  
  // End the game or keep going
  if (gameOver) {
  	endGame();
  } 
  else {
  	window.requestAnimationFrame(draw);
  }
}

// Show the menu to start the game
menu();
canvas.focus();
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