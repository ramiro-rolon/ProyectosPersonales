<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Roll-ON</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            padding: 2rem 0;
        }
        .register-container {
            background: #fff;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 450px;
            margin: 1rem;
        }
        .logo { 
            text-align: center; 
            margin-bottom: 1.5rem; 
        }
        .logo h1 { 
            font-size: 2.5rem; 
            color: #e94560; 
            font-weight: 800;
        }
        .logo p { color: #666; margin-top: 0.5rem; }
        
        .form-group { margin-bottom: 1.25rem; }
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
        
        .btn-register {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #e94560, #d63850);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 0.5rem;
        }
        .btn-register:hover { 
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(233,69,96,0.3);
        }
        
        .message {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .error-message {
            background: #fee;
            color: #c00;
            border: 1px solid #fcc;
        }
        
        .notice {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #856404;
            font-size: 0.9rem;
            text-align: center;
        }
        
        .notice i {
            margin-right: 0.5rem;
        }
        
        .links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .links a {
            color: #666;
            text-decoration: none;
        }
        .links a:hover { color: #e94560; }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <h1>ROLL-ON</h1>
            <p>Crear Cuenta</p>
        </div>
        
        <?php if (isset($_SESSION['registro_error'])): ?>
            <div class="message error-message">
                <?= htmlspecialchars($_SESSION['registro_error']); ?>
            </div>
            <?php unset($_SESSION['registro_error']); ?>
        <?php endif; ?>

        <div class="notice">
            <i class="fas fa-info-circle"></i>
            <strong>Importante:</strong> Tu cuenta será revisada por la administración antes de ser activada.
        </div>
        
        <form action="/auth/registrar" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required placeholder="Tu nombre">
            </div>
            
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" required placeholder="Tu apellido">
            </div>
            
            <div class="form-group">
                <label for="contacto">Teléfono (opcional)</label>
                <input type="text" id="contacto" name="contacto" placeholder="11 1234 5678">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="tu@email.com">
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required minlength="6" placeholder="Mínimo 6 caracteres">
            </div>
            
            <div class="form-group">
                <label for="password_confirm">Confirmar Contraseña</label>
                <input type="password" id="password_confirm" name="password_confirm" required placeholder="Repetí tu contraseña">
            </div>
            
            <button type="submit" class="btn-register">Registrarse</button>
        </form>
        
        <div class="links">
            <a href="/auth/login">← Ya tengo cuenta</a>
        </div>
    </div>
</body>
</html>