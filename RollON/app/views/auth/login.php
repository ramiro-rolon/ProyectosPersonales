<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Roll-ON</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        }
        .login-container {
            background: #fff;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
            margin: 1rem;
        }
        .logo { 
            text-align: center; 
            margin-bottom: 2rem; 
        }
        .logo h1 { 
            font-size: 2.5rem; 
            color: #e94560; 
            font-weight: 800;
        }
        .logo p { color: #666; margin-top: 0.5rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { 
            display: block; 
            margin-bottom: 0.5rem; 
            color: #333; 
            font-weight: 500;
        }
        .form-group input { 
            width: 100%; 
            padding: 0.875rem 1rem; 
            border: 2px solid #e0e0e0; 
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-group input:focus { 
            outline: none; 
            border-color: #e94560;
        }
        .btn-login {
            width: 100%;
            padding: 1rem;
            background: #e94560;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }
        .btn-login:hover { 
            background: #d63850;
            transform: translateY(-2px);
        }
        .error-message {
            background: #fee;
            color: #c00;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
            border: 1px solid #fcc;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
            text-decoration: none;
        }
        .back-link:hover { color: #e94560; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>ROLL-ON</h1>
            <p>Panel de Administración</p>
        </div>
        
        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="error-message">
                <?= htmlspecialchars($_SESSION['login_error']); ?>
            </div>
            <?php unset($_SESSION['login_error']); ?>
        <?php endif; ?>

        <form action="/auth/login" method="POST">
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username" required autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn-login">Ingresar</button>
        </form>
        
        <a href="/" class="back-link">← Volver al cotizador</a>
    </div>
</body>
</html>
