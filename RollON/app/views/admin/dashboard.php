<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Roll-ON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        :root { --primary: #e94560; --dark: #16213e; --success: #28a745; --warning: #ffc107; --danger: #dc3545; }
        body { background: #f5f6fa; min-height: 100vh; }
        
        .header {
            background: var(--dark);
            color: #fff;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 { color: var(--primary); }
        .header-right { display: flex; align-items: center; gap: 1rem; }
        .header a { color: #fff; text-decoration: none; }
        .header a:hover { color: var(--primary); }
        
        .container { max-width: 1400px; margin: 2rem auto; padding: 0 2rem; }
        
        .welcome { margin-bottom: 2rem; }
        .welcome h2 { color: var(--dark); font-size: 1.8rem; }
        .welcome p { color: #666; margin-top: 0.5rem; }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .stat-card i { font-size: 2.5rem; color: var(--primary); }
        .stat-card .stat-info h3 { font-size: 2rem; color: var(--dark); }
        .stat-card .stat-info p { color: #666; font-size: 0.9rem; }
        
        .nav-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .nav-card {
            background: #fff;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
            text-decoration: none;
            color: inherit;
        }
        
        .nav-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .nav-card i { font-size: 3rem; color: var(--primary); margin-bottom: 1rem; }
        .nav-card h3 { color: var(--dark); margin-bottom: 0.5rem; }
        .nav-card p { color: #666; font-size: 0.9rem; }
        
        .recent-section {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .recent-section h3 {
            color: var(--dark);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary);
        }
        
        .recent-table { width: 100%; border-collapse: collapse; }
        .recent-table th, .recent-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .recent-table th { background: #f8f9fa; color: #666; font-weight: 600; }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .badge-presupuesto { background: #fff3cd; color: #856404; }
        .badge-produccion { background: #cce5ff; color: #004085; }
        .badge-listo { background: #d4edda; color: #155724; }
        .badge-entregado { background: #28a745; color: #fff; }
        .badge-cancelado { background: #dc3545; color: #fff; }
        
        .btn-view { color: var(--primary); text-decoration: none; font-weight: 500; }
        
        .btn-aprobar { background: #28a745; color: #fff; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; margin-right: 0.5rem; }
        .btn-rechazar { background: #dc3545; color: #fff; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; }
        
        .pending-section {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .pending-section h3 {
            color: var(--dark);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--warning);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .pending-section h3 i { color: var(--warning); }
        
        .pending-table { width: 100%; border-collapse: collapse; }
        .pending-table th, .pending-table td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
        .pending-table th { background: #f8f9fa; color: #666; font-weight: 600; }
        
        .badge-pendiente { background: #fff3cd; color: #856404; }
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
    
    <div class="container">
        <div class="welcome">
            <h2>Panel de Control</h2>
            <p>Gestiona tus telas, dispositivos, extras y pedidos</p>
        </div>

        <div class="stats">
            <div class="stat-card">
                <i class="fas fa-file-alt"></i>
                <div class="stat-info">
                    <h3><?= $stats['pedidos'] ?></h3>
                    <p>Pedidos</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-layer-group"></i>
                <div class="stat-info">
                    <h3><?= $stats['telas'] ?></h3>
                    <p>Telas</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-tools"></i>
                <div class="stat-info">
                    <h3><?= $stats['dispositivos'] ?></h3>
                    <p>Dispositivos</p>
                </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-plus-square"></i>
                <div class="stat-info">
                    <h3><?= $stats['extras'] ?></h3>
                    <p>Extras</p>
                </div>
            </div>
            <?php if (isset($stats['solicitudes_pendientes']) && $stats['solicitudes_pendientes'] > 0): ?>
            <div class="stat-card" style="border: 2px solid var(--warning);">
                <i class="fas fa-user-clock" style="color: var(--warning);"></i>
                <div class="stat-info">
                    <h3 style="color: var(--warning);"><?= $stats['solicitudes_pendientes'] ?></h3>
                    <p>Solicitudes Pendientes</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($solicitudesPendientes)): ?>
        <div class="pending-section">
            <h3><i class="fas fa-user-clock"></i> Solicitudes Pendientes</h3>
            <table class="pending-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($solicitudesPendientes as $sol): ?>
                    <tr id="solicitud-<?= $sol['id_cliente'] ?>">
                        <td><?= $sol['id_cliente'] ?></td>
                        <td><?= htmlspecialchars($sol['nombre']) ?></td>
                        <td><?= htmlspecialchars($sol['apellido']) ?></td>
                        <td><?= htmlspecialchars($sol['email']) ?></td>
                        <td><?= htmlspecialchars($sol['contacto'] ?? 'No especificado') ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($sol['fecha_registro'])) ?></td>
                        <td>
                            <button class="btn-aprobar" onclick="aprobarSolicitud(<?= $sol['id_cliente'] ?>)">
                                <i class="fas fa-check"></i> Aprobar
                            </button>
                            <button class="btn-rechazar" onclick="rechazarSolicitud(<?= $sol['id_cliente'] ?>)">
                                <i class="fas fa-times"></i> Rechazar
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <div class="nav-grid">
            <a href="/admin/solicitudes" class="nav-card">
                <i class="fas fa-user-clock"></i>
                <h3>Solicitudes</h3>
                <p>Aprobar o rechazar nuevos clientes</p>
            </a>
            <a href="/admin/pedidos" class="nav-card">
                <i class="fas fa-file-alt"></i>
                <h3>Pedidos</h3>
                <p>Ver y gestionar presupuestos</p>
            </a>
            <a href="/admin/telas" class="nav-card">
                <i class="fas fa-layer-group"></i>
                <h3>Telas</h3>
                <p>Agregar, editar o eliminar telas</p>
            </a>
            <a href="/admin/dispositivos" class="nav-card">
                <i class="fas fa-tools"></i>
                <h3>Dispositivos</h3>
                <p>Gestionar mecanismos y soportes</p>
            </a>
            <a href="/admin/extras" class="nav-card">
                <i class="fas fa-plus-square"></i>
                <h3>Extras</h3>
                <p>Servicios adicionales</p>
            </a>
        </div>

        <div class="recent-section">
            <h3><i class="fas fa-clock"></i> Últimos Pedidos</h3>
            <table class="recent-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pedidosRecientes)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #999; padding: 2rem;">
                            No hay pedidos registrados
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($pedidosRecientes as $pedido): ?>
                    <tr>
                        <td>#<?= $pedido['id_pedido'] ?></td>
                        <td><?= htmlspecialchars($pedido['cliente_nombre']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($pedido['fecha_creacion'])) ?></td>
                        <td>$<?= number_format($pedido['total_pedido'], 2) ?></td>
                        <td>
                            <?php
                            $badgeClass = match($pedido['estado']) {
                                'Presupuesto' => 'badge-presupuesto',
                                'En Produccion' => 'badge-produccion',
                                'Listo' => 'badge-listo',
                                'Entregado' => 'badge-entregado',
                                'Cancelado' => 'badge-cancelado',
                                default => ''
                            };
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= $pedido['estado'] ?></span>
                        </td>
                        <td>
                            <a href="/admin/detalle/<?= $pedido['id_pedido'] ?>" class="btn-view">
                                Ver Detalle <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function aprobarSolicitud(id) {
            if (!confirm('¿Aprobar esta solicitud?')) return;
            
            const formData = new FormData();
            formData.append('id', id);
            
            fetch('/admin/aprobar-solicitud', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('solicitud-' + id).remove();
                    location.reload();
                } else {
                    alert(data.error);
                }
            });
        }

        function rechazarSolicitud(id) {
            if (!confirm('¿Rechazar esta solicitud?')) return;
            
            const formData = new FormData();
            formData.append('id', id);
            
            fetch('/admin/rechazar-solicitud', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('solicitud-' + id).remove();
                    location.reload();
                } else {
                    alert(data.error);
                }
            });
        }
    </script>
</body>
</html>