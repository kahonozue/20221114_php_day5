<?php
session_start();
require_once 'components/db_connect.php';
require_once 'components/file_upload.php';

if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$backBtn = '';
//if it is a user it will create a back button to home.php
if (isset($_SESSION["user"])) {
    $backBtn = "home.php";
}
//if it is a adm it will create a back button to dashboard.php
if (isset($_SESSION["adm"])) {
    $backBtn = "dashboard.php";
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM dishes WHERE dishID = {$id}";
    $result = mysqli_query($connect, $sql);
    if (mysqli_num_rows($result) == 1) {
        $data = mysqli_fetch_assoc($result);
        $id = $data['dishID'];
        $image = $data['image'];
        $name = $data['name'];
        $price = $data['price'];
        $mealDescription = $data['meal_description'];
    }
}

//update
$class = 'd-none';
if (isset($_POST["submit"])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $mealDescription = $_POST['mealDescription'];
    $id = $_POST['id'];
    //variable for upload pictures errors is initialized
    $uploadError = '';
    $imageArray = file_upload($_FILES['image']); //file_upload() called
    if ($imageArray->error === 0) {
        $image = $imageArray->fileName;
        ($_POST["image"] == "product.png") ?: unlink("pictures/{$_POST["image"]}");
        $sql = "UPDATE dishes SET image = '$image', name = '$name', price = '$price', meal_description = '$mealDescription' WHERE dishID = {$id}";
    } else {
        $sql = "UPDATE dishes SET name = '$name', price = '$price', meal_description = '$mealDescription' WHERE dishID = {$id}";
    }
    if (mysqli_query($connect, $sql) === true) {
        $class = "alert alert-success";
        $message = "The record was successfully updated";
        $uploadError = ($imageArray->error != 0) ? $imageArray->ErrorMessage : '';
        header("refresh:3;url=update.php?id={$id}");
    } else {
        $class = "alert alert-danger";
        $message = "Error while updating record : <br>" . $connect->error;
        $uploadError = ($imageArray->error != 0) ? $imageArray->ErrorMessage : '';
        header("refresh:3;url=update.php?id={$id}");
    }
}

mysqli_close($connect);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Menu</title>
    <?php require_once 'components/boot.php' ?>
    <style type="text/css">
        fieldset {
            margin: auto;
            margin-top: 100px;
            width: 60%;
        }

        .img-thumbnail {
            width: 70px !important;
            height: 70px !important;
        }
    </style>
</head>

<body class="vh-100 d-flex align-items-center justify-content-space-around">



    <fieldset class="w-75 bg-warning mx-auto my-auto pb-3">

        <legend class='h2'>Update Menu <img class='img-thumbnail rounded-circle' src='pictures/<?= $image ?>' alt="<?= $name ?>"></legend>

        <div class="<?php echo $class; ?>" role="alert">
            <p><?php echo ($message) ?? ''; ?></p>
            <p><?php echo ($uploadError) ?? ''; ?></p>
        </div>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="w-75 bg-white mx-auto my-auto">
                <div class="d-flex align-items-center p-3">
                    <div class="w-25">Image of the dish</div>
                    <input type="file" name="image" />
                </div>

                <div class="d-flex align-items-center p-3">
                    <div class="w-25">dish name</div>
                    <input type='text' name="name" placeholder="maroni" value="<?= $name ?>" />
                </div>

                <div class="d-flex align-items-center p-3">
                    <div class="w-25">Price</div>
                    <input type="number" name="price" placeholder="1.11" value="<?= $price ?>" />
                </div>

                <div class="d-flex align-items-center p-3">
                    <div class="w-25">Meal description</div>
                    <input type="text" name="mealDescription" placeholder="so spicy" value="<?= $mealDescription ?>" />
                </div>

                <div class="p-3">
                    <input type="hidden" name="id" value="<?= $id ?>" />
                    <input type="hidden" name="image" value="<?= $image ?>" />
                    <button class='btn btn-success' type="submit" name="submit">Save Changes</button>
                    <a href="index.php"><button class='btn btn-warning' type="button">Back</button></a>
                </div>
            </div>
        </form>

    </fieldset>
</body>

</html>