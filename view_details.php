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
  <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Project Review</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <link href="assets/images/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css" type="text/css">
  <script src="assets/js/jquery.min.js"></script>
  <link rel="stylesheet" href="assets/DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css">
  <script type="text/javascript" src="assets/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
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
  </nav></header>
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
    color:rgb(10, 10, 10);
  }
  tbody tr:nth-child(even) {
    background-color: #ffffff;
    color:rgb(26, 25, 25);
    font-size:18px;
    font-family: 'Segoe UI', sans-serif;
  }
  tbody tr:nth-child(odd) {
    background-color: #ffffff;
    color:rgb(26, 25, 25);
    font-size:18px;
    font-family: 'Segoe UI', sans-serif;
  }
 
  tbody tr:hover {
    background-color: #d3e4f0;
    color:rgb(54, 13, 150);
  }
  h2 {
    color: #4B0082;
    font-family: 'Segoe UI', sans-serif;
  }
</style>
<br><br>
<!-- Body content remains unchanged, uses dynamic PHP rendering -->

<body><br><br>
    <center><h2 style="color:purple;">Project Review:-Department /Unit/SBU/Lab</h2></center>
    <center><table id="myTable" style="width:95%;">
   <thead>
    <tr><th colspan="14" style="text-align:center;color:white;"><h2 style="color:white;">Project Details</h2></th></tr>
    <tr>
  
      <th><h6 style="color:white;">Technology Domain</h6></th>
      <th><h6 style="color:white;">Unit/SBU/LAB</h6> </th>
      <th><h6 style="color:white;">D&E Group</h6></th>
      <th><h6 style="color:white;">Project</h6></th>
      <th><h6 style="color:white;">Project Type</h6></th>
      <th><h6 style="color:white;">Parent WBS<br> (if applicable)</h6></th>
      <th><h6 style="color:white;">Project WBS</h6></th>
      <th><h6 style="color:white;">Start date</h6></th>
      <th><h6 style="color:white;">Original PDC </h6></th>
      <th><h6 style="color:white;">Current PDC</h6></th>
      <th><h6 style="color:white;">No of extension</h6></th>
      <th><h6 style="color:white;">Project Budget (as per ZIPS001) (in Lakhs)</h6></th>
      <th><h6 style="color:white;">Project Expenditure (as per ZIPS001)(as on date & incl commitment) (in Lakhs)</h6></th>
    </tr>
    </thead>
  <tbody>

  <?php

   
$sl_no=$_GET['sl_no'];
$sql="SELECT * FROM project_details where sl_no='$sl_no'"; 
$result=mysqli_query($conn,$sql);
$num_rows=mysqli_num_rows($result);
if($num_rows == 0){
    echo '<tr>
        <td colspan="14" style="text-align:center;">No records to display</td>
      </tr>';
}

if($result){
    while($row=mysqli_fetch_assoc($result)){

        $technology_domain=$row['technology_domain'];
        $unit_sbu=$row['unit_sbu'];
        $dne_group=$row['dne_group'];
        $project=$row['project'];
        $project_type=$row['project_type']; 
        $parent_wbs=$row['parent_wbs'];
        $wbs=$row['wbs'];
        $start_date=$row['start_date'];
        $original_pdc=$row['original_pdc'];
        $current_pdc=$row['current_pdc'];
        $no_of_extensions=$row['no_of_extensions'];
        $project_budget=nl2br($row['project_budget']);       
        $expenditure=nl2br($row['expenditure']);

        echo '<tr>

        <td style="background-color:white">'.$technology_domain.'</td>
        <td style="background-color:white;">'.$unit_sbu.'</td>
        <td style="background-color:white;">'.$dne_group.'</td>
        <td style="background-color:white;">'.$project.'</td>
        <td style="background-color:white;">'.$project_type.'</td>
        <td style="background-color:white;">'.$parent_wbs.'</td>
        <td style="background-color:white;">'.$wbs.'</td>
        <td style="background-color:white;">'.$start_date.'</td>
        <td style="background-color:white;">'.$original_pdc.'</td>
        <td style="background-color:white;">'.$current_pdc.'</td>
        <td style="background-color:white;">'.$no_of_extensions.'</td>
        <td style="background-color:white;">'.$project_budget.'</td>
        <td style="background-color:white;">'.$expenditure.'</td>
        
      </tr>';

    }
   
}

?>
</tbody>
</table><br><br>
<center>

