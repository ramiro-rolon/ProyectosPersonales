<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido #<?= $pedido['id_pedido'] ?> - Roll-ON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        :root { --primary: #e94560; --dark: #16213e; --gray: #6c757d; --success: #28a745; }
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
        .btn-secondary { background: var(--gray); color: #fff; }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .info-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .info-card h3 {
            color: var(--dark);
            margin-bottom: 1rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .info-card h3 i { color: var(--primary); }
        
        .info-item { margin-bottom: 0.75rem; }
        .info-item label { color: var(--gray); font-size: 0.85rem; display: block; }
        .info-item span { font-weight: 600; color: var(--dark); }
        
        .badge {
            display: inline-block;
            padding: 0.35rem 1rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .badge-presupuesto { background: #fff3cd; color: #856404; }
        .badge-produccion { background: #cce5ff; color: #004085; }
        .badge-listo { background: #d4edda; color: #155724; }
        .badge-entregado { background: var(--success); color: #fff; }
        
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        
        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-header h3 { color: var(--dark); display: flex; align-items: center; gap: 0.5rem; }
        .card-header h3 i { color: var(--primary); }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; color: var(--gray); font-weight: 600; font-size: 0.9rem; }
        
        .precio { font-weight: 700; color: var(--primary); }
        
        .total-section {
            background: linear-gradient(135deg, var(--dark), var(--primary));
            color: #fff;
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            margin-top: 2rem;
        }
        
        .total-section h3 { font-size: 1.2rem; opacity: 0.9; }
        .total-section .amount { font-size: 3rem; font-weight: 700; margin-top: 0.5rem; }

        .actions-bar { margin-bottom: 2rem; }
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
                <a href="/cliente/pedidos"><i class="fas fa-shopping-cart"></i> Mis Pedidos</a>
                <a href="/auth/logout"><i class="fas fa-sign-out-alt"></i> Salir</a>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="actions-bar">
            <a href="/cliente/pedidos" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a Mis Pedidos
            </a>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <h3><i class="fas fa-shopping-cart"></i> Pedido #<?= $pedido['id_pedido'] ?></h3>
                <div class="info-item">
                    <label>Estado</label>
                    <span>
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
                    </span>
                </div>
            </div>
            
            <div class="info-card">
                <h3><i class="fas fa-calendar"></i> Fechas</h3>
                <div class="info-item">
                    <label>Creado</label>
                    <span><?= date('d/m/Y H:i', strtotime($pedido['fecha_creacion'])) ?></span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-window-maximize"></i> Cortinas</h3>
                <span><?= count($detalles['cortinas']) ?> unidad(es)</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Tela</th>
                        <th>Dispositivo</th>
                        <th>Ancho</th>
                        <th>Largo</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles['cortinas'] as $cortina): ?>
                    <tr>
                        <td><?= htmlspecialchars($cortina['nombre_tela']) ?></td>
                        <td><?= htmlspecialchars($cortina['nombre_dispositivo']) ?></td>
                        <td><?= $cortina['ancho'] ?>m</td>
                        <td><?= $cortina['largo'] ?>m</td>
                        <td class="precio">$<?= number_format($cortina['subtotal_cortina'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($detalles['extras'])): ?>
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-plus-circle"></i> Servicios Extra</h3>
                <span><?= count($detalles['extras']) ?> item(s)</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles['extras'] as $extra): ?>
                    <tr>
                        <td><?= htmlspecialchars($extra['descripcion']) ?></td>
                        <td><?= $extra['cantidad'] ?></td>
                        <td>$<?= number_format($extra['precio_al_momento'], 2) ?></td>
                        <td class="precio">$<?= number_format($extra['cantidad'] * $extra['precio_al_momento'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <div class="total-section">
            <h3>Total del Pedido</h3>
            <div class="amount">$<?= number_format($pedido['total_pedido'], 2) ?></div>
        </div>
    </div>
</body>
</html>