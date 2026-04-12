<?php require_once '../config/admin_guard.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestor de Servicios | L&R Solutions</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="../public/assets/css/admin-panel.css" />
</head>

<body class="admin-page">
    <nav class="user-topbar">
        <div class="user-greeting">
            <i class="fa-solid fa-circle-user"></i>
            <span>Hola, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
        </div>
        <a href="../controllers/AuthController.php?action=logout" class="logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
        </a>
    </nav>

    <header class="header">
        <h1 class="logo">GESTOR DE SERVICIOS</h1>
    </header>

    <main class="grid-wrapper">
        <div class="grid-background"></div>

        <section class="content">
            <div class="admin-flex-layout">

                <div class="form-container">
                    <div class="table-card">
                        <div class="table-header">
                            <h2 id="panel-title">Nuevo Servicio</h2>
                            <button id="btn-nuevo" class="button-filter">+ Crear Nuevo</button>
                        </div>

                        <form id="form-service-admin" class="admin-form-padding">
                            <input type="hidden" id="service-id">

                            <div class="full-width">
                                <label>Nombre del Servicio</label>
                                <input type="text" id="service-name" placeholder="Ej. Desarrollo Multiplataforma"
                                    required>
                            </div>

                            <div>
                                <label>Categoría</label>
                                <select id="service-category" class="admin-select" required>
                                    <option value="1" selected>Web</option>
                                    <option value="2">Backend</option>
                                    <option value="3">Mobile</option>
                                    <option value="4">DevOps</option>
                                </select>
                            </div>

                            <div>
                                <label>Precio ($)</label>
                                <input type="number" step="0.01" id="service-price" placeholder="0.00" required>
                            </div>

                            <div class="full-width">
                                <label>Descripción del Servicio</label>
                                <textarea id="service-description" rows="4" placeholder="Describe el servicio..."
                                    required></textarea>
                            </div>

                            <div class="full-width">
                                <button type="submit" class="submit-btn" style="width: 100%; border-radius: 50px;">
                                    Guardar en Base de Datos
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <aside class="list-container">
                    <div class="table-card">
                        <div class="table-header">
                            <h2>Servicios Actuales</h2>
                        </div>
                        <div class="admin-list-wrapper">
                            <select id="service-selector" size="15" class="admin-vertical-list"></select>
                        </div>
                    </div>
                </aside>
            </div>

            <div class="admin-quotes-section">
                <div class="table-card">
                    <div class="table-header">
                        <h2><i class="fa-solid fa-receipt"></i> Historial de Cotizaciones</h2>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Empresa</th>
                                    <th>Total</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody id="quotes-list">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../public/assets/js/admin-services.js"></script>
</body>

</html>