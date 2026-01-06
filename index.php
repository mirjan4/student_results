<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Results</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="public-page">
        <nav class="public-navbar">
            <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
                <div class="brand-logo">
                    <i class="bi bi-mortarboard-fill"></i>
                    <span>ResultPortal</span>
                </div>
            </div>
        </nav>

        <main class="flex-center min-h-screen" style="background: linear-gradient(135deg, var(--bg-color) 0%, #e0e7ff 100%); margin-top: -64px;">
            <div class="search-card modern-card">
                <div class="text-center mb-4">
                    <div style="width: 64px; height: 64px; background: var(--info-light); color: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2rem;">
                        <i class="bi bi-search"></i>
                    </div>
                    <h1 style="font-size: 1.75rem; margin-bottom: 0.5rem;">Check Your Results</h1>
                    <p style="color: var(--text-muted);">Enter your register number below to view your exam results.</p>
                </div>

                <form action="result.php" method="GET">
                    <div class="mb-4">
                        <label for="register_no" class="form-label">Register Number</label>
                        <div style="position: relative;">
                            <input type="text" class="form-control" id="register_no" name="register_no" required style="padding-left: 3rem;">
                            <i class="bi bi-person-badge" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        <span>View Result</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>