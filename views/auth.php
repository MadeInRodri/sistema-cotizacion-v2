<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | LEO'S && RODRI'S</title>
    <link rel="stylesheet" href="../public/assets/css/services-catalog.css">
    <link rel="stylesheet" href="../public/assets/css/auth.css">
</head>

<body>

    <div class="auth-wrapper">
        <div class="grid-background"></div>

        <div class="auth-card">
            <div class="auth-header">
                <h2>Bienvenido de nuevo</h2>
                <p>Ingresa tus credenciales para continuar</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
            <div class="alert-error">
                Correo o contraseña incorrectos.
            </div>
            <?php endif; ?>

            <form class="auth-form" action="../controllers/AuthController.php?action=login" method="POST">
                <div class="input-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required>
                </div>

                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="submit-btn">Iniciar Sesión</button>
            </form>

            <div class="auth-footer">
                ¿No tienes una cuenta? <a href="../controllers/AuthController.php?action=showRegister">Regístrate
                    aquí</a>
            </div>
        </div>
    </div>

</body>

</html>