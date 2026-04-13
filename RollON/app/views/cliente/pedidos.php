<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos - Roll-ON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        :root { --primary: #e94560; --dark: #16213e; --success: #28a745; --gray: #6c757d; }
        body { background: #f5f6fa; min-height: 100vh; }
        
        .navbar {
            background: #fff;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .logo { text-decoration: none; }
        .logo h1 { color: var(--primary); font-size: 1.5rem; }
        .user-info { display: flex; align-items: center; gap: 1rem; }
        .user-info span { color: var(--dark); font-weight: 500; }
        .nav-links a {
            color: var(--dark);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 500;
        }
        .nav-links a:hover { background: var(--primary); color: #fff; }
        
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 2rem; }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .page-header h2 { color: var(--dark); }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-primary { background: var(--primary); color: #fff; }
        
        .card { background: #fff; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; color: var(--gray); font-weight: 600; }
        
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
        .badge-entregado { background: var(--success); color: #fff; }
        
        .btn-view {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--gray);
        }
        .empty-state i { font-size: 4rem; opacity: 0.3; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/cliente/cotizador" class="logo">
            <h1>ROLL-ON</h1>
        </a>
        <div class="user-info">
            <span>Hola, <?= htmlspecialchars($user['nombre']) ?>!</span>
            <div class="nav-links">
                <a href="/cliente/cotizador"><i class="fas fa-calculator"></i> Cotizador</a>
                <a href="/auth/logout"><i class="fas fa-sign-out-alt"></i> Salir</a>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="page-header">
            <h2>Mis Pedidos</h2>
            <a href="/cliente/cotizador" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nuevo Presupuesto
            </a>
        </div>

        <div class="card">
            <?php if (empty($pedidos)): ?>
            <div class="empty-state">
                <i class="fas fa-file-alt"></i>
                <p>No tenés pedidos aún</p>
                <a href="/cliente/cotizador" class="btn btn-primary" style="margin-top: 1rem;">
                    Crear mi primer presupuesto
                </a>
            </div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Cortinas</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td>#<?= $pedido['id_pedido'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($pedido['fecha_creacion'])) ?></td>
                        <td>
                            <?php 
                            $detalles = $pedidoModel->getDetalles($pedido['id_pedido']);
                            echo count($detalles['cortinas']);
                            ?>
                        </td>
                        <td><strong>$<?= number_format($pedido['total_pedido'], 2) ?></strong></td>
                        <td>
                            <?php
                            $badgeClass = match($pedido['estado']) {
                                'Presupuesto' => 'badge-presupuesto',
                                'En Produccion' => 'badge-produccion',
                                'Listo' => 'badge-listo',
                                'Entregado' => 'badge-entregado',
                                default => ''
                            };
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= $pedido['estado'] ?></span>
                        </td>
                        <td>
                            <a href="/cliente/pedidos/ver/<?= $pedido['id_pedido'] ?>" class="btn-view">
                                Ver <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>