<?php
session_start();
include '../config.php';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Simple hardcoded credentials (should be in DB in production)
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Student Results</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="login-page">
    <div class="login-card text-center">
        <div class="mb-4">
            <i class="bi bi-mortarboard-fill brand-icon"></i>
            <h2 class="fw-bold mb-1">Welcome Back</h2>
            <p class="text-muted">Sign in to manage student results</p>
        </div>
        
        <form method="POST" class="text-start">
            <div class="mb-3">
                <label for="username" class="form-label text-muted small fw-bold text-uppercase">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-person text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" id="username" name="username" placeholder="Enter username" required>
                </div>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label text-muted small fw-bold text-uppercase">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" class="form-control border-start-0 ps-0" id="password" name="password" placeholder="Enter password" required>
                </div>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger py-2 small mb-3">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <button type="submit" name="login" class="btn btn-primary w-100">Sign In</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>