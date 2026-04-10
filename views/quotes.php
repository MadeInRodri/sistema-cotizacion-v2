<?php require_once '../config/auth_guard.php'; ?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../public/assets/css/services-catalog.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <title>Historial de Cotizaciones</title>
</head>

<body>
    <div class="user-topbar">
        <span class="user-greeting">
            <i class="fa-solid fa-circle-user"></i> Hola, <?= htmlspecialchars($_SESSION['user_name']) ?>
        </span>
        <a href="../controllers/AuthController.php?action=logout" class="logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
        </a>
    </div>
    <header class="header">
        <h1>HISTORIAL DE COTIZACIONES</h1>
        <nav>
            <ul class="nav-list">
                <li><a href="cart.php" class="button-filter" style="text-decoration:none">Nuevo Catálogo</a></li>
            </ul>
        </nav>
    </header>
    <main class="grid-wrapper">
        <div class="grid-background"></div>
        <section class="content">
            <div class="table-card">
                <div class="table-header">
                    <h2>Clientes y Cotizaciones</h2>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Cliente</th>
                                <th>Empresa</th>
                                <th>Fecha</th>
                                <th class="text-right">Total</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="quotes-table-body">
                            <tr>
                                <td colspan="6" style="text-align:center; padding: 2rem;">Cargando historial...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <script src="../public/assets/js/quotes.js?v=<?= time() ?>"></script>
</body>

</html>