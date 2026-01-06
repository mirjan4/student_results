<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../config.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: dashboard.php');
    exit;
}

// Fetch student
$stmt = $conn->prepare("SELECT * FROM students_results WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
if (!$student) {
    header('Location: dashboard.php');
    exit;
}
$stmt->close();

if (isset($_POST['update'])) {
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

    // Check if register_no exists for another student
    $stmt = $conn->prepare("SELECT id FROM students_results WHERE register_no = ? AND id != ?");
    $stmt->bind_param("si", $register_no, $id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $error = "Register Number already exists.";
    } else {
        // Update
        $stmt = $conn->prepare("UPDATE students_results SET register_no = ?, name = ?, class = ?, sub1_name = ?, sub1_mark = ?, sub2_name = ?, sub2_mark = ?, sub3_name = ?, sub3_mark = ?, sub4_name = ?, sub4_mark = ?, sub5_name = ?, sub5_mark = ?, sub6_name = ?, sub6_mark = ?, sub7_name = ?, sub7_mark = ?, sub8_name = ?, sub8_mark = ?, sub9_name = ?, sub9_mark = ?, sub10_name = ?, sub10_mark = ?, total = ?, result = ? WHERE id = ?");
        $stmt->bind_param("sssssssssssssssssssssssssi", $register_no, $name, $class, $subjects[1]['name'], $subjects[1]['mark'], $subjects[2]['name'], $subjects[2]['mark'], $subjects[3]['name'], $subjects[3]['mark'], $subjects[4]['name'], $subjects[4]['mark'], $subjects[5]['name'], $subjects[5]['mark'], $subjects[6]['name'], $subjects[6]['mark'], $subjects[7]['name'], $subjects[7]['mark'], $subjects[8]['name'], $subjects[8]['mark'], $subjects[9]['name'], $subjects[9]['mark'], $subjects[10]['name'], $subjects[10]['mark'], $total, $result_status, $id);
        if ($stmt->execute()) {
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Error updating student.";
        }
    }
    $stmt->close();
}
$conn->close();

$page_title = 'Edit Student';
$nav_active = 'students';
$show_search = false;

include __DIR__ . '/includes/header.php';
?>

<div class="page-content">
    <div class="container-fluid p-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Edit Student Result</h3>
            <a href="dashboard.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger mb-4"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="card card-form">
            <form method="POST">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label for="register_no" class="form-label">Register Number</label>
                        <input type="text" class="form-control" id="register_no" name="register_no" value="<?php echo htmlspecialchars($student['register_no']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="name" class="form-label">Student Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="class" class="form-label">Class</label>
                        <input type="text" class="form-control" id="class" name="class" value="<?php echo htmlspecialchars($student['class']); ?>" required>
                    </div>
                </div>

                <h5 class="mt-5 mb-4 pb-2 border-bottom">Subject Marks</h5>
                 <div class="row">
                    <?php for ($i = 1; $i <= 10; $i++): ?>
                        <div class="col-md-6 mb-3">
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="row g-3">
                                    <div class="col-7">
                                        <label for="sub<?php echo $i; ?>_name" class="form-label small text-muted">Subject <?php echo $i; ?></label>
                                        <input type="text" class="form-control form-control-sm" id="sub<?php echo $i; ?>_name" name="sub<?php echo $i; ?>_name" value="<?php echo htmlspecialchars($student['sub' . $i . '_name'] ?? ''); ?>">
                                    </div>
                                    <div class="col-5">
                                        <label for="sub<?php echo $i; ?>_mark" class="form-label small text-muted">Mark</label>
                                        <input type="text" class="form-control form-control-sm" id="sub<?php echo $i; ?>_mark" name="sub<?php echo $i; ?>_mark" value="<?php echo $student['sub' . $i . '_mark'] ?? ''; ?>">
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
                            <option value="">Not Published</option>
                            <option value="PASS" <?php echo $student['result'] == 'PASS' ? 'selected' : ''; ?>>PASS</option>
                            <option value="FAIL" <?php echo $student['result'] == 'FAIL' ? 'selected' : ''; ?>>FAIL</option>
                        </select>
                    </div>
                </div>

                <div class="mt-5 d-flex gap-2">
                    <button type="submit" name="update" class="btn btn-primary px-4">Update Changes</button>
                    <a href="dashboard.php" class="btn btn-light border px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>