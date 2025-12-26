<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if (!isset($_SESSION['staff_number'])) {
    $stmt = $conn->prepare("SELECT staff_number FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($staff_number);
    $stmt->fetch();
    $stmt->close();
    $_SESSION['staff_number'] = $staff_number;
} else {
    $staff_number = $_SESSION['staff_number'];
}

if ($role === 'admin') {
    $stmt = $conn->prepare("SELECT * FROM project_db");
} else {
    $stmt = $conn->prepare("SELECT * FROM project_db WHERE staff_number = ?");
    $stmt->bind_param("s", $staff_number);
}

$stmt->execute();
$result = $stmt->get_result();

$statusCounts = ['Ongoing' => 0, 'Completed' => 0, 'On Hold' => 0];
$allProjects = [];

while ($row = $result->fetch_assoc()) {
    $status = $row['status'];
    if (isset($statusCounts[$status])) {
        $statusCounts[$status]++;
    }
    $allProjects[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Project Review</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css">

  <script src="assets/js/chart.js"></script>
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/jquery-3.6.0.min.js"></script>
  <script src="assets/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="assets/DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css">
  <script type="text/javascript" src="assets/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="assets/js/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="assets/js/jquery.dataTables.min.js"></script>

  <style>
    body {
      background-color: #E6F0FA;
      font-family: 'Segoe UI', sans-serif;
      width: 100%;
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
    }
    .navmenu ul {
      list-style: none;
      display: flex;
      gap: 10px;
    }
    .navmenu a {
      color: white;
      text-decoration: none;
    }
    .navmenu a:hover {
      color: #FFD700;
    }
    .user-dropdown {
      position: relative;
      display: inline-block;
    }
    .user-dropdown .dropdown-content {
      display: none;
      position: absolute;
      background-color: white;
      min-width: 160px;
      box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
      z-index: 1;
      right: 0;
      top: 35px;
    }
    .user-dropdown:hover .dropdown-content {
      display: block;
    }
    .user-dropdown .dropdown-content a {
      color: black;
      padding: 8px 16px;
      text-decoration: none;
      display: block;
    }
    .user-dropdown .dropdown-content a:hover {
      background-color: #f1f1f1;
    }

    #myTable {
      width: 99% !important;
      margin: 30px auto;
      background-color: #ffffff;
      font-size: 14px;
      border-collapse: collapse;
    }

    #myTable thead th {
      background-color: #004080;
      color: white;
      padding: 12px;
      border: 1px solid #ccc;
    }

    #myTable tbody td {
      padding: 10px;
      border: 1px solid #ccc;
    }

    #myTable tbody tr:nth-child(even) {
      background-color: #EDF4FA;
    }

    #myTable tbody tr:hover {
      background-color: #D6E8FF;
    }

    .container {
      padding: 15px;
      margin-top: 95px;
      /* width: 95%; */
    }

    #statusPieChart {
      width: 250px !important; /* 3 cm ≈ 114px */
      height: 250px !important;
      display: block;
      margin: 0 auto;
    }

    .header-section.center {
  font-size: 1.6rem;
  font-weight: 700; /* bold */
  color: #FFD700;
  text-align: center;
}


  </style>
</head>




<body>
<header class="header fixed-top">
  <div class="sitename">
    <img src="assets/images/bellogo1.png" alt="BEL Logo" class="logo">
    <div class="header-section center">
    Customer Service Portal
  </div>  
  </div>
  <nav class="navmenu">
    <ul>
      <li><a href="project_review.php"><b>HOME</b></a></li>
      <li><a href="add_project_details.php"><b>ADD NEW</b></a></li>
      <li>
        <?php if ($_SESSION['role'] === 'admin') {
          echo "<a href='getallusers.php'> <b>USER MANAGEMENT</b></a>";
        } ?>
      </li>
      <li class="user-dropdown">
        <span style="color:khaki; cursor: pointer;">
          <i class="bi bi-person-circle" style="font-size: 1.4rem;"></i>
          <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>
        </span>
        <ul class="dropdown-content">
          <li><a href="change_password.php">Change Password</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </li>
    </ul>
  </nav>
