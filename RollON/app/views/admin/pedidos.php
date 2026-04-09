<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos - Admin Roll-ON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        :root { --primary: #e94560; --dark: #16213e; --success: #28a745; --danger: #dc3545; }
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
        .header a:hover { color: var(--primary); }
        
        .container { max-width: 1400px; margin: 2rem auto; padding: 0 2rem; }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .page-header h2 { color: var(--dark); }
        
        .filters {
            background: #fff;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .filters .form-group { margin: 0; display: flex; align-items: center; gap: 0.5rem; }
        .filters label { font-weight: 500; color: #666; white-space: nowrap; }
        
        .filters input,
        .filters select {
            padding: 0.5rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.95rem;
        }
        
        .filters input:focus,
        .filters select:focus { outline: none; border-color: var(--primary); }
        
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
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .btn-sm { padding: 0.5rem 1rem; font-size: 0.85rem; }
        
        .card { background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; color: #666; font-weight: 600; }
        tr:hover { background: #fafafa; }
        tr:last-child td { border-bottom: none; }
        
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
        .badge-cancelado { background: var(--danger); color: #fff; }
        
        .btn-view {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        .btn-view:hover { text-decoration: underline; }
        
        .actions { display: flex; gap: 0.5rem; align-items: center; }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }
        .empty-state i { font-size: 4rem; opacity: 0.3; margin-bottom: 1rem; }
        
        .toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: #fff;
            font-weight: 500;
            z-index: 2000;
            animation: slideIn 0.3s ease;
        }
        .toast.success { background: var(--success); }
        .toast.error { background: var(--danger); }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-shopping-cart"></i> Pedidos</h1>
        <div>
            <a href="/admin"><i class="fas fa-home"></i> Dashboard</a>
            <a href="/auth/logout"><i class="fas fa-sign-out-alt"></i> Salir</a>
        </div>
    </div>
    
    <div class="container">
        <div class="page-header">
            <h2>Gestión de Pedidos</h2>
        </div>

        <div class="filters">
            <div class="form-group">
                <label for="buscar">Buscar:</label>
                <input type="text" id="buscar" placeholder="Nombre o ID...">
            </div>
            <div class="form-group">
                <label for="estado">Estado:</label>
                <select id="estado">
                    <option value="Todos">Todos</option>
                    <option value="Presupuesto">Presupuesto</option>
                    <option value="En Produccion">En Producción</option>
                    <option value="Listo">Listo</option>
                    <option value="Entregado">Entregado</option>
                    <option value="Cancelado">Cancelado</option>
                </select>
            </div>
            <button class="btn btn-primary btn-sm" onclick="filtrarPedidos()">
                <i class="fas fa-search"></i> Filtrar
            </button>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Cortinas</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="pedidosBody">
                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>Cargando pedidos...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const estadosValidos = ['Presupuesto', 'En Produccion', 'Listo', 'Entregado', 'Cancelado'];
        
        function getBadgeClass(estado) {
            const map = {
                'Presupuesto': 'badge-presupuesto',
                'En Produccion': 'badge-produccion',
                'Listo': 'badge-listo',
                'Entregado': 'badge-entregado',
                'Cancelado': 'badge-cancelado'
            };
            return map[estado] || '';
        }

        function cargarPedidos() {
            const buscar = document.getElementById('buscar').value;
            const estado = document.getElementById('estado').value;
            
            fetch(`/pedido/listar?buscar=${encodeURIComponent(buscar)}&estado=${encodeURIComponent(estado)}`)
                .then(r => r.json())
                .then(data => {
                    const tbody = document.getElementById('pedidosBody');
                    
                    if (data.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>No se encontraron pedidos</p>
                                </td>
                            </tr>
                        `;
                        return;
                    }

                    tbody.innerHTML = data.map(p => `
                        <tr>
                            <td>#${p.id_pedido}</td>
                            <td>${escapeHtml(p.nombre_cliente)}</td>
                            <td>${formatearFecha(p.fecha_creacion)}</td>
                            <td>${p.cantidad_cortinas || 0}</td>
                            <td><strong>$${parseFloat(p.total_pedido).toFixed(2)}</strong></td>
                            <td>
                                <select class="estado-select" onchange="cambiarEstado(${p.id_pedido}, this.value)" 
                                        style="padding: 0.25rem 0.5rem; border-radius: 6px; border: 1px solid #ddd;">
                                    ${estadosValidos.map(e => 
                                        `<option value="${e}" ${p.estado === e ? 'selected' : ''}>${e}</option>`
                                    ).join('')}
                                </select>
                            </td>
                            <td class="actions">
                                <a href="/admin/pedidos/ver/${p.id_pedido}" class="btn-view">
                                    Ver <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    `).join('');
                });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatearFecha(fecha) {
            const d = new Date(fecha);
            return d.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        }

        function cambiarEstado(id, estado) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('estado', estado);
            
            fetch('/pedido/actualizarEstado', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    mostrarToast('Estado actualizado', 'success');
                } else {
                    mostrarToast(data.error, 'error');
                    cargarPedidos();
                }
            });
        }

        function filtrarPedidos() {
            cargarPedidos();
        }

        document.getElementById('buscar').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') filtrarPedidos();
        });

        function mostrarToast(mensaje, tipo) {
            const toast = document.createElement('div');
            toast.className = 'toast ' + tipo;
            toast.textContent = mensaje;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        cargarPedidos();
    </script>
</body>
</html>
