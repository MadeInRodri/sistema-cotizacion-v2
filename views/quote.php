<?php require_once '../config/auth_guard.php'; ?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../public/assets/css/services-catalog.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <title>Detalle de Cotización</title>
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
        <h1>DETALLE DE SU COTIZACIÓN</h1>
        <nav>
            <ul class="nav-list">
                <li><a href="cart.php" class="button-filter" style="text-decoration:none">Volver al Catálogo</a></li>
                <li><a href="quotes.php" class="button-filter" style="text-decoration:none">Ver Historial</a></li>
            </ul>
        </nav>
    </header>
    <main class="grid-wrapper">
        <div class="grid-background"></div>
        <section class="content">
            <section class="products-view">
                <div class="table-card">
                    <div class="table-header">
                        <h2 id="quote-client-name">Cargando datos...</h2>
                        <p><strong>Código:</strong> <span id="quote-code">...</span> | <strong>Vence:</strong> <span
                                id="quote-date">...</span></p>
                    </div>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Categoría</th>
                                    <th class="text-right">Cantidad</th>
                                    <th class="text-right">Precio Unit.</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="quote-items-body">
                                <tr>
                                    <td colspan="5" style="text-align:center; padding: 2rem;">Cargando servicios...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="totals-container">
                        <div class="totals-wrapper">
                            <div class="total-row">
                                <span>Subtotal</span>
                                <span id="quote-subtotal">...</span>
                            </div>
                            <div class="total-row">
                                <span>Descuento Aplicado</span>
                                <span id="quote-discount">...</span>
                            </div>
                            <div class="total-row">
                                <span>IVA (13%)</span>
                                <span id="quote-tax">...</span>
                            </div>
                            <div class="total-row grand-total">
                                <span>Total Final</span>
                                <span class="price-highlight" id="quote-total">...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </main>

    <script src="../public/assets/js/quotes.js?v=<?= time() ?>"></script>
</body>

</html>