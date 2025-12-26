<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Customer Support Portal</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/images/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">


  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
	
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<meta name="description" content="A short description." />

	<meta name="keywords" content="put, keywords, here" />

	<link rel="stylesheet" href="style.css" type="text/css">


  <script src="assets/js/jquery.min.js"></script>
<link rel="stylesheet" href="assets/DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css"></style>
<script type="text/javascript" src="assets/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	

</head>

<body >

<header class="header fixed-top">
  <div class="sitename">
    <img src="assets/images/bellogo1.png" alt="BEL Logo" class="logo">
    Customer Service Portal    
  </div>
  <nav class="navmenu">
    <ul>
      <li><a href="project_review.php"><b>HOME</b></a></li>
      <!-- <li><a href="add_project_db.php"><b>ADD NEW</b></a></li> -->
      <!-- <li><b style="color:khaki;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></b></li> -->
      <li><a href="logout.php"><b>LOGOUT</b></a></li>
    </ul>
  </nav>
</header>
<style>
  html, body {
  width: 100%;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  background-color: #E6F0FA;
  font-family: 'Segoe UI', sans-serif;
}

  .header {
    background-color: #002147;
    height: 60px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
  }
   .sitename {
      display: flex;
      align-items: center;
      gap: 3px;
      color: #FFD700;
      font-size: 1.2rem;
    }

    .logo {
      height: 50px;
      width: 200px;
      display: inline-block;
    }
  .navmenu ul {
    list-style: none;
    display: flex;
    gap: 2px;
  }
  .navmenu a {
    color: white;
    text-decoration: none;
  }
  .navmenu a:hover {
    color: #FFD700;
  }
  .modal-title {
    color: white;
  }
  </style>
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
        'project_name', 'project_desc', 'customer_name', 'ticket_desc', 'start_date', 'end_date', 'warranty_from',
        'warranty_end', 'owner', 'staff_number', 'status'
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
    form { max-width: 800px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    label { font-weight: bold; }
    input, textarea, select { width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px; }
    button { background-color: #004080; color: white; padding: 10px 20px; border: none; border-radius: 5px; }
    button:hover { background-color: #002147; }
  </style>
</head>
<body>
  <form method="post">
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
    <input type="date" name="start_date" value="<?= $project['start_date'] ?>" required>

    <label>End Date:</label>
    <input type="date" name="end_date" value="<?= $project['end_date'] ?>" required>

    <label>Warranty From:</label>
    <input type="date" name="warranty_from" value="<?= $project['warranty_from'] ?>">

    <label>Warranty End:</label>
    <input type="date" name="warranty_end" value="<?= $project['warranty_end'] ?>">

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

    <button type="submit" name="submit">Update Project</button>
  </form>
</body>
</html>