</header>

<div class="container">
  <!-- Pie Chart and Summary inside container -->
  <div class="row justify-content-center mb-4">
    <div class="col-md-4 text-center">
      <h5 class="text-primary">Project Status Overview</h5>
      <canvas id="statusPieChart"></canvas>
    </div>
    <div class="col-md-4 mt-4">
      <h6 class="text-primary">Summary</h6>
      <ul class="list-group">
        <li class="list-group-item d-flex justify-content-between align-items-center">
          Total Projects
          <span class="badge bg-primary rounded-pill"><?php echo array_sum($statusCounts); ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          Ongoing
          <span class="badge bg-success rounded-pill"><?php echo $statusCounts['Ongoing']; ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          Completed
          <span class="badge bg-primary rounded-pill"><?php echo $statusCounts['Completed']; ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          On Hold
          <span class="badge bg-danger rounded-pill"><?php echo $statusCounts['On Hold']; ?></span>
        </li>
      </ul>
    </div>
  </div>
   </div>

  <!-- Project Table -->
  <table id="myTable" class="table table-bordered table-striped table-hover">
    <thead>
      <tr>
        <th>Sl.No</th>
        <th>Project Name</th>
        <th>Project Description</th>
        <th>Customer Name</th>
        <th>Ticket Description</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Warranty From</th>
        <th>Warranty End</th>
        <th>Owner</th>
        <th>Status</th>
        <th>Modify/Delete</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $serial = 1;
        foreach ($allProjects as $row) {
          echo "<tr>
            <td>{$serial}</td>
            <td>{$row['project_name']}</td>
            <td>{$row['project_desc']}</td>
            <td>{$row['customer_name']}</td>
            <td>{$row['ticket_desc']}</td>
            <td>{$row['start_date']}</td>
            <td>{$row['end_date']}</td>
            <td>{$row['warranty_from']}</td>
            <td>{$row['warranty_end']}</td>
            <td>{$row['owner']}</td>
            <td>{$row['status']}</td>
            <td>
              <a href='update_details.php?id={$row['id']}' class='btn btn-outline-primary btn-sm' title='Update'>
                <i class='bi bi-pencil-square'></i>
              </a>
              <button class='btn btn-outline-danger btn-sm' onclick='confirmDelete({$row['id']})' title='Delete'>
                <i class='bi bi-trash'></i>
              </button>
            </td>
          </tr>";
          $serial++;
        }
      ?>
    </tbody>
  </table>


<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">Confirm Deletion</h5>
      </div>
      <div class="modal-body">Are you sure you want to delete this project?</div>
      <div class="modal-footer">
        <form method="post" action="delete_details.php">
          <input type="hidden" name="sl_no" id="delete_id">
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
        <button type="button" class="btn btn-secondary" onclick="window.location.href='project_review.php'">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script>
function confirmDelete(id) {
  document.getElementById('delete_id').value = id;
  const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
  deleteModal.show();
}

$(document).ready(function () {
  $('#myTable').DataTable({
    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
    "pageLength": 10
  });
});

const ctx = document.getElementById('statusPieChart').getContext('2d');
const statusChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: ['Ongoing', 'Completed', 'On Hold'],
    datasets: [{
      label: 'Project Status',
      data: [
        <?php echo $statusCounts['Ongoing']; ?>,
        <?php echo $statusCounts['Completed']; ?>,
        <?php echo $statusCounts['On Hold']; ?>
      ],
      backgroundColor: ['#28a745', '#007bff', '#dc3545'],
      borderColor: '#fff',
      borderWidth: 1
    }]
  },
  options: {
    responsive: false,
    plugins: {
      legend: {
        position: 'bottom',
        labels: { color: '#333' }
      }
    }
  }
});
</script>

</body>
</html>
