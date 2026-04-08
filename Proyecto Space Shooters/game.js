const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');

function setCanvasSize() {
    const maxWidth = Math.min(window.innerWidth - 40, 480);
    const maxHeight = Math.min(window.innerHeight - 180, 640);
    const aspectRatio = 480 / 640;
    
    let width = maxWidth;
    let height = width / aspectRatio;
    
    if (height > maxHeight) {
        height = maxHeight;
        width = height * aspectRatio;
    }
    
    canvas.style.width = width + 'px';
    canvas.style.height = height + 'px';
    canvas.width = 480;
    canvas.height = 640;
}

setCanvasSize();
window.addEventListener('resize', () => {
    setCanvasSize();
});

let gameState = 'start';
let score = 0;
let highScore = localStorage.getItem('spaceShooterHighScore') || 0;
let lives = 3;
let wave = 1;
let animationId;
let lastShot = 0;
const SHOT_COOLDOWN = 200;

const player = {
    x: canvas.width / 2,
    y: canvas.height - 60,
    width: 40,
    height: 40,
    speed: 6,
    dx: 0
};

let bullets = [];
let enemies = [];
let enemyBullets = [];
let particles = [];
let stars = [];

const keys = {
    ArrowLeft: false,
    ArrowRight: false,
    Space: false,
    KeyA: false,
    KeyD: false
};

function initStars() {
    stars = [];
    for (let i = 0; i < 100; i++) {
        stars.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            size: Math.random() * 2 + 0.5,
            speed: Math.random() * 2 + 0.5,
            opacity: Math.random() * 0.5 + 0.5
        });
    }
}

initStars();

document.addEventListener('keydown', (e) => {
    if (e.code === 'ArrowLeft' || e.code === 'KeyA') keys.ArrowLeft = true;
    if (e.code === 'ArrowRight' || e.code === 'KeyD') keys.ArrowRight = true;
    if (e.code === 'Space') {
        e.preventDefault();
        keys.Space = true;
    }
    if (e.code === 'KeyP' && gameState === 'playing') {
        pauseGame();
    }
});

document.addEventListener('keyup', (e) => {
    if (e.code === 'ArrowLeft' || e.code === 'KeyA') keys.ArrowLeft = false;
    if (e.code === 'ArrowRight' || e.code === 'KeyD') keys.ArrowRight = false;
    if (e.code === 'Space') keys.Space = false;
});

document.getElementById('startBtn').addEventListener('click', startGame);
document.getElementById('retryBtn').addEventListener('click', startGame);
document.getElementById('resumeBtn').addEventListener('click', resumeGame);

function startGame() {
    gameState = 'playing';
    score = 0;
    lives = 3;
    wave = 1;
    bullets = [];
    enemyBullets = [];
    enemies = [];
    particles = [];
    player.x = canvas.width / 2;
    player.dx = 0;
    
    document.getElementById('startScreen').style.display = 'none';
    document.getElementById('gameOverScreen').style.display = 'none';
    document.getElementById('pauseScreen').style.display = 'none';
    
    updateHUD();
    spawnWave();
    gameLoop();
}

function pauseGame() {
    gameState = 'paused';
    document.getElementById('pauseScreen').style.display = 'flex';
}

function resumeGame() {
    gameState = 'playing';
    document.getElementById('pauseScreen').style.display = 'none';
    gameLoop();
}

function gameOver() {
    gameState = 'gameover';
    cancelAnimationFrame(animationId);
    
    const newRecordEl = document.getElementById('newHighScore');
    if (score > highScore) {
        highScore = score;
        localStorage.setItem('spaceShooterHighScore', highScore);
        newRecordEl.classList.add('show');
    } else {
        newRecordEl.classList.remove('show');
    }
    
    document.getElementById('finalScore').textContent = score;
    document.getElementById('gameOverScreen').style.display = 'flex';
}

function spawnWave() {
    const rows = Math.min(3 + Math.floor(wave / 2), 6);
    const cols = Math.min(5 + Math.floor(wave / 3), 8);
    const enemyWidth = 35;
    const enemyHeight = 30;
    const spacingX = 50;
    const spacingY = 45;
    const startX = (canvas.width - (cols - 1) * spacingX) / 2;
    const startY = 50;

    for (let row = 0; row < rows; row++) {
        for (let col = 0; col < cols; col++) {
            let type = 'basic';
            let points = 100;
            let color = '#ff6b6b';
            let health = 1;
            
            if (row === 0) {
                type = 'elite';
                points = 300;
                color = '#ffd93d';
                health = 2;
            } else if (row === 1 && wave > 2) {
                type = 'medium';
                points = 200;
                color = '#6bcb77';
                health = 1;
            }

            enemies.push({
                x: startX + col * spacingX,
                y: startY + row * spacingY,
                width: enemyWidth,
                height: enemyHeight,
                type: type,
                points: points,
                color: color,
                health: health,
                maxHealth: health,
                dx: 1.5 + wave * 0.2,
                dy: 0,
                shootTimer: Math.random() * 2000,
                animFrame: 0
            });
        }
    }
}

