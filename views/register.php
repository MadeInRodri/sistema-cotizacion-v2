<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | LEO'S && RODRI'S</title>
    <link rel="stylesheet" href="../public/assets/css/services-catalog.css">
    <link rel="stylesheet" href="../public/assets/css/auth.css">
</head>

<body>

    <div class="auth-wrapper">
        <div class="grid-background"></div>

        <div class="auth-card">
            <div class="auth-header">
                <h2>Crear Cuenta</h2>
                <p>Únete para comenzar a cotizar servicios</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
            <div class="alert-error">
                Hubo un problema al crear tu cuenta. Intenta de nuevo.
            </div>
            <?php endif; ?>

            <form class="auth-form" action="../controllers/AuthController.php?action=register" method="POST">
                <div class="input-group">
                    <label for="name">Nombre Completo</label>
                    <input type="text" id="name" name="name" placeholder="Ej. Rodrigo Mejía" required>
                </div>

                <div class="input-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
                </div>

                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Crea una contraseña" required>
                </div>

                <button type="submit" class="submit-btn">Crear Cuenta</button>
            </form>

            <div class="auth-footer">
                ¿Ya tienes una cuenta? <a href="../controllers/AuthController.php?action=showLogin">Inicia sesión</a>
            </div>
        </div>
    </div>

</body>

</html>