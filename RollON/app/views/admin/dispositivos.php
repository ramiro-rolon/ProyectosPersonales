<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispositivos - Admin Roll-ON</title>
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
        .btn-primary:hover { background: #d63850; }
        .btn-success { background: var(--success); color: #fff; }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .btn-sm { padding: 0.5rem 1rem; font-size: 0.85rem; }
        
        .card { background: #fff; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; color: #666; font-weight: 600; }
        tr:hover { background: #fafafa; }
        
        .actions { display: flex; gap: 0.5rem; }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal.active { display: flex; }
        
        .modal-content {
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .modal-header h3 { color: var(--dark); }
        .modal-close { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #666; }
        
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333; }
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
        }
        .form-group input:focus { outline: none; border-color: var(--primary); }
        
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        
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
        <h1><i class="fas fa-cogs"></i> Dispositivos</h1>
        <div>
            <a href="/admin"><i class="fas fa-home"></i> Dashboard</a>
            <a href="/auth/logout"><i class="fas fa-sign-out-alt"></i> Salir</a>
        </div>
    </div>
    
    <div class="container">
        <div class="page-header">
            <h2>Gestión de Dispositivos</h2>
            <button class="btn btn-primary" onclick="abrirModal()">
                <i class="fas fa-plus"></i> Nuevo Dispositivo
            </button>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Ancho Mín</th>
                        <th>Ancho Máx</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dispositivos as $disp): ?>
                    <tr id="disp-<?= $disp['id_dispositivo'] ?>">
                        <td><?= $disp['id_dispositivo'] ?></td>
                        <td><?= htmlspecialchars($disp['nombre_dispositivo']) ?></td>
                        <td><?= $disp['ancho_minimo'] ?>m</td>
                        <td><?= $disp['ancho_maximo'] ?>m</td>
                        <td>$<?= number_format($disp['precio_dispositivo'], 2) ?></td>
                        <td class="actions">
                            <button class="btn btn-secondary btn-sm" onclick="editarDisp(<?= htmlspecialchars(json_encode($disp)) ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="eliminarDisp(<?= $disp['id_dispositivo'] ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal" id="modalDisp">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitulo">Nuevo Dispositivo</h3>
                <button class="modal-close" onclick="cerrarModal()">&times;</button>
            </div>
            <form id="formDisp">
                <input type="hidden" id="dispId" name="id">
                <div class="form-group">
                    <label for="nombre">Nombre del Dispositivo</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="anchoMin">Ancho Mínimo (m)</label>
                        <input type="number" id="anchoMin" name="ancho_min" step="0.01" min="0.1" required>
                    </div>
                    <div class="form-group">
                        <label for="anchoMax">Ancho Máximo (m)</label>
                        <input type="number" id="anchoMax" name="ancho_max" step="0.01" min="0.1" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="precio">Precio ($)</label>
                    <input type="number" id="precio" name="precio" step="0.01" min="0.01" required>
                </div>
                <button type="submit" class="btn btn-success" style="width: 100%;">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalDisp');
        const form = document.getElementById('formDisp');

        function abrirModal() {
            document.getElementById('modalTitulo').textContent = 'Nuevo Dispositivo';
            document.getElementById('dispId').value = '';
            form.reset();
            modal.classList.add('active');
        }

        function cerrarModal() {
            modal.classList.remove('active');
        }

        function editarDisp(disp) {
            document.getElementById('modalTitulo').textContent = 'Editar Dispositivo';
            document.getElementById('dispId').value = disp.id_dispositivo;
            document.getElementById('nombre').value = disp.nombre_dispositivo;
            document.getElementById('anchoMin').value = disp.ancho_minimo;
            document.getElementById('anchoMax').value = disp.ancho_maximo;
            document.getElementById('precio').value = disp.precio_dispositivo;
            modal.classList.add('active');
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            
            fetch('/admin-crud/saveDispositivo', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    cerrarModal();
                    location.reload();
                } else {
                    mostrarToast(data.error, 'error');
                }
            });
        });

        function eliminarDisp(id) {
            if (!confirm('¿Estás seguro de eliminar este dispositivo?')) return;
            
            const formData = new FormData();
            formData.append('id', id);
            
            fetch('/admin-crud/deleteDispositivo', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('disp-' + id).remove();
                    mostrarToast(data.message, 'success');
                } else {
                    mostrarToast(data.error, 'error');
                }
            });
        }

        function mostrarToast(mensaje, tipo) {
            const toast = document.createElement('div');
            toast.className = 'toast ' + tipo;
            toast.textContent = mensaje;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        modal.addEventListener('click', function(e) {
            if (e.target === modal) cerrarModal();
        });
    </script>
</body>
</html>
