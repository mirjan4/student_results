<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../config.php';

if (isset($_POST['add'])) {
    $register_no = trim($_POST['register_no']);
    $name = trim($_POST['name']);
    $class = trim($_POST['class']);
    $subjects = [];
    for ($i = 1; $i <= 10; $i++) {
        $subjects[$i]['name'] = !empty($_POST['sub' . $i . '_name']) ? trim($_POST['sub' . $i . '_name']) : null;
        $subjects[$i]['mark'] = !empty($_POST['sub' . $i . '_mark']) ? floatval($_POST['sub' . $i . '_mark']) : null;
    }
    $result_status = !empty($_POST['result']) ? $_POST['result'] : null;

    // Calculate total
    $total = 0;
    foreach ($subjects as $subject) {
        if ($subject['mark'] !== null) {
            $total += $subject['mark'];
        }
    }
    $total = $total > 0 ? $total : null;

    // Check if register_no exists
    $stmt = $conn->prepare("SELECT id FROM students_results WHERE register_no = ?");
    $stmt->bind_param("s", $register_no);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $error = "Register Number already exists.";
    } else {
        // Insert
        // Use a simpler query construction if possible or stick to the massive one.
        // Sticking to original logic to ensure correctness.
        $stmt = $conn->prepare("INSERT INTO students_results (register_no, name, class, sub1_name, sub1_mark, sub2_name, sub2_mark, sub3_name, sub3_mark, sub4_name, sub4_mark, sub5_name, sub5_mark, sub6_name, sub6_mark, sub7_name, sub7_mark, sub8_name, sub8_mark, sub9_name, sub9_mark, sub10_name, sub10_mark, total, result) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssssssssssssssss", $register_no, $name, $class, $subjects[1]['name'], $subjects[1]['mark'], $subjects[2]['name'], $subjects[2]['mark'], $subjects[3]['name'], $subjects[3]['mark'], $subjects[4]['name'], $subjects[4]['mark'], $subjects[5]['name'], $subjects[5]['mark'], $subjects[6]['name'], $subjects[6]['mark'], $subjects[7]['name'], $subjects[7]['mark'], $subjects[8]['name'], $subjects[8]['mark'], $subjects[9]['name'], $subjects[9]['mark'], $subjects[10]['name'], $subjects[10]['mark'], $total, $result_status);
        if ($stmt->execute()) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Error adding student.";
        }
    }
    $stmt->close();
}
$conn->close();

$page_title = 'Add Student';
$nav_active = 'students';
$show_search = false;

include __DIR__ . '/includes/header.php';
?>

<div class="page-content">
    <div class="container-fluid p-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Add New Student</h3>
            <a href="dashboard.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger mb-4 rounded-3 shadow-sm border-0"><i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error; ?></div>
        <?php endif; ?>

        <div class="card card-form">
            <form method="POST">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label for="register_no" class="form-label">Register Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="register_no" name="register_no" required >
                    </div>
                    <div class="col-md-4">
                        <label for="name" class="form-label">Student Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required >
                    </div>
                    <div class="col-md-4">
                        <label for="class" class="form-label">Class <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="class" name="class" required >
                    </div>
                </div>

                <h5 class="mt-5 mb-4 pb-2 border-bottom">Subject Marks</h5>
                
                <div class="row">
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="row g-3">
                                    <div class="col-7">
                                        <label for="sub<?php echo $i; ?>_name" class="form-label small text-muted">Sub <?php echo $i; ?></label>
                                        <input type="text" class="form-control form-control-sm" id="sub<?php echo $i; ?>_name" name="sub<?php echo $i; ?>_name" placeholder="Subject Name">
                                    </div>
                                    <div class="col-5">
                                        <label for="sub<?php echo $i; ?>_mark" class="form-label small text-muted">Mark</label>
                                        <input type="text" class="form-control form-control-sm" id="sub<?php echo $i; ?>_mark" name="sub<?php echo $i; ?>_mark" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4">
                        <label for="result" class="form-label">Result Status</label>
                        <select class="form-select" id="result" name="result">
                            <option value="">Not Published / Pending</option>
                            <option value="PASS" class="text-success fw-bold">PASS</option>
                            <option value="FAIL" class="text-danger fw-bold">FAIL</option>
                        </select>
                    </div>
                </div>

                <div class="mt-5 d-flex gap-2">
                    <button type="submit" name="add" class="btn btn-primary px-4"> Save Student Result</button>
                    <a href="dashboard.php" class="btn btn-light border px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>