<table id="myTable" style="width:1000px;">
   <thead>
    <tr><th colspan="4" style="text-align:center;color:white;"><h2 style="color:white;">Project Details</h2></th></tr>
    <tr>
      <th rowspan="2" ><h6 style="color:white;">Development Partners <br> (Unit/SBU/Labs/External)<br>(if applicable)</h6></th>
      <th style="text-align:center;" colspan="3" ><h6 style="color:white;">Production Commitments (if applicable)</h6></th>
    </tr>
    <tr>
      <th><h6 style="color:white;">Customer Name <br>  (Unit/SBU/DRDO/ External)</h6></th>
      <th><h6 style="color:white;">PoC/FoPM/Order</h6> </th>
      <th><h6 style="color:white;">Delivery date </h6></th>
    </tr>
    </thead>
  <tbody>

  <?php

   
$sl_no=$_GET['sl_no'];
$sql="SELECT * FROM project_details where sl_no='$sl_no'"; 
$result=mysqli_query($conn,$sql);
$num_rows=mysqli_num_rows($result);
if($num_rows == 0){
    echo '<tr>
        <td colspan="4" style="text-align:center;">No records to display</td>
      </tr>';
}

if($result){
    while($row=mysqli_fetch_assoc($result)){
        $dev_partners=$row['dev_partners'];
        $cust_name=$row['cust_name'];
        $poc_fopm_order=$row['poc_fopm_order'];
        $delivery_date=$row['delivery_date'];
        
        echo '<tr>
        <td style="background-color:white;">'.$dev_partners .'</td>
        <td style="background-color:white;">'.$cust_name.'</td>
        <td style="background-color:white;">'.$poc_fopm_order.'</td>
        <td style="background-color:white;">'.$delivery_date.'</td
        
      </tr>';
    }
   
}

?>
</tbody>
</table><br><br>


<table id="myTable" style="width:95%;">
   <thead>
    <tr><th colspan="7" style="text-align:center;color:white;"><h2 style="color:white;">Project Status</h2></th></tr>
    <tr>
      <th rowspan="2" ><h6 style="color:white;">Key Milestones  completed  till  end of last quarter </h6></th>
      <th style="text-align:center;" colspan="3" ><h6 style="color:white;">Plan for  current quarter (Max 3 Milestones)</h6></th>
      <th style="text-align:center;" colspan="3" ><h6 style="color:white;">Achieved in current quarter (Max 3 Milestones) </h6></th>
    </tr>
    <tr>
      <th><h6 style="color:white;">Month 1 </h6></th>
      <th><h6 style="color:white;">Month 2 </h6> </th>
      <th><h6 style="color:white;">Month 3 </h6></th>
      <th><h6 style="color:white;">Month 1 </h6></th>
      <th><h6 style="color:white;">Month 2 </h6> </th>
      <th><h6 style="color:white;">Month 3 </h6></th>
    </tr>
    </thead>
  <tbody>

  <?php

   
$sl_no=$_GET['sl_no'];
$sql="SELECT * FROM project_details where sl_no='$sl_no'"; 
$result=mysqli_query($conn,$sql);
$num_rows=mysqli_num_rows($result);
if($num_rows == 0){
    echo '<tr>
        <td colspan="7" style="text-align:center;">No records to display</td>
      </tr>';
}

if($result){
    while($row=mysqli_fetch_assoc($result)){
        $key_milestones=nl2br($row['key_milestones']);
        $plan_m1=nl2br($row['plan_m1']);
        $plan_m2=nl2br($row['plan_m2']);
        $plan_m3=nl2br($row['plan_m3']);
        $achieved_m1=nl2br($row['achieved_m1']);
        $achieved_m2=nl2br($row['achieved_m2']);
        $achieved_m3=nl2br($row['achieved_m3']);
        
        echo '<tr>
        <td style="background-color:white;">'.$key_milestones .'</td>
        <td style="background-color:white;">'.$plan_m1 .'</td>
        <td style="background-color:white;">'.$plan_m2.'</td>
        <td style="background-color:white;">'.$plan_m3.'</td>
        <td style="background-color:white;">'.$achieved_m1.'</td>
        <td style="background-color:white;">'.$achieved_m2.'</td>
        <td style="background-color:white;">'.$achieved_m3.'</td>

      </tr>';
    }
   
}

?>
</tbody>
</table><br><br>

