<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $staff_number = $_POST['staff_number'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE staff_number = ?");
    $stmt->bind_param("s", $staff_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $error = "Staff number does not exist.";
    } elseif (!password_verify($password, $user['password'])) {
        $error = "Incorrect password.";
    } else {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: project_review.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Customer Support Portal</title>
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
      margin-right:100px;
    }

    .navmenu a {
      color: white;
      text-decoration: none;
    }

    .navmenu a:hover {
      color: #FFD700;
    }

    .login-container {
      margin-top: 120px;
      width: 500px;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
      margin-left: auto;
      margin-right: auto;
    }

    .login-container h2 {
      text-align: center;
      color: #002147;
      margin-bottom: 30px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      color: #333;
    }

    input[type="integer"], input[type="password"] {
      width: 93%;
      padding: 12px 15px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
      transition: 0.3s ease;
    }

    input[type="text"]:focus, input[type="password"]:focus {
      border-color: #004080;
      outline: none;
    }

    #g {
      width: 99%;
      background: #ff8c00;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s ease;
    }

    #g:hover {
      background: #e67300;
    }

    .forgot {
      text-align: center;
      margin-top: 10px;
    }

    .forgot a {
      color: #004080;
      text-decoration: none;
    }

    .forgot a:hover {
      text-decoration: underline;
    }

    .signup-link {
      text-align: center;
      margin-top: 20px;
      color:rgb(219, 125, 10);
    }

    .signup-link a {
      color: #004080;
      font-weight: bold;
      text-decoration: none;
    }

    .signup-link a:hover {
      text-decoration: underline;
    }

    .error-msg {
      text-align: center;
      color: red;
      margin-bottom: 20px;
    }

    #forgotPasswordModal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 9999;
    }

    #forgotPasswordModal .modal-content {
      background: white;
      padding: 25px;
      border-radius: 10px;
      width: 300px;
      margin: 150px auto;
      text-align: center;
    }

    #forgotPasswordModal button {
      margin-top: 20px;
      background-color: #002147;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
    }

    header {
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
    }
  </style>
</head>

<body>

<header class="header fixed-top">
  <div class="sitename">
    <img src="assets/images/bellogo1.png" alt="BEL Logo" class="logo">
    Customer Service Portal    
  </div>
  <nav class="navmenu">
    <ul>
       <li>
        <a href="assets/docs/Project_Review_User_Guide.docx" download>
          <b>User Guide</b>
        </a>
      </li>
    </ul>
  </nav>
</header>



<div class="login-container">
  <h2>User Login</h2>

  <?php if (!empty($error)) : ?>
    <div class="error-msg"><?php echo $error; ?></div>
  <?php endif; ?>

  <form method="post">
    <label>STAFF NUMBER</label>
    <input type="integer" name="staff_number" placeholder="Enter staff number" maxlength="6" required="6" required autocomplete="off">

    <label>PASSWORD</label>
    <input type="password" name="password" placeholder="Enter password" required autocomplete="off">

    <input type="submit" value="LOGIN" id="g" name="submit">
  </form>

  <div class="forgot">
    <a href="javascript:void(0);" onclick="showForgotPasswordModal()">Forgot Password?</a>
  </div>

 
</div>

<div id="forgotPasswordModal">
  <div class="modal-content">
    <h4 style="color:black;">Please send mail to <b style="color:orange">cotp@bel.co.in</b> to reset password with user <b style="color:orange">Staff Number</b></h4>
    <button onclick="closeForgotPasswordModal()">Close</button>
  </div>
</div>

<script>
function showForgotPasswordModal() {
  document.getElementById('forgotPasswordModal').style.display = 'block';
}
function closeForgotPasswordModal() {
  document.getElementById('forgotPasswordModal').style.display = 'none';
}
</script>

</body>
</html>
