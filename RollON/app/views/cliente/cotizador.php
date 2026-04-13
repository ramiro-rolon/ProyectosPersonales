<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cotizador - Roll-ON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #e94560;
            --primary-dark: #d63850;
            --secondary: #0f3460;
            --dark: #16213e;
            --dark-card: #1a2744;
            --light: #f8f9fa;
            --gray: #6c757d;
            --success: #28a745;
            --danger: #dc3545;
            --shadow: 0 10px 40px rgba(0,0,0,0.1);
            --radius: 16px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        .navbar {
            background: var(--dark-card);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        .logo h1 { color: var(--primary); font-size: 1.8rem; font-weight: 800; }
        .logo span { color: #fff; font-size: 0.8rem; font-weight: 400; }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .user-info span { color: #fff; font-weight: 500; }
        .nav-links a {
            color: #fff;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            transition: all 0.3s;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .nav-links a:hover { background: var(--primary); color: #fff; }

        .hero {
            background: linear-gradient(135deg, var(--dark) 0%, var(--secondary) 100%);
            color: #fff;
            padding: 8rem 2rem 4rem;
            text-align: center;
            position: relative;
        }
        .hero h2 { font-size: 3rem; margin-bottom: 1rem; }
        .hero p { font-size: 1.2rem; opacity: 0.9; }

        .container {
            max-width: 1200px;
            margin: -2rem auto 2rem;
            padding: 0 1rem;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 2rem;
            position: relative;
            z-index: 10;
        }

        @media (max-width: 968px) {
            .container { grid-template-columns: 1fr; }
        }

        .card {
            background: #fff;
            border-radius: var(--radius);
            padding: 2rem;
            box-shadow: var(--shadow);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light);
        }

        .card-header i { font-size: 1.5rem; color: var(--primary); }
        .card-header h3 { color: var(--dark); font-size: 1.3rem; }

        .form-group { margin-bottom: 1.5rem; }
        .form-group label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            font-weight: 500;
            color: var(--dark);
        }

        .form-control {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            background: var(--light);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(233,69,96,0.1);
        }

        .form-control.error {
            border-color: var(--danger);
        }

        .dimensions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        #resultado {
            padding: 1.5rem;
            border-radius: var(--radius);
            text-align: center;
            margin: 1.5rem 0;
            animation: fadeIn 0.3s ease;
            display: none;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .result-box {
            background: linear-gradient(135deg, var(--primary), #ff6b6b);
            color: #fff;
        }

        .result-box.error { background: linear-gradient(135deg, var(--danger), #ff4757); }

        .result-box .price { font-size: 2.5rem; font-weight: 700; }
        .result-box .dispositivo { font-size: 0.85rem; opacity: 0.85; margin-top: 0.5rem; }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            width: 100%;
        }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(233,69,96,0.3); }
        .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }

        .btn-success {
            background: linear-gradient(135deg, var(--success), #20c997);
            color: #fff;
            width: 100%;
        }
        .btn-success:hover { transform: translateY(-3px); }
        .btn-success:disabled { opacity: 0.5; }

        .extras-list { display: flex; flex-direction: column; gap: 0.75rem; }

        .extra-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: var(--light);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .extra-item:hover { background: #fff; border-color: var(--primary); }

        .extra-item input { display: none; }

        .extra-item .checkbox {
            width: 24px;
            height: 24px;
            border: 2px solid #dee2e6;
            border-radius: 6px;
            margin-right: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .extra-item input:checked + .checkbox {
            background: var(--primary);
            border-color: var(--primary);
        }

        .extra-item input:checked + .checkbox::after {
            content: '✓';
            color: #fff;
            font-size: 0.8rem;
        }

        .extra-item .info { flex: 1; }
        .extra-item .info h4 { color: var(--dark); font-size: 0.95rem; }
        .extra-item .price-tag { font-weight: 600; color: var(--primary); }

        .cart-empty {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--gray);
        }

        .cart-empty i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.3; }

        .cart-items { max-height: 300px; overflow-y: auto; }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: var(--light);
            border-radius: 12px;
            margin-bottom: 0.75rem;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .cart-item .item-info h4 { color: var(--dark); font-size: 0.95rem; }
        .cart-item .item-info p { color: var(--gray); font-size: 0.85rem; }
        .cart-item .item-price { text-align: right; }
        .cart-item .item-price .price { font-size: 1.1rem; font-weight: 700; color: var(--primary); }
        .cart-item .item-price .delete-btn { color: var(--danger); cursor: pointer; margin-top: 0.25rem; }

        .cart-summary {
            background: linear-gradient(135deg, var(--dark), var(--secondary));
            color: #fff;
            padding: 1.5rem;
            border-radius: var(--radius);
            margin-top: 1rem;
        }

        .cart-summary .extras-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            font-size: 0.9rem;
        }

        .cart-summary .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .cart-summary .total-row .amount { color: var(--primary); font-size: 1.8rem; }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal.active { display: flex; }

        .modal-content {
            background: #fff;
            padding: 3rem;
            border-radius: var(--radius);
            max-width: 450px;
            width: 90%;
            text-align: center;
            animation: modalPop 0.3s ease;
        }

        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-icon { font-size: 5rem; color: var(--success); margin-bottom: 1.5rem; }
        .modal-content h2 { color: var(--dark); margin-bottom: 0.5rem; }
        .modal-content p { color: var(--gray); margin-bottom: 1.5rem; }

        .modal-details {
            background: var(--light);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        .modal-details .detail {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
        }

        .modal-details .detail:last-child {
            border-top: 1px solid #dee2e6;
            margin-top: 0.5rem;
            padding-top: 1rem;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .modal-details .detail span:last-child { color: var(--primary); }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/cliente/cotizador" class="logo">
            <h1>ROLL-ON</h1>
            <span>Cortinas a medida</span>
        </a>
        <div class="user-info">
            <span>Hola, <?= htmlspecialchars($user['nombre']) ?>!</span>
            <div class="nav-links">
                <a href="/cliente/pedidos"><i class="fas fa-shopping-cart"></i> Mis Pedidos</a>
                <a href="/auth/logout"><i class="fas fa-sign-out-alt"></i> Salir</a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <h2>Mi Cotizador</h2>
        <p>Configurá tus cortinas y obtené tu presupuesto al instante</p>
    </section>

    <div class="container">
        <div class="left-column">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-cut"></i>
                    <h3>Configurar Cortina</h3>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-scroll"></i> Tela</label>
                    <select id="tela" class="form-control">
                        <option value="">Seleccionar tela...</option>
                        <?php foreach ($telas as $tela): ?>
                        <option value="<?= $tela['id_tela'] ?>" data-ancho-max="<?= $tela['ancho_maximo_tela'] ?? 3 ?>">
                            <?= htmlspecialchars($tela['nombre_tela']) ?> - $<?= number_format($tela['precio_m2'], 2) ?>/m²
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="dimensions">
                    <div class="form-group">
                        <label><i class="fas fa-arrows-alt-h"></i> Ancho (m)</label>
                        <input type="number" id="ancho" class="form-control" step="0.01" min="0.1" placeholder="Ej: 1.50">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-arrows-alt-v"></i> Largo (m)</label>
                        <input type="number" id="largo" class="form-control" step="0.01" min="0.1" placeholder="Ej: 2.00">
                    </div>
                </div>

                <div id="resultado"></div>

                <button id="btnAgregar" class="btn btn-primary" disabled>
                    <i class="fas fa-plus"></i> Agregar al Presupuesto
                </button>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <i class="fas fa-tools"></i>
                    <h3>Servicios Extra</h3>
                </div>

                <div class="extras-list">
                    <?php foreach ($extras as $extra): ?>
                    <label class="extra-item">
                        <input type="checkbox" data-id="<?= $extra['id_extra'] ?>" data-precio="<?= $extra['precio_fijo'] ?>">
                        <span class="checkbox"></span>
                        <div class="info">
                            <h4><?= htmlspecialchars($extra['descripcion']) ?></h4>
                        </div>
                        <span class="price-tag">$<?= number_format($extra['precio_fijo'], 2) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="right-column">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Tu Presupuesto</h3>
                </div>

                <div id="carrito">
                    <div class="cart-empty">
                        <i class="fas fa-shopping-basket"></i>
                        <p>Agregá cortinas para cotizar</p>
                    </div>
                </div>

                <div id="totalBox" style="display: none;">
                    <div class="cart-summary">
                        <div id="extrasResumen"></div>
                        <div class="total-row">
                            <span>Total</span>
                            <span class="amount" id="totalAmount">$0.00</span>
                        </div>
                    </div>

                    <button id="btnFinalizar" class="btn btn-success" style="margin-top: 1rem;">
                        <i class="fas fa-check-circle"></i> Finalizar Presupuesto
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modalFinalizar">
        <div class="modal-content">
            <div class="modal-icon"><i class="fas fa-check-circle"></i></div>
            <h2>¡Presupuesto Guardado!</h2>
            <p>Tu presupuesto ha sido registrado exitosamente</p>
            
            <div class="modal-details">
                <div class="detail">
                    <span>Número de Pedido</span>
                    <span>#<span id="pedidoId"></span></span>
                </div>
                <div class="detail">
                    <span>Total</span>
                    <span>$<span id="pedidoTotal"></span></span>
                </div>
            </div>

            <button class="btn btn-primary" onclick="cerrarModal()">
                <i class="fas fa-arrow-right"></i> Continuar
            </button>
        </div>
    </div>

    <script>
        const cortinas = [];
        const extrasSeleccionados = [];

        const tela = document.getElementById('tela');
        const ancho = document.getElementById('ancho');
        const largo = document.getElementById('largo');
        const resultado = document.getElementById('resultado');
        const btnAgregar = document.getElementById('btnAgregar');
        const btnFinalizar = document.getElementById('btnFinalizar');

        function validarCampo(input, esPositivo = true) {
            const valor = input.value.trim();
            
            if (valor === '') {
                input.classList.remove('error');
                return null;
            }

            const num = parseFloat(valor);

            if (isNaN(num)) {
                input.classList.add('error');
                return 'Solo números';
            }

            if (esPositivo && num <= 0) {
                input.classList.add('error');
                return 'Mayor a 0';
            }

            if (num > 100) {
                input.classList.add('error');
                return 'Valor muy grande';
            }

            input.classList.remove('error');
            return num;
        }

        function calcular() {
            const idTela = tela.value;
            const anchoVal = validarCampo(ancho, true);
            const largoVal = validarCampo(largo, true);

            if (!idTela) {
                resultado.style.display = 'none';
                btnAgregar.disabled = true;
                return;
            }

            if (anchoVal === 'Solo números' || anchoVal === 'Mayor a 0' || anchoVal === 'Valor muy grande') {
                resultado.className = 'result-box error';
                resultado.innerHTML = `<i class="fas fa-exclamation-triangle"></i> Ancho: ${anchoVal}`;
                resultado.style.display = 'block';
                btnAgregar.disabled = true;
                return;
            }

            if (largoVal === 'Solo números' || largoVal === 'Mayor a 0' || largoVal === 'Valor muy grande') {
                resultado.className = 'result-box error';
                resultado.innerHTML = `<i class="fas fa-exclamation-triangle"></i> Largo: ${largoVal}`;
                resultado.style.display = 'block';
                btnAgregar.disabled = true;
                return;
            }

            if (anchoVal === null || largoVal === null) {
                resultado.style.display = 'none';
                btnAgregar.disabled = true;
                return;
            }

            fetch('/cliente/calcular', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id_tela=${idTela}&ancho=${anchoVal}&largo=${largoVal}`
            })
            .then(r => r.json())
            .then(data => {
                if (data.valido) {
                    resultado.className = 'result-box';
                    resultado.innerHTML = `
                        <h4>Valor de la cortina</h4>
                        <div class="price">$${data.valor.toFixed(2)}</div>
                        <div class="dispositivo">
                            <i class="fas fa-cog"></i> Mecanismo: ${data.dispositivo}
                        </div>
                    `;
                    resultado.dataset.valor = data.valor;
                    btnAgregar.disabled = false;
                } else {
                    resultado.className = 'result-box error';
                    resultado.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${data.error}`;
                    btnAgregar.disabled = true;
                }
                resultado.style.display = 'block';
            });
        }

        tela.addEventListener('change', calcular);
        ancho.addEventListener('input', calcular);
        largo.addEventListener('input', calcular);

        btnAgregar.addEventListener('click', () => {
            const telaOption = tela.options[tela.selectedIndex];
            cortinas.push({
                id_tela: tela.value,
                nombre_tela: telaOption.text.split(' - ')[0],
                ancho: parseFloat(ancho.value),
                largo: parseFloat(largo.value),
                valor: parseFloat(resultado.dataset.valor)
            });

            ancho.value = '';
            largo.value = '';
            tela.value = '';
            resultado.style.display = 'none';
            btnAgregar.disabled = true;
            actualizarCarrito();
        });

        function actualizarCarrito() {
            const carrito = document.getElementById('carrito');
            
            if (cortinas.length === 0) {
                carrito.innerHTML = `
                    <div class="cart-empty">
                        <i class="fas fa-shopping-basket"></i>
                        <p>Agregá cortinas para cotizar</p>
                    </div>
                `;
                document.getElementById('totalBox').style.display = 'none';
                btnFinalizar.disabled = true;
                return;
            }

            let html = '<div class="cart-items">';
            let subtotal = 0;

            cortinas.forEach((c, i) => {
                html += `
                    <div class="cart-item">
                        <div class="item-info">
                            <h4>${c.nombre_tela}</h4>
                            <p>${c.ancho}m × ${c.largo}m</p>
                        </div>
                        <div class="item-price">
                            <div class="price">$${c.valor.toFixed(2)}</div>
                            <div class="delete-btn" onclick="eliminarCortina(${i})">
                                <i class="fas fa-trash"></i>
                            </div>
                        </div>
                    </div>
                `;
                subtotal += c.valor;
            });

            html += '</div>';
            carrito.innerHTML = html;

            let totalExtras = 0;
            extrasSeleccionados.forEach(e => {
                totalExtras += e.precio * e.cantidad;
            });

            document.getElementById('extrasResumen').innerHTML = extrasSeleccionados.length > 0
                ? extrasSeleccionados.map(e => `
                    <div class="extras-row">
                        <span>${e.descripcion}</span>
                        <span>$${(e.precio * e.cantidad).toFixed(2)}</span>
                    </div>
                `).join('')
                : '';

            document.getElementById('totalAmount').textContent = '$' + (subtotal + totalExtras).toFixed(2);
            document.getElementById('totalBox').style.display = 'block';
            btnFinalizar.disabled = false;
        }

        function eliminarCortina(index) {
            cortinas.splice(index, 1);
            actualizarCarrito();
        }

        document.querySelectorAll('.extra-item input').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const id = this.dataset.id;
                const precio = parseFloat(this.dataset.precio);
                const desc = this.closest('.extra-item').querySelector('.info h4').textContent;

                if (this.checked) {
                    extrasSeleccionados.push({ id_extra: id, descripcion: desc, precio: precio, cantidad: 1 });
                } else {
                    const idx = extrasSeleccionados.findIndex(e => e.id_extra === id);
                    if (idx > -1) extrasSeleccionados.splice(idx, 1);
                }
                actualizarCarrito();
            });
        });

        btnFinalizar.addEventListener('click', () => {
            const formData = new FormData();
            formData.append('cortinas', JSON.stringify(cortinas));
            formData.append('extras', JSON.stringify(extrasSeleccionados));

            fetch('/cliente/crearPedido', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('pedidoId').textContent = data.id_pedido;
                    document.getElementById('pedidoTotal').textContent = data.total.toFixed(2);
                    document.getElementById('modalFinalizar').classList.add('active');
                    
                    cortinas.length = 0;
                    extrasSeleccionados.length = 0;
                    document.querySelectorAll('.extra-item input').forEach(c => c.checked = false);
                    actualizarCarrito();
                } else {
                    alert('Error: ' + data.error);
                }
            });
        });

        function cerrarModal() {
            document.getElementById('modalFinalizar').classList.remove('active');
        }
    </script>
</body>
</html>