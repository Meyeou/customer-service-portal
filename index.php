

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Service Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles/entry_page.css">
  <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
</head>
<style>
  /* styles/entry_page.css */

body {
  margin: 0;
  font-family: 'Segoe UI', sans-serif;
  background-color: #E6F0FA;
}

.entry-page {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
   /* background-color:rgb(126, 181, 236); */
    background: linear-gradient(to right, #002147, #004080);
}

.entry-container {
  display: flex;
  width: 80%;
  max-width: 1200px;
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  overflow: hidden;
   background: linear-gradient(to right,rgb(227, 232, 236),rgb(236, 194, 88));
}

.divider {
  width: 5px;
  background-color: #004080;
}

.entry-left, .entry-right {
  flex: 1;
  padding: 40px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.entry-title {
  font-size: 2rem;
  color: #004080;
  margin-bottom: 20px;
  text-align: center;
}

.login-button {
  background-color: #004080;
  color: white;
  padding: 12px 24px;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  cursor: pointer;
}

.login-button:hover {
  background-color: #0059b3;
}

/* .entry-logo {
  width: 250px;
  height: auto;
} */

</style>
<body>
  <div class="entry-page">
    <div class="entry-container">
      <div class="divider"></div>

      <div class="entry-right">
        <img src="assets/images/BelLogo2.png" alt="Logo" class="entry-logo">
      </div>

      <div class="entry-left">
        <h1 class="entry-title">Welcome to Customer Service Portal</h1>
        <form method="get" action="login.php">
          <button type="submit" class="login-button">Login</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
