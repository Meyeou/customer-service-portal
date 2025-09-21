<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Fetch existing project data
if (!isset($_GET['id'])) {
    echo "Project ID not provided.";
    exit;
}

$project_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM project_db WHERE id = ?");
$stmt->bind_param("i", $project_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Project not found.";
    exit;
}

$project = $result->fetch_assoc();

// Handle form submission
if (isset($_POST['submit'])) {
    $fields = [
        'project_name', 'project_desc', 'customer_name', 'ticket_desc',
        'start_date', 'end_date', 'warranty_from', 'warranty_end',
        'owner', 'staff_number', 'status'
    ];

    $data = [];
    foreach ($fields as $field) {
        $data[$field] = htmlspecialchars($_POST[$field] ?? '');
    }

    $stmt = $conn->prepare("UPDATE project_db SET 
        project_name = ?,
        project_desc = ?,
        customer_name = ?,
        ticket_desc = ?,
        start_date = ?,
        end_date = ?,
        warranty_from = ?,
        warranty_end = ?,
        owner = ?,
        staff_number = ?,
        status = ?
        WHERE id = ?");

    $stmt->bind_param(
        "sssssssssssi",
        $data['project_name'],
        $data['project_desc'],
        $data['customer_name'],
        $data['ticket_desc'],
        $data['start_date'],
        $data['end_date'],
        $data['warranty_from'],
        $data['warranty_end'],
        $data['owner'],
        $data['staff_number'],
        $data['status'],
        $project_id
    );

    if ($stmt->execute()) {
        header("Location: project_review.php?msg=updated");
        exit;
    } else {
        echo "Error updating project: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Project</title>
  <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
  <style>
    body { background-color: #E6F0FA; font-family: 'Segoe UI', sans-serif; }
    form { max-width: 800px; margin: 80px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    label { font-weight: bold; }
    input, textarea, select { width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px; }
    button { background-color: #004080; color: white; padding: 10px 20px; border: none; border-radius: 5px; }
    button:hover { background-color: #002147; }
    .error { color: red; font-size: 14px; display: none; }
  </style>
</head>
<body>
  <form method="post" id="updateForm">
    <h3>Update Project Details</h3>

    <label>Project Name:</label>
    <input type="text" name="project_name" value="<?= htmlspecialchars($project['project_name']) ?>" required>

    <label>Project Description:</label>
    <textarea name="project_desc" required><?= htmlspecialchars($project['project_desc']) ?></textarea>

    <label>Customer Name:</label>
    <input type="text" name="customer_name" value="<?= htmlspecialchars($project['customer_name']) ?>" required>

    <label>Ticket Description:</label>
    <textarea name="ticket_desc" required><?= htmlspecialchars($project['ticket_desc']) ?></textarea>

    <label>Start Date:</label>
    <input type="date" id="start_date" name="start_date" value="<?= $project['start_date'] ?>" required>

    <label>End Date:</label>
    <input type="date" id="end_date" name="end_date" value="<?= $project['end_date'] ?>" required>

    <label>Warranty From:</label>
    <input type="date" id="warranty_from" name="warranty_from" value="<?= $project['warranty_from'] ?>">

    <label>Warranty End:</label>
    <input type="date" id="warranty_end" name="warranty_end" value="<?= $project['warranty_end'] ?>">

    <label>Project Owner:</label>
    <input type="text" name="owner" value="<?= htmlspecialchars($project['owner']) ?>" required>

    <label>Project Owner Staff Number:</label>
    <input type="number" name="staff_number" value="<?= htmlspecialchars($project['staff_number']) ?>" required>

    <label>Status:</label>
    <select name="status" required>
      <option value="Ongoing" <?= $project['status'] === 'Ongoing' ? 'selected' : '' ?>>Ongoing</option>
      <option value="Completed" <?= $project['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
      <option value="On Hold" <?= $project['status'] === 'On Hold' ? 'selected' : '' ?>>On Hold</option>
    </select>

    <p id="dateError" class="error">⚠️ Please fix date inconsistencies before submitting.</p>

    <button type="submit" name="submit">Update Project</button>
  </form>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const start = document.getElementById("start_date");
  const end = document.getElementById("end_date");
  const warrantyFrom = document.getElementById("warranty_from");
  const warrantyEnd = document.getElementById("warranty_end");
  const errorMsg = document.getElementById("dateError");
  const form = document.getElementById("updateForm");

  function validateDates() {
    errorMsg.style.display = "none";

    if (start.value && end.value && end.value < start.value) {
      errorMsg.style.display = "block";
      return false;
    }
    if (start.value && warrantyFrom.value && warrantyFrom.value < start.value) {
      errorMsg.style.display = "block";
      return false;
    }
    if (warrantyFrom.value && warrantyEnd.value && warrantyEnd.value < warrantyFrom.value) {
      errorMsg.style.display = "block";
      return false;
    }
    return true;
  }

  // Update constraints dynamically
  start.addEventListener("change", function () {
    if (start.value) {
      end.min = start.value;
      warrantyFrom.min = start.value;
    }
  });

  warrantyFrom.addEventListener("change", function () {
    if (warrantyFrom.value) {
      warrantyEnd.min = warrantyFrom.value;
    }
  });

  form.addEventListener("submit", function (e) {
    if (!validateDates()) {
      e.preventDefault();
    }
  });
});
</script>

</body>
</html>