function spawnParticles(x, y, color, count) {
    for (let i = 0; i < count; i++) {
        particles.push({
            x: x,
            y: y,
            vx: (Math.random() - 0.5) * 8,
            vy: (Math.random() - 0.5) * 8,
            size: Math.random() * 4 + 2,
            color: color,
            life: 1
        });
    }
}

function drawPlayer() {
    ctx.save();
    ctx.translate(player.x, player.y);
    
    ctx.fillStyle = '#00d4ff';
    ctx.shadowBlur = 15;
    ctx.shadowColor = '#00ffff';
    ctx.beginPath();
    ctx.moveTo(0, -20);
    ctx.lineTo(-15, 15);
    ctx.lineTo(0, 8);
    ctx.lineTo(15, 15);
    ctx.closePath();
    ctx.fill();
    
    ctx.fillStyle = '#0096ff';
    ctx.beginPath();
    ctx.moveTo(0, -15);
    ctx.lineTo(-10, 10);
    ctx.lineTo(0, 5);
    ctx.lineTo(10, 10);
    ctx.closePath();
    ctx.fill();
    
    ctx.shadowBlur = 10;
    ctx.shadowColor = '#4ade80';
    ctx.fillStyle = '#4ade80';
    ctx.beginPath();
    ctx.moveTo(-20, 12);
    ctx.lineTo(-10, 5);
    ctx.lineTo(-5, 12);
    ctx.closePath();
    ctx.fill();
    
    ctx.beginPath();
    ctx.moveTo(20, 12);
    ctx.lineTo(10, 5);
    ctx.lineTo(5, 12);
    ctx.closePath();
    ctx.fill();
    
    ctx.shadowBlur = 0;
    ctx.restore();
}

function drawEnemy(enemy) {
    ctx.save();
    ctx.translate(enemy.x, enemy.y);
    
    const wobble = Math.sin(Date.now() / 200 + enemy.x) * 2;
    ctx.translate(wobble, 0);
    
    ctx.fillStyle = enemy.color;
    ctx.shadowBlur = 10;
    ctx.shadowColor = enemy.color;
    ctx.beginPath();
    ctx.moveTo(0, -12);
    ctx.lineTo(-15, 5);
    ctx.lineTo(-10, 12);
    ctx.lineTo(10, 12);
    ctx.lineTo(15, 5);
    ctx.closePath();
    ctx.fill();
    
    ctx.shadowBlur = 0;
    ctx.fillStyle = '#000';
    ctx.beginPath();
    ctx.arc(-5, 0, 3, 0, Math.PI * 2);
    ctx.arc(5, 0, 3, 0, Math.PI * 2);
    ctx.fill();
    
    if (enemy.type === 'elite') {
        ctx.strokeStyle = '#fff';
        ctx.lineWidth = 2;
        ctx.shadowBlur = 5;
        ctx.shadowColor = '#fff';
        ctx.beginPath();
        ctx.arc(0, -5, 8, 0, Math.PI * 2);
        ctx.stroke();
        ctx.shadowBlur = 0;
    }
    
    if (enemy.health < enemy.maxHealth) {
        const barWidth = 30;
        const healthPercent = enemy.health / enemy.maxHealth;
        ctx.fillStyle = '#333';
        ctx.fillRect(-barWidth/2, -22, barWidth, 4);
        ctx.fillStyle = '#4ade80';
        ctx.fillRect(-barWidth/2, -22, barWidth * healthPercent, 4);
    }
    
    ctx.restore();
}

function drawBullet(bullet) {
    ctx.fillStyle = '#00ffff';
    ctx.shadowBlur = 15;
    ctx.shadowColor = '#00ffff';
    ctx.fillRect(bullet.x - 2, bullet.y - 10, 4, 20);
    
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(bullet.x - 1, bullet.y - 8, 2, 16);
    ctx.shadowBlur = 0;
}

function drawEnemyBullet(bullet) {
    ctx.fillStyle = '#ff4444';
    ctx.shadowBlur = 15;
    ctx.shadowColor = '#ff4444';
    ctx.beginPath();
    ctx.arc(bullet.x, bullet.y, 5, 0, Math.PI * 2);
    ctx.fill();
    ctx.shadowBlur = 0;
}

function drawParticle(p) {
    ctx.globalAlpha = p.life;
    ctx.fillStyle = p.color;
    ctx.shadowBlur = 5;
    ctx.shadowColor = p.color;
    ctx.beginPath();
    ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
    ctx.fill();
    ctx.shadowBlur = 0;
    ctx.globalAlpha = 1;
}

function drawStars() {
    stars.forEach(star => {
        ctx.globalAlpha = star.opacity;
        ctx.fillStyle = '#fff';
        ctx.beginPath();
        ctx.arc(star.x, star.y, star.size, 0, Math.PI * 2);
        ctx.fill();
    });
    ctx.globalAlpha = 1;
}

