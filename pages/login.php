<?php
$basePath = "../";
$pageTitle = "Login";
$headerClass = "scrolled";

include_once $basePath . 'includes/auth.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (loginUser($email, $password)) {
        header("Location: " . $basePath . "index.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}

include $basePath . 'includes/header.php';
?>

<main class="auth-page">
    <div class="container">
        <div class="auth-container fade-in-up">
            <div class="auth-header">
                <h1>Welcome Back</h1>
                <p>Login to your VESTURE account</p>
            </div>

            <?php if ($error): ?>
                <div class="error-msg"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="name@example.com">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Login</button>
            </form>

            <div class="auth-footer">
                <p>Don't have an account? <a href="register.php">Create one</a></p>
            </div>
        </div>
    </div>
</main>

<?php include $basePath . 'includes/footer.php'; ?>
