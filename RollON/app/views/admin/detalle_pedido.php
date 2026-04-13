<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Pedido #<?= $pedido['id_pedido'] ?> - Roll-ON Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        :root { --primary: #e94560; --dark: #16213e; --success: #28a745; --gray: #6c757d; }
        body { background: #f5f6fa; min-height: 100vh; }
        
        .header {
            background: var(--dark);
            color: #fff;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 { color: var(--primary); font-size: 1.5rem; }
        .header a { color: #fff; text-decoration: none; margin-left: 1rem; }
        
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
        .btn-secondary { background: #6c757d; color: #fff; }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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
        .badge-cancelado { background: #dc3545; color: #fff; }
        
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
        th { background: #f8f9fa; color: var(--gray); font-weight: 600; }
        
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
    <div class="header">
        <h1><i class="fas fa-file-alt"></i> Detalle Pedido #<?= $pedido['id_pedido'] ?></h1>
        <div>
            <a href="/admin/dashboard"><i class="fas fa-arrow-left"></i> Volver</a>
            <a href="/auth/logout"><i class="fas fa-sign-out-alt"></i> Salir</a>
        </div>
    </div>
    
    <div class="container">
        <div class="actions-bar">
            <a href="/admin/dashboard" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <h3><i class="fas fa-user"></i> Cliente</h3>
                <?php if (!empty($cliente)): ?>
                <div class="info-item">
                    <label>Nombre</label>
                    <span><?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) ?></span>
                </div>
                <div class="info-item">
                    <label>Email</label>
                    <span><?= htmlspecialchars($cliente['email'] ?? 'No disponible') ?></span>
                </div>
                <div class="info-item">
                    <label>Contacto</label>
                    <span><?= htmlspecialchars($cliente['contacto'] ?? 'No disponible') ?></span>
                </div>
                <?php else: ?>
                <p style="color: #999;">Cliente no encontrado</p>
                <?php endif; ?>
            </div>
            
            <div class="info-card">
                <h3><i class="fas fa-info-circle"></i> Pedido</h3>
                <div class="info-item">
                    <label>Estado</label>
                    <span>
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
                    </span>
                </div>
                <div class="info-item">
                    <label>Fecha de Creación</label>
                    <span><?= date('d/m/Y H:i', strtotime($pedido['fecha_creacion'])) ?></span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-window-maximize"></i> Cortinas</h3>
                <span><?= count($detalle) ?> unidad(es)</span>
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
                    <?php foreach ($detalle as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nombre_tela'] ?? $item['tela'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($item['nombre_dispositivo'] ?? $item['dispositivo'] ?? 'N/A') ?></td>
                        <td><?= $item['ancho'] ?? 'N/A' ?>m</td>
                        <td><?= $item['largo'] ?? 'N/A' ?>m</td>
                        <td class="precio">$<?= number_format($item['subtotal_cortina'] ?? $item['subtotal'] ?? 0, 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="total-section">
            <h3>Total del Pedido</h3>
            <div class="amount">$<?= number_format($pedido['total_pedido'], 2) ?></div>
        </div>
    </div>
</body>
</html>