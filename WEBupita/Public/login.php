<?php
// pages/login.php - Sistema de login optimizado para IONOS

// Iniciar sesión
session_start();

// Redireccionar si ya está logueado
if (isset($_SESSION['usuario_id'])) {
    header("Location: ../index.php");
    exit;
}

// Incluir conexión a la base de datos
require_once __DIR__ . '/../includes/conexion.php';

$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    // Validaciones básicas
    if (empty($email) || empty($password)) {
        $mensaje = "Por favor, complete todos los campos.";
        $tipo_mensaje = "error";
    } else {
        try {
            // Buscar usuario en la base de datos
            $stmt = $pdo->prepare("SELECT id, nombre, email, password FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($password, $usuario['password'])) {
                // Login exitoso
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['login_time'] = time();

                // Cookie "recordarme" (opcional)
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true); // 30 días
                    
                    // Guardar token en BD (opcional, para mayor seguridad)
                    $stmt = $pdo->prepare("UPDATE usuarios SET remember_token = ? WHERE id = ?");
                    $stmt->execute([$token, $usuario['id']]);
                }

                // Redireccionar a la página solicitada o al inicio
                $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '../index.php';
                header("Location: " . $redirect);
                exit;
            } else {
                $mensaje = "Email o contraseña incorrectos.";
                $tipo_mensaje = "error";
            }
        } catch (PDOException $e) {
            error_log('Error en login: ' . $e->getMessage());
            $mensaje = "Error del sistema. Intente nuevamente.";
            $tipo_mensaje = "error";
        }
    }
}

$page_title = "Iniciar Sesión";
require_once __DIR__ . '/../includes/header.php';
?>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="login-icon">🔑</div>
            <h1>Iniciar Sesión</h1>
            <p>Accede a tu cuenta de UPIITA Finder</p>
        </div>

        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <form action="login.php<?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>" method="POST" class="login-form">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       required 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                       placeholder="tu.email@ejemplo.com"
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <div class="password-field">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           placeholder="Tu contraseña"
                           class="form-control">
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        👁️
                    </button>
                </div>
            </div>

            <div class="form-group form-options">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" value="1">
                    <span class="checkmark"></span>
                    Recordarme
                </label>
                <a href="recuperar-password.php" class="forgot-link">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                Iniciar Sesión
            </button>
        </form>

        <div class="login-footer">
            <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
            <div class="demo-info">
                <small>
                    <strong>Cuenta de prueba:</strong><br>
                    Email: test@upiita.mx<br>
                    Contraseña: test123
                </small>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<style>
.login-container {
    min-height: calc(100vh - 140px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.login-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    padding: 40px;
    width: 100%;
    max-width: 450px;
    animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.login-header {
    text-align: center;
    margin-bottom: 30px;
}

.login-icon {
    font-size: 3rem;
    margin-bottom: 15px;
}

.login-header h1 {
    color: #333;
    margin: 0 0 10px 0;
    font-size: 2rem;
}

.login-header p {
    color: #666;
    margin: 0;
}

.alert {
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
}

.alert-error {
    background: #fee;
    color: #c33;
    border: 1px solid #fcc;
}

.alert-success {
    background: #efe;
    color: #3c3;
    border: 1px solid #cfc;
}

.login-form {
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.password-field {
    position: relative;
}

.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1.2rem;
    opacity: 0.7;
}

.password-toggle:hover {
    opacity: 1;
}

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: 0.9rem;
    margin: 0;
}

.checkbox-label input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 18px;
    height: 18px;
    border: 2px solid #ddd;
    border-radius: 3px;
    margin-right: 8px;
    position: relative;
    transition: all 0.3s ease;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark {
    background: #007bff;
    border-color: #007bff;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark::after {
    content: '✓';
    position: absolute;
    color: white;
    font-size: 12px;
    top: -1px;
    left: 2px;
}

.forgot-link {
    color: #007bff;
    text-decoration: none;
    font-size: 0.9rem;
}

.forgot-link:hover {
    text-decoration: underline;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}

.btn-block {
    width: 100%;
}

.login-footer {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.login-footer p {
    margin: 0 0 15px 0;
    color: #666;
}

.login-footer a {
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
}

.login-footer a:hover {
    text-decoration: underline;
}

.demo-info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
}

.demo-info small {
    color: #666;
    line-height: 1.4;
}

/* Responsive */
@media (max-width: 480px) {
    .login-container {
        padding: 20px 15px;
    }
    
    .login-card {
        padding: 30px 25px;
    }
    
    .form-options {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}
</style>

<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleButton = document.querySelector('.password-toggle');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleButton.textContent = '🙈';
    } else {
        passwordField.type = 'password';
        toggleButton.textContent = '👁️';
    }
}

// Auto-focus en el primer campo
document.addEventListener('DOMContentLoaded', function() {
    const emailField = document.getElementById('email');
    if (emailField && !emailField.value) {
        emailField.focus();
    }
});
</script>