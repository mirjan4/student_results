<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../config.php';

// Fetch statistics
$total_students = $conn->query("SELECT COUNT(*) as count FROM students_results")->fetch_assoc()['count'];
$attended = $conn->query("SELECT COUNT(*) as count FROM students_results WHERE result IS NOT NULL")->fetch_assoc()['count'];
$absent = $total_students - $attended;
$passed = $conn->query("SELECT COUNT(*) as count FROM students_results WHERE result = 'PASS'")->fetch_assoc()['count'];
$failed = $conn->query("SELECT COUNT(*) as count FROM students_results WHERE result = 'FAIL'")->fetch_assoc()['count'];

// Fetch students with optional filters
$where = "1";
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$class_filter = isset($_GET['class']) ? trim($_GET['class']) : '';

if ($search) {
    $where .= " AND (name LIKE '%$search%' OR register_no LIKE '%$search%')";
}
if ($status_filter && $status_filter != 'all') {
    $where .= " AND result = '$status_filter'";
}
if ($class_filter) {
    $where .= " AND class = '$class_filter'";
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$total_results = $conn->query("SELECT COUNT(*) as count FROM students_results WHERE $where")->fetch_assoc()['count'];
$total_pages = ceil($total_results / $limit);

$result = $conn->query("SELECT * FROM students_results WHERE $where ORDER BY created_at DESC LIMIT $offset, $limit");
$students = $result->fetch_all(MYSQLI_ASSOC);

// Get unique classes for filter
$classes_result = $conn->query("SELECT DISTINCT class FROM students_results ORDER BY class");
$classes = $classes_result->fetch_all(MYSQLI_ASSOC);

$conn->close();

// Page settings for header
$page_title = 'Dashboard';
$nav_active = 'dashboard';
$show_search = true;
$search_value = $search;

include __DIR__ . '/includes/header.php';
?>

<div class="page-content">
    


    <!-- Stats Grid -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-total">
                <div class="stat-info">
                    <h3><?php echo $total_students; ?></h3>
                    <p>Total Students</p>
                </div>
                <div class="stat-icon-grid">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-pass">
                <div class="stat-info">
                    <h3><?php echo $passed; ?></h3>
                    <p>Students Passed</p>
                </div>
                <div class="stat-icon-grid">
                    <i class="bi bi-check-lg"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-fail">
                <div class="stat-info">
                    <h3><?php echo $failed; ?></h3>
                    <p>Students Failed</p>
                </div>
                <div class="stat-icon-grid">
                    <i class="bi bi-x-lg"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-absent">
                <div class="stat-info">
                    <h3><?php echo $absent; ?></h3>
                    <p>Absent / Pending</p>
                </div>
                <div class="stat-icon-grid">
                    <i class="bi bi-exclamation-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Table -->
    <div class="table-card">
        <div class="table-header">
            <h5 class="mb-0 fw-bold">Student Results</h5>
            <form method="GET" class="d-flex gap-2 flex-wrap" id="filterForm">
                <select name="status" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                    <option value="all">All Status</option>
                    <option value="PASS" <?php if ($status_filter == 'PASS') echo 'selected'; ?>>Passed</option>
                    <option value="FAIL" <?php if ($status_filter == 'FAIL') echo 'selected'; ?>>Failed</option>
                </select>
                <select name="class" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                    <option value="">All Classes</option>
                    <?php foreach ($classes as $cls): ?>
                        <option value="<?php echo htmlspecialchars($cls['class']); ?>" <?php if ($class_filter == $cls['class']) echo 'selected'; ?>><?php echo htmlspecialchars($cls['class']); ?></option>
                    <?php endforeach; ?>
                </select>
                 <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
            </form>
        </div>
        
        <div class="table-responsive">
            <table class="table custom-table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Rg No</th>
                        <th>Class</th>
                        <th>Status</th>
                        <th>Total Score</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($students) > 0): ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td class="fw-medium">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px; font-size: 0.8rem; font-weight: bold; color: var(--primary-color);">
                                            <?php echo strtoupper(substr($student['name'], 0, 1)); ?>
                                        </div>
                                        <?php echo htmlspecialchars($student['name']); ?>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($student['register_no']); ?></td>
                                <td><?php echo htmlspecialchars($student['class']); ?></td>
                                <td>
                                    <?php if ($student['result'] == 'PASS'): ?>
                                        <span class="status-badge badge-pass"><i class="bi bi-check-circle-fill"></i> PASS</span>
                                    <?php elseif ($student['result'] == 'FAIL'): ?>
                                        <span class="status-badge badge-fail"><i class="bi bi-x-circle-fill"></i> FAIL</span>
                                    <?php else: ?>
                                        <span class="status-badge badge-pending">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold"><?php echo $student['total'] ? number_format($student['total'], 1) : '-'; ?></td>
                                <td class="text-end">
                                    <a href="edit.php?id=<?php echo $student['id']; ?>" class="btn btn-action btn-outline-primary btn-sm">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-search display-6 mb-3 d-block"></i>
                                No results found matching your criteria.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="px-4 py-3 border-top d-flex justify-content-between align-items-center">
            <div class="text-muted small">Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $limit, $total_results); ?> of <?php echo $total_results; ?> results</div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                     <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status_filter); ?>&class=<?php echo urlencode($class_filter); ?>"><?php echo $i; ?></a>
                        </li>
                     <?php endfor; ?>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>