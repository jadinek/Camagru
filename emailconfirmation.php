<?php
require_once 'config/database.php';

$username = $_GET['UserName'];
$code = $_GET['code'];

$query = $con->prepare("SELECT ConfirmCode FROM users WHERE UserName = '$username'");
$query->execute();
$data=$query->fetch();
if ($data['ConfirmCode'] == $code)
{
    $con->query("UPDATE Users SET Confirmed = 1 WHERE UserName = '$username'");
    $con->query("UPDATE Users SET ConfirmCode = 0 WHERE UserName = '$username'");
    echo "Account successfully created.";
}
else
{
    echo "This link is invalid. Please try again.";
}
?>