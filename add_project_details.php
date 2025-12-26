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
      'project_name' , 'project_desc' , 'customer_name', 'ticket_desc', 'start_date', 'end_date', 'warranty_from',
      'warranty_end', 'owner','staff_number', 'status'

        
    ];

    $data = [];
    foreach ($fields as $field) {
        $data[] = htmlspecialchars($_POST[$field] ?? '');
    }

    $data[] = $user_id; // Add created_by

    $placeholders = implode(',', array_fill(0, count($data), '?'));

    $sql = "INSERT INTO project_db (" . implode(',', $fields) . ", created_by) VALUES ($placeholders)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $types = str_repeat('s', count($data) - 1) . 'i'; // Last is user_id as int

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
      display: inline-block;
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
  #bg{
  background:url(bg.gif);
  background-repeat:repeat;
  padding-bottom:20px;
}
button#s {
      background-color: midnightblue;
      color: khaki;
      font-size: 16px;
      /* font-weight: bold; */
      border: none;
      border-radius: 6px;
      padding: 10px 30px;
      display: block;
      margin: 20px auto;
      transition: all 0.3s ease-in-out;
    }

    button#s:hover {
      background-color: khaki;
      color: midnightblue;
      cursor: pointer;
    }
body{
  color:brown;
}
table,tr{
    
}
table {
      width: 1000px;
      border-collapse: collapse;
      margin-top: 30px;
      margin: 60px 0px 15px 0%;
    }
 th{
      border: 1px solid #000;
      text-align: center;
      padding: 1px;
       background-color: #004080;
      color:white;
 }
   tr{
      border: 1px solid #000;
      text-align: center;
      padding: 5px;
    }
td {
      border: 1px solid #000;
      text-align: center;
      padding: 15px;
    }

#t1{
    border:solid black 0px;
    border-radius:10px;
    margin:-280px 0px 5px 3%;
}
#t2{
  border:solid black 0px;
  margin:-30px 0px 5px 3%; 
  border-radius:10px;
}
#t3{
  border:solid black 0px;
  margin:-300px 0px 5px 3%; 
  border-radius:10px;
}
#s{
  margin-left:45%;
  color:black;
  /* background-color:green; */
  width:200px;
  height:40px;
  border-radius:6px;
  
}
input,select{
  width:300px;
  height:30px;
  border-color:darkblue;
  border-radius:5px;
}
textarea{
  width:300px;
  height:60px;
  border-color:darkblue;
  border-radius:5px;
}
#w{
  padding-top:15px;
  padding-left:5px;
  text-align:left;
   height:100px;
}
#c{
  width:250px;
  height:100px;
   margin-left:1px;
}
h1{
  text-align:center;
}

</style>  




<form method="post" enctype="multipart/form-data">
  <br><br><br>
  <center>
    <table id="t">
      <tr style="background-color:#DF674D;">
        <th colspan="2">
          <h3 style="color:white;font-family:tahoma;text-align:center;">Project Entry Form</h3>
        </th>
      </tr>

      <tr>
        <td><b>Project Name:</b></td>
        <td><input type="text" name="project_name" placeholder="Enter Project Name" required></td>
      </tr>

      <tr>
        <td><b>Project Description:</b></td>
        <td><textarea name="project_desc" placeholder="Enter Description" required></textarea></td>
      </tr>

      <tr>
        <td><b>Customer Name:</b></td>
        <td><input type="text" name="customer_name" placeholder="Enter Customer Name" required></td>
      </tr>

      <tr>
        <td><b>Ticket Description:</b></td>
        <td><textarea name="ticket_desc" placeholder="Enter Ticket Info" required></textarea></td>
      </tr>

      <tr>
        <td><b>Start Date:</b></td>
        <td><input type="date" name="start_date" required></td>
      </tr>

      <tr>
        <td><b>End Date:</b></td>
        <td><input type="date" name="end_date" required></td>
      </tr>

      <tr>
        <td><b>Warranty From:</b></td>
        <td><input type="date" name="warranty_from"></td>
      </tr>

      <tr>
        <td><b>Warranty End:</b></td>
        <td><input type="date" name="warranty_end"></td>
      </tr>

      <tr>
        <td><b>Project Owner:</b></td>
        <td><input type="text" name="owner" placeholder="Enter Owner Name" required></td>
      </tr>
       <tr>
        <td><b>Project Owner Staff Number:</b></td>
        <td><input type="number" name="staff_number" placeholder="Enter Owner Staff Number" maxlength="6" required></td>
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
    <br><br>
  </center>
</form>

</div>
  </body>
</html>