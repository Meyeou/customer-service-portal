<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Customer Support Portal</title>

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body>
<header class="header fixed-top">
  <div class="sitename">
    <img src="assets/images/bellogo1.png" alt="BEL Logo" class="logo">
    Customer Service Portal    
  </div>
  <nav class="navmenu">
    <ul>
      <li><a href="project_review.php"><b>HOME</b></a></li>
      <li><a href="logout.php"><b>LOGOUT</b></a></li>
    </ul>
  </nav>
</header>

<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['submit'])) {
    $fields = [
      'project_name' , 'project_desc' , 'customer_name', 'ticket_desc', 
      'start_date', 'end_date', 'warranty_from', 'warranty_end', 
      'owner','staff_number', 'status'
    ];

    $data = [];
    foreach ($fields as $field) {
        $data[] = htmlspecialchars($_POST[$field] ?? '');
    }

    $data[] = $user_id;

    $placeholders = implode(',', array_fill(0, count($data), '?'));
    $sql = "INSERT INTO project_db (" . implode(',', $fields) . ", created_by) VALUES ($placeholders)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $types = str_repeat('s', count($data) - 1) . 'i';
    $stmt->bind_param($types, ...$data);

    if ($stmt->execute()) {
        header("Location: project_review.php");
        exit;
    } else {
        die("Execute failed: " . $stmt->error);
    }
}
?>

<style>
  body {
    background-color: #E6F0FA;
    font-family: 'Segoe UI', sans-serif;
    color: brown;
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
    gap: 10px;
    color: #FFD700;
    font-size: 1.2rem;
  }

  .logo {
    height: 50px;
    width: 200px;
  }

  .navmenu ul {
    list-style: none;
    display: flex;
    gap: 20px;
  }

  .navmenu a {
    color: white;
    text-decoration: none;
  }

  .navmenu a:hover {
    color: #FFD700;
  }

  table {
    width: 1000px;
    border-collapse: collapse;
    margin-top: 80px;
  }

  th {
    border: 1px solid #000;
    text-align: center;
    padding: 1px;
    background-color: #004080;
    color: white;
  }

  tr, td {
    border: 1px solid #000;
    text-align: center;
    padding: 10px;
  }

  input, select, textarea {
    width: 300px;
    border: 1px solid darkblue;
    border-radius: 5px;
    padding: 5px;
  }

  textarea { height: 60px; }

  button#s {
    background-color: midnightblue;
    color: khaki;
    font-size: 16px;
    border: none;
    border-radius: 6px;
    padding: 10px 30px;
    margin: 20px auto;
    display: block;
    transition: all 0.3s ease-in-out;
  }

  button#s:hover {
    background-color: khaki;
    color: midnightblue;
    cursor: pointer;
  }

  .error {
    color: red;
    font-size: 14px;
  }
</style>  

<form method="post" enctype="multipart/form-data">
  <center>
    <table>
      <tr style="background-color:#DF674D;">
        <th colspan="2">
          <h3 style="color:white;font-family:tahoma;text-align:center;">Project Entry Form</h3>
        </th>
      </tr>

      <tr>
        <td><b>Project Name:</b></td>
        <td><input type="text" name="project_name" required></td>
      </tr>

      <tr>
        <td><b>Project Description:</b></td>
        <td><textarea name="project_desc" required></textarea></td>
      </tr>

      <tr>
        <td><b>Customer Name:</b></td>
        <td><input type="text" name="customer_name" required></td>
      </tr>

      <tr>
        <td><b>Ticket Description:</b></td>
        <td><textarea name="ticket_desc" required></textarea></td>
      </tr>

      <tr>
        <td><b>Start Date:</b></td>
        <td><input type="date" id="start_date" name="start_date" required></td>
      </tr>

      <tr>
        <td><b>End Date:</b></td>
        <td><input type="date" id="end_date" name="end_date" required></td>
      </tr>

      <tr>
        <td><b>Warranty From:</b></td>
        <td><input type="date" id="warranty_from" name="warranty_from"></td>
      </tr>

      <tr>
        <td><b>Warranty End:</b></td>
        <td><input type="date" id="warranty_end" name="warranty_end"></td>
      </tr>

      <tr>
        <td><b>Project Owner:</b></td>
        <td><input type="text" name="owner" required></td>
      </tr>

      <tr>
        <td><b>Owner Staff Number:</b></td>
        <td><input type="number" name="staff_number" maxlength="6" required></td>
      </tr>

      <tr>
        <td><b>Status:</b></td>
        <td>
          <select name="status" required>
            <option value="">Select Status</option>
            <option value="Ongoing">Ongoing</option>
            <option value="Completed">Completed</option>
            <option value="On Hold">On Hold</option>
          </select>
        </td>
      </tr>
    </table>
    <br>
    <button type="submit" id="s" name="submit"><b>SUBMIT</b></button>
  </center>
</form>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const start = document.getElementById("start_date");
  const end = document.getElementById("end_date");
  const warrantyFrom = document.getElementById("warranty_from");
  const warrantyEnd = document.getElementById("warranty_end");

  // update constraints when start date changes
  start.addEventListener("change", function () {
    if (start.value) {
      end.min = start.value;
      warrantyFrom.min = start.value;
    }
  });

  // warranty end must be >= warranty from
  warrantyFrom.addEventListener("change", function () {
    if (warrantyFrom.value) {
      warrantyEnd.min = warrantyFrom.value;
    }
  });
});
</script>

</body>
</html>