function updateStars() {
    stars.forEach(star => {
        star.y += star.speed;
        if (star.y > canvas.height) {
            star.y = 0;
            star.x = Math.random() * canvas.width;
        }
    });
}

function updatePlayer() {
    if (keys.ArrowLeft) player.dx = -player.speed;
    else if (keys.ArrowRight) player.dx = player.speed;
    else player.dx = 0;
    
    player.x += player.dx;
    player.x = Math.max(player.width/2, Math.min(canvas.width - player.width/2, player.x));
    
    if (keys.Space && Date.now() - lastShot > SHOT_COOLDOWN) {
        bullets.push({ x: player.x, y: player.y - 25 });
        lastShot = Date.now();
    }
}

function updateBullets() {
    bullets = bullets.filter(b => {
        b.y -= 10;
        return b.y > -20;
    });
    
    enemyBullets = enemyBullets.filter(b => {
        b.y += 6;
        return b.y < canvas.height + 20;
    });
}

function updateEnemies() {
    let hitEdge = false;
    
    enemies.forEach(enemy => {
        enemy.x += enemy.dx;
        enemy.animFrame++;
        
        if (enemy.x <= 30 || enemy.x >= canvas.width - 30) {
            hitEdge = true;
        }
        
        enemy.shootTimer -= 16;
        if (enemy.shootTimer <= 0 && Math.random() < 0.02 + wave * 0.005) {
            enemyBullets.push({ x: enemy.x, y: enemy.y + 15 });
            enemy.shootTimer = 1500 + Math.random() * 2000;
        }
    });
    
    if (hitEdge) {
        enemies.forEach(enemy => {
            enemy.dx *= -1;
            enemy.y += 20;
        });
    }
}

function updateParticles() {
    particles = particles.filter(p => {
        p.x += p.vx;
        p.y += p.vy;
        p.life -= 0.03;
        p.vy += 0.1;
        return p.life > 0;
    });
}

function checkCollisions() {
    bullets.forEach((bullet, bi) => {
        enemies.forEach((enemy, ei) => {
            if (bullet.x > enemy.x - enemy.width/2 &&
                bullet.x < enemy.x + enemy.width/2 &&
                bullet.y > enemy.y - enemy.height/2 &&
                bullet.y < enemy.y + enemy.height/2) {
                
                enemy.health--;
                bullets.splice(bi, 1);
                
                if (enemy.health <= 0) {
                    score += enemy.points;
                    spawnParticles(enemy.x, enemy.y, enemy.color, 15);
                    enemies.splice(ei, 1);
                    updateHUD();
                } else {
                    spawnParticles(bullet.x, bullet.y, '#fff', 5);
                }
            }
        });
    });
    
    enemyBullets.forEach((bullet, bi) => {
        if (bullet.x > player.x - player.width/2 &&
            bullet.x < player.x + player.width/2 &&
            bullet.y > player.y - player.height/2 &&
            bullet.y < player.y + player.height/2) {
            
            enemyBullets.splice(bi, 1);
            lives--;
            spawnParticles(player.x, player.y, '#00d4ff', 20);
            updateHUD();
            
            if (lives <= 0) {
                gameOver();
            }
        }
    });
    
    enemies.forEach(enemy => {
        if (enemy.x - enemy.width/2 < player.x + player.width/2 &&
            enemy.x + enemy.width/2 > player.x - player.width/2 &&
            enemy.y - enemy.height/2 < player.y + player.height/2 &&
            enemy.y + enemy.height/2 > player.y - player.height/2) {
            gameOver();
        }
    });
}

function checkWaveComplete() {
    if (enemies.length === 0) {
        wave++;
        updateHUD();
        spawnWave();
    }
}

function updateHUD() {
    document.getElementById('scoreDisplay').textContent = score;
    document.getElementById('highScoreDisplay').textContent = highScore;
    document.getElementById('livesDisplay').textContent = lives;
    document.getElementById('waveDisplay').textContent = wave;
}

function draw() {
    ctx.fillStyle = '#0a0a20';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    const gradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
    gradient.addColorStop(0, '#0a0a20');
    gradient.addColorStop(1, '#1a0a30');
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    drawStars();
    
    bullets.forEach(drawBullet);
    enemyBullets.forEach(drawEnemyBullet);
    enemies.forEach(drawEnemy);
    particles.forEach(drawParticle);
    
    if (lives > 0) {
        drawPlayer();
    }
    
    ctx.fillStyle = 'rgba(0, 212, 255, 0.1)';
    ctx.fillRect(0, canvas.height - 80, canvas.width, 80);
}

function gameLoop() {
    if (gameState !== 'playing') return;
    
    updatePlayer();
    updateBullets();
    updateEnemies();
    updateParticles();
    updateStars();
    checkCollisions();
    checkWaveComplete();
    draw();
    
    animationId = requestAnimationFrame(gameLoop);
}

document.getElementById('highScoreDisplay').textContent = highScore;
