<?php
include 'config.php';

if (!isset($_GET['register_no']) || empty($_GET['register_no'])) {
    header('Location: index.php');
    exit;
}

$register_no = trim($_GET['register_no']);

// Prepare and execute query
$stmt = $conn->prepare("SELECT * FROM students_results WHERE register_no = ?");
$stmt->bind_param("s", $register_no);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $error = "No result found for Register Number: " . htmlspecialchars($register_no);
} else {
    $student = $result->fetch_assoc();
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Result</title>
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
                    <span>Result</span>
                </div>
            </div>
        </nav>

        <main class="min-h-screen" style="background-color: var(--bg-color); padding: 2rem 1rem;">
            <div class="result-card" style="margin-top: 2rem;">
                
                <?php if (isset($error)): ?>
                    <div class="modern-card text-center" style="max-width: 500px; margin: 0 auto;">
                        <div style="width: 64px; height: 64px; background: var(--danger-light); color: var(--danger-text); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2rem;">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <h3 class="mb-3">Result Not Found</h3>
                        <p class="mb-4" style="color: var(--text-muted);"><?php echo $error; ?></p>
                        <a href="index.php" class="btn btn-outline">
                            <i class="bi bi-arrow-left"></i> Back to Search
                        </a>
                    </div>
                <?php else: ?>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4 no-print" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                        <a href="index.php" class="btn btn-outline" style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="bi bi-arrow-left"></i> Back to Search
                        </a>
                        <button onclick="window.print()" class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="bi bi-printer"></i> Print Result
                        </button>
                    </div>

                    <div class="modern-card">
                        <div class="text-center mb-4" style="border-bottom: 1px solid var(--border-color); padding-bottom: 1.5rem;">
                            <h2 style="margin-bottom: 0.5rem;">Mark list</h2>
                        </div>

                        <div class="student-info-grid">
                            <div class="info-item">
                                <label>Student Name</label>
                                <p><?php echo htmlspecialchars($student['name']); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Register Number</label>
                                <p><?php echo htmlspecialchars($student['register_no']); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Class</label>
                                <p><?php echo htmlspecialchars($student['class']); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Result Status</label>
                                <div>
                                    <?php if ($student['result'] == 'PASS'): ?>
                                        <span class="status-badge badge-pass" style="font-size: 0.9rem;"><i class="bi bi-check-circle-fill"></i> PASS</span>
                                    <?php elseif ($student['result'] == 'FAIL'): ?>
                                        <span class="status-badge badge-fail" style="font-size: 0.9rem;"><i class="bi bi-x-circle-fill"></i> FAIL</span>
                                    <?php else: ?>
                                        <span class="status-badge badge-pending" style="font-size: 0.9rem;">Pending</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div style="overflow-x: auto;">
                            <table class="result-table">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th style="width: 150px; text-align: center;">Marks </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 1; $i <= 10; $i++): ?>
                                        <?php if (!is_null($student['sub' . $i . '_name']) && !is_null($student['sub' . $i . '_mark'])): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($student['sub' . $i . '_name']); ?></td>
                                                <td style="text-align: center; font-weight: 500;"><?php echo htmlspecialchars($student['sub' . $i . '_mark']); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    
                                    <?php if (!is_null($student['total'])): ?>
                                        <tr style="background-color: #f8fafc;">
                                            <td style="font-weight: 700;">Grand Total</td>
                                            <td style="text-align: center; font-weight: 700; font-size: 1.1rem; color: var(--primary-color);">
                                                <?php echo htmlspecialchars($student['total']); ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mt-5" style="margin-top: 3rem; color: var(--text-muted); font-size: 0.8rem;">
                            <p>This is a computer generated document. No signature is required.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>