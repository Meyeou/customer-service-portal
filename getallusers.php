<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
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

<header class="header fixed-top">
  <div class="sitename">
    <img src="assets/images/bellogo1.png" alt="BEL Logo" class="logo">
    Customer Service Portal    
  </div>
  <nav class="navmenu">
    <ul>
      <li><a href="project_review.php"><b>HOME</b></a></li>
      <li><a href="signup.php"><b>CREATE USER</b></a></li>
      <li><b style="color:khaki;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></b></li>
      <li><a href="logout.php"><b>LOGOUT</b></a></li>
    </ul>
  </nav>
</header>

<style>
body {
  background-color: #E6F0FA;
  font-family: 'Segoe UI', sans-serif;
  padding-top: 80px;
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
  width: 90%;
  margin: 30px auto;
  border-collapse: collapse;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  background-color: #ffffff;
  font-size: 14px;
}
th, td {
  padding: 10px;
  border: 1px solid #ccc;
}
th {
  background-color: #004080;
  color: white;
}
tbody tr:hover {
  background-color: #d3e4f0;
}

table, #myTable {
  width: 100% !important;
  border-collapse: collapse;
  background-color: #ffffff;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  font-size: 14px;
}

/* Table header */
#myTable thead th {
  background-color: #004080;
  color: white;
  padding: 12px;
  border: 1px solid #D0D7E1;
}

/* Table body */
#myTable tbody td {
  background-color: #F9FBFF;
  color: #333;
  padding: 10px;
  border: 1px solid #D0D7E1;
}

/* Zebra rows */
#myTable tbody tr:nth-child(even) {
  background-color: #EDF4FA;
}

/* Hover effect */
#myTable tbody tr:hover {
  background-color: #D6E8FF;
}

</style>
</head>


<body>
<div class="container">
  <table id="myTable" class="table table-striped">
    <thead>
      <tr>
        <th>Sl.No</th>
        <th>Name</th>
        <th>Staff Number</th>
        <th>E-Mail</th>
        <th>Role</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT * FROM users";
      $result = mysqli_query($conn, $sql);
      $serial = 1;

      if (mysqli_num_rows($result) == 0) {
          echo "<tr><td colspan='6' class='text-center'>No users found</td></tr>";
      }

      while ($row = $result->fetch_assoc()) {
          echo "<tr>
            <td>{$serial}</td>
            <td>{$row['username']}</td>
            <td>{$row['staff_number']}</td>
            <td>{$row['email']}</td>
            <td>{$row['role']}</td>
            <td>
              <button class='btn btn-success btn-sm' onclick='confirmReset({$row['id']})'>Reset</button>
              <button class='btn btn-danger btn-sm' onclick='confirmDelete({$row['id']})'>Delete</button>
            </td>
          </tr>";
          $serial++;
      }
      ?>
    </tbody>
  </table>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this user?
      </div>
      <div class="modal-footer">
        <form method="post" action="delete_user.php">
          <input type="hidden" name="id" id="delete_id">
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="assets/js/bootstrap.bundle.min.js"></script>
<!-- <script src="assets/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script> -->
<script>
function confirmDelete(id) {
  document.getElementById('delete_id').value = id;
  const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
  modal.show();
}

// function confirmReset(id) {
//   if (confirm("Are you sure you want to reset this user's password to 'Bel@1234'?")) {
//     $.post('reset_password.php', { id: id }, function(response) {
//       if (response.trim() === "success") {
//         alert("Password reset to 'Bel@1234'");
//       } else {
//         alert("Reset failed");
//       }
//     });
//   }
// }
function confirmReset(id) {
  if (confirm("Are you sure you want to reset this user's password to 'Bel@1234'?")) {
    $.post('reset_password.php', { id: id }, function(response) {
      console.log("Response:", response); // helpful for debugging
      if (response.trim() === "success") {
        alert("Password reset to 'Bel@1234'");
      } else {
        alert("Reset failed: " + response);
      }
    });
  }
}


$(document).ready(function () {
  $('#myTable').DataTable({
    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
    "pageLength": 10
  });
});
</script>
</body>
</html>
