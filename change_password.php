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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change Password | Customer Support Portal</title>

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      background: linear-gradient(to right, #002147, #004080);
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
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 999;
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
    .signup-container {
      width: 480px;
      background: white;
      margin: 120px auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }
    label {
      color: #002147;
      font-weight: 500;
    }
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 2px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
    }
    input[type="submit"] {
      width: 100%;
      background-color: #ff8c00;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    input[type="submit"]:hover {
      background-color: #e67300;
    }
    .success-box {
      background-color: #e6ffe6;
      color: #006600;
      border: 1px solid #009900;
      padding: 20px;
      border-radius: 10px;
      width: 90%;
      max-width: 400px;
      text-align: center;
      margin: 80px auto;
    }
    .error-msg {
      color: red;
      text-align: center;
      margin: 20px;

    }

/* jg */
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
  <div class="header-section left">
    <img src="assets/images/bellogo1.png" alt="BEL Logo" class="logo">
  </div>
  <div class="header-section center">
    Customer Service Portal
  </div>
  <div class="header-section right navmenu">
    <ul>
      <li><a href="project_review.php"><b>HOME</b></a></li>
    </ul>
  </div>
</header>


<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo '
    <div class="signup-container" id="change-form">
      <h3 style="text-align:center;">Change Password</h3>
      <form method="post" action="">
        <label>Old Password</label>
        <input type="password" name="userpass_old" placeholder="Enter Old Password" required autocomplete="off">

        <label>New Password</label>
        <input type="password" name="password" placeholder="Enter New Password" required autocomplete="off">

        <label>Re-enter New Password</label>
        <input type="password" name="confirm_password" placeholder="Re-enter New Password" required autocomplete="off">

        <input type="submit" name="submit" value="Submit">
      </form>
    </div>';
} else {
    $user_id = $_SESSION['user_id'];
    $old_password = $_POST['userpass_old'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];

    if ($new_password !== $confirm_password) {
        $errors[] = "New passwords do not match.";
    }

    if (strlen($new_password) < 6) {
        $errors[] = "New password should be at least 6 characters.";
    }

    // Get current password from DB
    $query = "SELECT password FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 0) {
        $errors[] = "User not found.";
    } else {
        mysqli_stmt_bind_result($stmt, $db_password);
        mysqli_stmt_fetch($stmt);

        if (!password_verify($old_password, $db_password)) {
            $errors[] = "Old password is incorrect.";
        }
    }

    if (!empty($errors)) {
        echo '<div class="signup-container"><div class="error-msg">';
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        echo '</div></div>';
    } else {
        $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE users SET password = ? WHERE id = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, 'si', $new_hashed, $user_id);
        $result = mysqli_stmt_execute($update_stmt);

        if ($result) {
            echo '
            <div class="success-box">
              Password changed successfully.<br><br>
              <a href="project_review.php" style="background:#ff8c00;color:white;padding:10px 15px;border-radius:6px;text-decoration:none;margin:5px;">Return to Home</a>
              <a href="index.php" style="background:#004080;color:white;padding:10px 15px;border-radius:6px;text-decoration:none;margin:5px;">Login Again</a>
            </div>

            <script>
              document.getElementById("change-form").style.display = "none";
            </script>';
        } else {
            echo '<div class="signup-container"><div class="error-msg">Something went wrong. Please try again later.</div></div>';
        }
    }
}
?>
</body>
</html>
