<?php
include 'connect.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

$success = "";
$error = "";
$formDisabled = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $staff_number = $_POST['staff_number'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = 'Bel@1234';
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // Check if staff number already exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE staff_number = ?");
    $check_stmt->bind_param("s", $staff_number);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $error = "Staff Number already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, staff_number, email, password, role) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            $error = "Prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("sssss", $username, $staff_number, $email, $hashed, $role);
            if ($stmt->execute()) {
                $success = "Account created successfully! Default password is Bel@1234";
                $formDisabled = true;
            } else {
                $error = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }

    $check_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create | Customer Support Portal</title>

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

    .signup-container h2 {
      text-align: center;
      color: #002147;
      margin-bottom: 25px;
    }

    label {
      color: #002147;
      font-weight: 500;
    }

    input[type="text"], input[type="integer"], input[type="password"], input[type="email"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
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

    .error-msg {
      color: red;
      text-align: center;
      margin-bottom: 15px;
      font-weight: bold;
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

    .disabled-form input {
      background-color: #f1f1f1;
      pointer-events: none;
    }

    .bottom-link {
      text-align: center;
      margin-top: 20px;
      color: #ff8c00;
    }

    .bottom-link a {
      color: #004080;
      font-weight: bold;
      text-decoration: none;
    }

    .bottom-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>

<header class="header">
  <div class="sitename">
    <img src="assets/images/bellogo1.png" alt="BEL Logo" class="logo">
     Customer Service Portal    
  </div>
  <nav class="navmenu">
    <ul>
      
      <li><a href="project_review.php"><b>HOME</b></a></li>
        
      
    </ul>
  </nav>
</header>

<?php if (!$formDisabled): ?>
<div class="signup-container">
  <h2>Create User </h2>

  <?php if (!empty($error)) : ?>
    <div class="error-msg"><?php echo $error; ?></div>
  <?php endif; ?>

  <form method="post">
    <label>USERNAME:</label>
    <input type="text" name="username" placeholder="Enter username" required autocomplete="off">

    <label>STAFF NUMBER:</label>
    <input type="text" name="staff_number" maxlength="6" placeholder="Enter staff number" required autocomplete="off">


    <label>EMAIL:</label>
    <input type="email" name="email" placeholder="Enter email" required autocomplete="off">

    <label>ROLE:</label><br>
    <select name="role" id="domain" style="width:300px;height:40px; background-color:white" >
    <option>Slect role</option>
    <option>user</option>
    <option>admin</option>
   </select></td><br>
   <br>
    <input type="submit" name="submit" value="SUBMIT">
  </form>

<?php endif; ?>

<?php if (!empty($success)) : ?>
  <div class="success-box">
    <strong><?php echo $success; ?></strong><br><br>
    
    <a href="getallusers.php">Return to Home</a>
  </div>
<?php endif; ?>

</body>
</html>
