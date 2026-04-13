<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes - Roll-ON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        :root { --primary: #e94560; --dark: #16213e; --success: #28a745; --warning: #ffc107; --danger: #dc3545; }
        body { background: #f5f6fa; min-height: 100vh; }
        
        .header { background: var(--dark); color: #fff; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { color: var(--primary); }
        .header-right { display: flex; align-items: center; gap: 1rem; }
        .header a { color: #fff; text-decoration: none; }
        .header a:hover { color: var(--primary); }
        
        .nav-bar {
            background: #fff;
            padding: 1rem 2rem;
            border-bottom: 1px solid #eee;
            display: flex;
            gap: 1rem;
        }
        .nav-bar a {
            color: #666;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.3s;
        }
        .nav-bar a:hover, .nav-bar a.active {
            background: var(--primary);
            color: #fff;
        }
        
        .container { max-width: 1400px; margin: 2rem auto; padding: 0 2rem; }
        
        .page-title {
            color: var(--primary);
            margin-bottom: 1.5rem;
        }
        
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }
        
        .solicitud-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .solicitud-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        
        .solicitud-header h3 {
            color: var(--dark);
        }
        
        .badge-pendiente {
            background: #fff3cd;
            color: #856404;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .solicitud-info {
            margin-bottom: 1rem;
        }
        
        .solicitud-info p {
            color: #666;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .solicitud-info i { color: var(--primary); width: 20px; }
        
        .solicitud-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .btn {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-aprobar {
            background: var(--success);
            color: #fff;
        }
        
        .btn-aprobar:hover {
            background: #218838;
        }
        
        .btn-rechazar {
            background: var(--danger);
            color: #fff;
        }
        
        .btn-rechazar:hover {
            background: #c82333;
        }
        
        .btn-reset {
            background: #ffc107;
            color: #333;
        }
        
        .btn-reset:hover {
            background: #e0a800;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #999;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-cog"></i> ROLL-ON Admin</h1>
        <div class="header-right">
            <span>Bienvenido, <?= htmlspecialchars($_SESSION['nombre'] ?? 'Admin') ?></span>
            <a href="/"><i class="fas fa-external-link-alt"></i> Ver Cotizador</a>
            <a href="/auth/logout">Cerrar Sesión</a>
        </div>
    </div>
    
    <div class="nav-bar">
        <a href="/admin/dashboard"><i class="fas fa-home"></i> Dashboard</a>
        <a href="/admin/solicitudes" class="active"><i class="fas fa-user-clock"></i> Solicitudes</a>
        <a href="/admin/pedidos"><i class="fas fa-file-alt"></i> Pedidos</a>
        <a href="/admin/telas"><i class="fas fa-scroll"></i> Telas</a>
        <a href="/admin/dispositivos"><i class="fas fa-cogs"></i> Dispositivos</a>
        <a href="/admin/extras"><i class="fas fa-plus-circle"></i> Extras</a>
    </div>
    
    <div class="container">
        <h2 class="page-title"><i class="fas fa-user-clock"></i> Solicitudes de Registro</h2>
        
        <?php if (empty($solicitudes)): ?>
        <div class="empty-state">
            <i class="fas fa-check-circle"></i>
            <h3>No hay solicitudes pendientes</h3>
            <p>Los nuevos clientes aparecerán aquí cuando se registren</p>
        </div>
        <?php else: ?>
        <div class="cards-grid">
            <?php foreach ($solicitudes as $sol): ?>
            <div class="solicitud-card" id="solicitud-<?= $sol['id_cliente'] ?>">
                <div class="solicitud-header">
                    <h3><?= htmlspecialchars($sol['nombre'] . ' ' . $sol['apellido']) ?></h3>
                    <span class="badge-pendiente">Pendiente</span>
                </div>
                <div class="solicitud-info">
                    <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($sol['email']) ?></p>
                    <p><i class="fas fa-phone"></i> <?= htmlspecialchars($sol['contacto'] ?? 'No especificado') ?></p>
                    <p><i class="fas fa-calendar"></i> <?= date('d/m/Y H:i', strtotime($sol['fecha_registro'])) ?></p>
                </div>
                <div class="solicitud-actions">
                    <button class="btn btn-aprobar" onclick="aprobarSolicitud(<?= $sol['id_cliente'] ?>)">
                        <i class="fas fa-check"></i> Aprobar
                    </button>
                    <button class="btn btn-rechazar" onclick="rechazarSolicitud(<?= $sol['id_cliente'] ?>)">
                        <i class="fas fa-times"></i> Rechazar
                    </button>
                    <button class="btn btn-reset" onclick="resetearPassword(<?= $sol['id_cliente'] ?>)" title="Resetear password a Cortina2026">
                        <i class="fas fa-key"></i> Reset
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <script>
        function aprobarSolicitud(id) {
            if (!confirm('¿Aprobar esta solicitud? El cliente podrá acceder al sistema.')) return;
            
            const formData = new FormData();
            formData.append('id', id);
            
            fetch('/admin/aprobar-solicitud', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('solicitud-' + id).style.opacity = '0.5';
                    document.getElementById('solicitud-' + id).innerHTML = '<div class="empty-state"><i class="fas fa-check-circle" style="color: #28a745;"></i><h3>Aprobado</h3></div>';
                    setTimeout(() => {
                        document.getElementById('solicitud-' + id).remove();
                        checkEmpty();
                    }, 1500);
                } else {
                    alert(data.error || 'Error al aprobar');
                }
            });
        }

        function rechazarSolicitud(id) {
            if (!confirm('¿Rechazar esta solicitud? El cliente no podrá acceder al sistema.')) return;
            
            const formData = new FormData();
            formData.append('id', id);
            
            fetch('/admin/rechazar-solicitud', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('solicitud-' + id).style.opacity = '0.5';
                    document.getElementById('solicitud-' + id).innerHTML = '<div class="empty-state"><i class="fas fa-times-circle" style="color: #dc3545;"></i><h3>Rechazado</h3></div>';
                    setTimeout(() => {
                        document.getElementById('solicitud-' + id).remove();
                        checkEmpty();
                    }, 1500);
                } else {
                    alert(data.error || 'Error al rechazar');
                }
            });
        }

        function checkEmpty() {
            const cards = document.querySelectorAll('.solicitud-card');
            if (cards.length === 0) {
                document.querySelector('.page-title').insertAdjacentHTML('afterend', 
                    '<div class="empty-state"><i class="fas fa-check-circle"></i><h3>No hay solicitudes pendientes</h3><p>Los nuevos clientes aparecerán aquí cuando se registren</p></div>'
                );
            }
        }

        function resetearPassword(id) {
            if (!confirm('¿Resetear la password de este usuario a Cortina2026?')) return;
            
            const formData = new FormData();
            formData.append('id', id);
            
            fetch('/admin/resetear-password', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert('Password reseteada: ' + data.password + '\n\nCompartila con el cliente por WhatsApp');
                } else {
                    alert(data.error || 'Error al resetear password');
                }
            });
        }
    </script>
</body>
</html>