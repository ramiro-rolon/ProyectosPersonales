# ROLL-ON - Cotizador de Cortinas

Aplicación web MVC en PHP para cotizar cortinas a medida.

## Requisitos

- PHP 7.4+ (con extensión PDO para MySQL)
- MySQL 5.7+
- Servidor web (XAMPP, WAMP, Laragon, o PHP built-in server)

## Instalación

### 1. Base de datos

Ejecutar los archivos SQL en MySQL en este orden:

```sql
mysql -u root -p
source Base_Roll_On.sql;
source Functions.sql;
source Procedures.sql;
source Views.sql;
```

### 2. Ejecutar

**Opción A - Script automático (Windows):**
```
Doble clic en RUN.bat
```

**Opción B - Servidor PHP manual:**
```bash
cd RollON/public
php -S localhost:8000
```

**Opción C - Con XAMPP:**
1. Copia la carpeta `RollON` a `C:\xampp\htdocs\`
2. Accede a `http://localhost/RollON/public/`

## Acceso

| Rol | URL | Usuario | Contraseña |
|-----|-----|---------|------------|
| Cliente | `/RollON/public/` | - | - |
| Admin | `/RollON/public/admin/login` | Daniel | Flora0612 |

## Estructura MVC

```
RollON/
├── app/
│   ├── core/
│   │   ├── Database.php
│   │   └── Router.php
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── AdminController.php
│   │   ├── AdminCrudController.php
│   │   ├── CotizadorController.php
│   │   └── PedidoController.php
│   ├── models/
│   │   ├── Model.php
│   │   ├── CortinaModel.php
│   │   ├── TelaModel.php
│   │   ├── DispositivoModel.php
│   │   ├── ExtraModel.php
│   │   └── PedidoModel.php
│   └── views/
│       ├── auth/login.php
│       ├── cotizador/index.php
│       └── admin/
│           ├── dashboard.php
│           ├── telas.php
│           ├── dispositivos.php
│           ├── extras.php
│           ├── pedidos.php
│           └── pedido_detalle.php
├── public/
│   ├── index.php
│   └── .htaccess
├── Base_Roll_On.sql
├── Functions.sql
├── Procedures.sql
├── Views.sql
├── RUN.bat
└── README.md
```

## Funcionalidades

- [x] Cotización en tiempo real (AJAX)
- [x] Validación de medidas en cliente
- [x] Carrito de presupuesto múltiple
- [x] Extras (instalación, envío, etc.)
- [x] Login admin con sesión
- [x] CRUD Telas, Dispositivos, Extras
- [x] Gestión de pedidos
- [x] Cambio de estado (Presupuesto → Entregado)

## Pendiente

- [ ] Exportar a PDF
- [ ] Envío de email
- [ ] Dashboard con gráficos
- [ ] Datos de prueba (seed)