<table id="myTable"  style="width: 500px;">
   <thead>
    <tr>
    <th style="text-align:center;"  ><h6 style="color:white;">Project Team</h6></th>
   
    </tr>
    <tr>
      <th><h6 style="color:white;">Name(Staff Number)</h6></th>
    
      
    </tr>
    </thead>
  <tbody>

  <?php

   
$sl_no=$_GET['sl_no'];
$sql="SELECT * FROM project_details where sl_no='$sl_no'"; 
$result=mysqli_query($conn,$sql);
$num_rows=mysqli_num_rows($result);
if($num_rows == 0){
    echo '<tr>
        <td colspan="4" style="text-align:center;">No records to display</td>
      </tr>';
}

if($result){
    while($row=mysqli_fetch_assoc($result)){
        $name=nl2br($row['name']);      

 
        echo '<tr>
        <td style="background-color:white;">'.$name .'</td>


      </tr>';
    }
   
}

?>
</tbody>
</table><br><br>

<table id="myTable"  style="width: 1000px;">
   <thead>
    <tr>
    
      <th style="text-align:center;" colspan="3"  ><h6 style="color:white;">Others</h6></th>
    </tr>
    <tr>
    
      <th><h6 style="color:white;">Issues faced</h6></th>
      <th><h6 style="color:white;">Support needed</h6></th>
      <th><h6 style="color:white;">Action points <br>(to be filled during/after review)</h6></th>
      
    </tr>
    </thead>
  <tbody>

  <?php

   
$sl_no=$_GET['sl_no'];
$sql="SELECT * FROM project_details where sl_no='$sl_no'"; 
$result=mysqli_query($conn,$sql);
$num_rows=mysqli_num_rows($result);
if($num_rows == 0){
    echo '<tr>
        <td colspan="4" style="text-align:center;">No records to display</td>
      </tr>';
}

if($result){
    while($row=mysqli_fetch_assoc($result)){
      
        $issues_faced=$row['issues_faced'];
        $support_needed=$row['support_needed'];
        $action_points=$row['action_points'];
 
        echo '<tr>
  
        <td style="background-color:white;">'.$issues_faced .'</td>
        <td style="background-color:white;">'.$support_needed .'</td>
        <td style="background-color:white;">'.$action_points .'</td>

      </tr>';
    }
   
}

?>
</tbody>
</table><br><br>

<table id="myTable" style="width:1000px;">
   <thead>
    <tr>
      <th style="text-align:center;" colspan="6" ><h6 style="color:white;">Department Projects Statistics ( On going)</h6></th>
      
    </tr>
    <tr>
      <th rowspan="2"><h6 style="color:white;">Total No of projects</h6></th>
      <th rowspan="2"><h6 style="color:white;">Projects with valid PDC</h6> </th>
      <th rowspan="2"><h6 style="color:white;">Projects without valid PDC</h6> </th>
      <th colspan="3"><h6 style="color:white;">Projects without valid PDC</h6> </th>
    </tr>
    <tr>
      <th><h6 style="color:white;">TECO</h6></th>
      <th><h6 style="color:white;">RLSD</h6></th>
      <th><h6 style="color:white;">CLSD</h6></th>
    </tr>
    </thead>
  <tbody>

  <?php

   
$sl_no=$_GET['sl_no'];
$sql="SELECT * FROM project_details where sl_no='$sl_no'"; 
$result=mysqli_query($conn,$sql);
$num_rows=mysqli_num_rows($result);
if($num_rows == 0){
    echo '<tr>
        <td colspan="4" style="text-align:center;">No records to display</td>
      </tr>';
}

if($result){
    while($row=mysqli_fetch_assoc($result)){
        $no_of_projects=$row['no_of_projects'];
        $project_valid_pdc=$row['project_valid_pdc'];
        $without_valid_pdic=$row['without_valid_pdic'];
        $teco=$row['teco'];
        $rlsd=$row['rlsd'];
        $clsd=$row['clsd'];
        
        echo '<tr>
        <td style="background-color:white;">'.$no_of_projects .'</td>
        <td style="background-color:white;">'.$project_valid_pdc.'</td>
         <td style="background-color:white;">'.$without_valid_pdic.'</td>
        <td style="background-color:white;">'.$teco.'</td>
        <td style="background-color:white;">'.$rlsd.'</td>
        <td style="background-color:white;">'.$clsd.'</td>
        
      </tr>';
    }
   
}

?>
</tbody>
</table><br><br>


</center>
</body>
</html>
