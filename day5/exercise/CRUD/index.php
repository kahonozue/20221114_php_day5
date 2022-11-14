<?php
session_start();
require_once 'components/db_connect.php';

// it will never let you open index (login) page if session is set
if (isset($_SESSION['user']) != "") {
  header("Location: home.php");
  exit;
}
if (isset($_SESSION['adm']) != "") {
  header("Location: dashboard.php"); // redirects to home.php
}

$error = false;
$name = $password = $nameError = $passError = '';

if (isset($_POST['btn-login'])) {

  // prevent sql injections/ clear user invalid inputs
  $name = trim($_POST['name']);
  $name = strip_tags($name);
  $name = htmlspecialchars($name);

  $pass = trim($_POST['pass']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);

  if (empty($name)) {
      $error = true;
      $nameError = "Please enter dish name.";
  }

  if (empty($pass)) {
      $error = true;
      $passError = "Please enter dish password.";
  }

  // if there's no error, continue to login
  if (!$error) {

      $password = hash('sha256', $pass); // password hashing

      $sql = "SELECT dishID, name, password, status FROM dishes WHERE name = '$name'";
      $result = mysqli_query($connect, $sql);
      $row = mysqli_fetch_assoc($result);
      $count = mysqli_num_rows($result);
      if ($count == 1 && $row['password'] == $password) {
          if ($row['status'] == 'adm') {
              $_SESSION['adm'] = $row['dishID'];
              header("Location: dashboard.php");
          } else {
              $_SESSION['user'] = $row['dishID'];
              header("Location: home.php");
          }
      } else {
          $errMSG = "Incorrect Credentials, Try again...";
      }
  }
}

mysqli_close($connect);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login & Registration System</title>
  <?php require_once 'components/boot.php' ?>
</head>

<body>
  <div class="container">
      <form class="w-75" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
          <h2>Login</h2>
          <hr />
          <?php
          if (isset($errMSG)) {
              echo $errMSG;
          }
          ?>

          <input type="text" autocomplete="off" name="name" class="form-control" placeholder="Dish Name" value="<?php echo $name; ?>" maxlength="40" />
          <span class="text-danger"><?php echo $nameError; ?></span>

          <input type="password" name="pass" class="form-control" placeholder="Dish Password" maxlength="15" />
          <span class="text-danger"><?php echo $passError; ?></span>
          <hr />
          <button class="btn btn-block btn-primary" type="submit" name="btn-login">Sign In</button>
          <hr />
          <a href="register.php">Not registered yet? Click here</a>
      </form>
  </div>
</body>
</html>