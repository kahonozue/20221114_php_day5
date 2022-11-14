<?php
session_start(); // start a new session or continues the previous
if (isset($_SESSION['user']) != "") {
    header("Location: home.php"); // redirects to home.php
}
if (isset($_SESSION['adm']) != "") {
    header("Location: dashboard.php"); // redirects to home.php
}
require_once 'components/db_connect.php';
require_once 'components/file_upload.php';
$error = false;
$image = $name = $pass = $price = $mealDescription = '';
$imgError = $nameError = $passError = $priceError = $descError = '';
if (isset($_POST['btn-signup'])) {

    // sanitise user input to prevent sql injection
    // trim - strips whitespace (or other characters) from the beginning and end of a string
    $name = trim($_POST['name']);


    // strip_tags -- strips HTML and PHP tags from a string
    $name = strip_tags($name);

    // htmlspecialchars converts special characters to HTML entities
    $name = htmlspecialchars($name);

    $pass = trim($_POST['pass']);
    $pass = strip_tags($pass);
    $pass = htmlspecialchars($pass);

    $price = trim($_POST['price']);
    $price = strip_tags($price);
    $price = htmlspecialchars($price);

    $mealDescription = trim($_POST['mealDescription']);
    $mealDescription = strip_tags($mealDescription);
    $mealDescription = htmlspecialchars($mealDescription);


    $uploadError = '';
    $image = file_upload($_FILES['image']);

    // basic name validation
    if (empty($name)) {
        $error = true;
        $nameError = "Please enter dish name.";
    } else {
        // checks whether the dish name exists or not
        $query = "SELECT name FROM dishes WHERE name='$name'";
        $result = mysqli_query($connect, $query);
        $count = mysqli_num_rows($result);
        if ($count != 0) {
            $error = true;
            $emailError = "Provided dish name is already in use.";
        }
    }

    // password validation
    if (empty($pass)) {
        $error = true;
        $passError = "Please enter password.";
    } else if (strlen($pass) < 6) {
        $error = true;
        $passError = "Password must have at least 6 characters.";
    }

    // price validation
    if (empty($price)) {
        $error = true;
        $priceError = "Please enter price.";
    }

    // password hashing for security
    $password = hash('sha256', $pass);
    // if there's no error, continue to signup
    if (!$error) {

        $query = "INSERT INTO dishes(image, name, password, price, meal_description)
                VALUES('$image->fileName', '$name', '$password', '$price', '$mealDescription')";
        $res = mysqli_query($connect, $query);

        if ($res) {
            $errTyp = "success";
            $errMSG = "Successfully registered, you may login now";
            $uploadError = ($image->error != 0) ? $image->ErrorMessage : '';
        } else {
            $errTyp = "danger";
            $errMSG = "Something went wrong, try again later...";
            $uploadError = ($image->error != 0) ? $image->ErrorMessage : '';
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
        <form class="w-75" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off" enctype="multipart/form-data">
            <h2>Sign Up.</h2>
            <hr />
            <?php
            if (isset($errMSG)) {
            ?>
                <div class="alert alert-<?php echo $errTyp ?>">
                    <p><?php echo $errMSG; ?></p>
                    <p><?php echo $uploadError; ?></p>
                </div>

            <?php
            }
            ?>

            <input class='form-control w-50' type="file" name="image">
            <span class="text-danger"> <?php echo $imgError; ?> </span>

            <input type="text" name="name" class="form-control" placeholder="Dish name" maxlength="50" value="<?php echo $name ?>" />
            <span class="text-danger"> <?php echo $nameError; ?> </span>

            <input type="password" name="pass" class="form-control" placeholder="Enter Password" maxlength="15" />
            <span class="text-danger"> <?php echo $passError; ?> </span>

            <input type="number" name="price" class="form-control" placeholder="Price" maxlength="50" value="<?php echo $price ?>" />
            <span class="text-danger"> <?php echo $priceError; ?> </span>

            <input type="text" name="mealDescription" class="form-control" placeholder="describe the meal" maxlength="40" value="<?php echo $mealDescription ?>" />
            <span class="text-danger"> <?php echo $descError; ?> </span>
           
            <hr />
            <button type="submit" class="btn btn-block btn-primary" name="btn-signup">Sign Up</button>
            <hr />
            <a href="index.php">Sign in Here...</a>
        </form>
    </div>
</body>

</html>