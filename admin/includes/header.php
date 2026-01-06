<?php
if (!isset($page_title)) $page_title = 'Admin Dashboard';
if (!isset($nav_active)) $nav_active = 'dashboard';
if (!isset($show_search)) $show_search = false;
if (!isset($search_value)) $search_value = '';
if (!isset($status_filter)) $status_filter = '';
if (!isset($class_filter)) $class_filter = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> | Student Results</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
</head>
<body>

    <!-- Mobile Overlay -->
    <div class="overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-mortarboard-fill me-2"></i> Admin
        </div>
        <div class="sidebar-menu">
            <a href="dashboard.php" class="nav-item <?php echo $nav_active === 'dashboard' ? 'active' : ''; ?>">
                <i class="bi bi-grid"></i> Dashboard
            </a>
            <a href="add.php" class="nav-item <?php echo $nav_active === 'students' ? 'active' : ''; ?>">
                <i class="bi bi-people"></i> Students
            </a>
            <a href="#" class="nav-item <?php echo $nav_active === 'users' ? 'active' : ''; ?>">
                <i class="bi bi-person-badge"></i> Users
            </a>
            <a href="#" class="nav-item <?php echo $nav_active === 'settings' ? 'active' : ''; ?>">
                <i class="bi bi-gear"></i> Settings
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="d-flex align-items-center">
                <div class="hamburger" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </div>
                
                <?php if ($show_search): ?>
                <div class="header-search d-none d-md-block ms-3">
                    <i class="bi bi-search"></i>
                    <form method="GET" action="">
                        <input type="text" name="search" class="form-control border-0" placeholder="Search students, register no..." value="<?php echo htmlspecialchars($search_value); ?>">
                        <!-- Preserve other filters if they exist -->
                        <?php if($status_filter): ?><input type="hidden" name="status" value="<?php echo htmlspecialchars($status_filter); ?>"><?php endif; ?>
                        <?php if($class_filter): ?><input type="hidden" name="class" value="<?php echo htmlspecialchars($class_filter); ?>"><?php endif; ?>
                    </form>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="dropdown profile-dropdown">
                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="profile-avatar">A</div>
                    <span class="d-none d-md-block">Admin User</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                </ul>
            </div>
        </header>

        <!-- Page Content Wrapper -->
        <!-- The individual page logic will put its content here -->
