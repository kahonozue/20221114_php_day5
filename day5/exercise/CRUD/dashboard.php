<?php
require_once 'components/db_connect.php';
// require_once '../../pre-work/CRUD/components/db_connect.php';

$sql = "SELECT * FROM dishes";
$result = mysqli_query($connect, $sql);
$div = '';
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
if (mysqli_num_rows($result) > 0) {
    foreach ($rows as $row) {
        $div .= "<div class='eachMenu d-flex justify-content-evenly align-items-center'>
                    <img class='img-thumbnail img' src='pictures/" . $row['image'] . "'>
                    <div class='ms-5'>
                         <div>
                             <input type='hidden' name='dishId' value='" . $row['dishID'] . "' />
                             <input type='submit' name='submit" . $row['name'] . "' class='btnsubmit' value='" . $row['name'] . "' />
                         </div>
                        <div>$" . $row['price'] . "</div>
                    </div>
                    <form method=POST action='update.php' >
                    <input type='hidden' value='$row[dishID]' name='id' />
                    <input type='submit' value='Edit' name='edit' class='btn btn-primary btn-sm' type='button' />
                    </form>
                    <form method=POST action='delete.php' >
                        <input type='hidden' value='$row[dishID]' name='id' />
                        <input type='submit' value='Delete' name='delete' class='btn btn-danger btn-sm' type='button' type='button' />
                    </form>
                </div>";
    }
} else {
    $div = "<div class='text-center'>No dish here.<br>We are closed permanently.</div>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once 'components/boot.php' ?>

    <link rel="stylesheet" href="css/challenge.css">
    <title>Menu</title>
</head>

<body class="mt-3 mb-3">

    <div class='mb-3'>
        <a href="create.php"><button class='btn btn-primary' type="button">Add product</button></a>
    </div>

    <div class="w-75 min-h-100 mx-auto my-auto">
        <div class="menuTitle pt-3 text-center h1">Menu</div>
        <?= $div; ?>
    </div>

</body>

</html>