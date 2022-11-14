
<!DOCTYPE html>
<html>
<body>
<?php
$str = "\t\t\n PHP Validators are useful tools\n\t ";
echo "Original value:" . $str . "\n";
echo "<br>";
echo "Trimmed value:" . trim($str);

echo "<br>";

$test_var_a = "<br><p><a href='file.php'>PHP Validators</a></p>";
echo htmlspecialchars($test_var_a);
//it will print: <br><p><a href='file.php'>PHP Validators</a></p>

echo "<br>";

$test_var_a = "<p><a href='file.php'>PHP Validators</a></p><br>";
$test_var_b = "<?php echo 'strip_tags remove php too'?>";
echo htmlspecialchars(strip_tags($test_var_a));           //it will print: PHP Validators;
echo "<br>";
echo htmlspecialchars(strip_tags($test_var_a, "<br>"));   //it will print: PHP Validators<br>;
echo "<br>";
var_dump(strip_tags($test_var_b));;         
?>
</body>
</html>

