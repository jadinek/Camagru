<?php
require_once 'header.php';
require_once 'database.php';
session_start();
if (!isset($_SESSION['id'])){
    header("location:/signin.php");
}
$Email = $_SESSION['id'];
global $alert;
    
    //username
    if(isset($_POST['val1']))
    {
        $UserName = trim($_POST['newUserName']);

        $select = $con->prepare("UPDATE users SET UserName= '$UserName' WHERE Email = '$Email'");
        $select->execute();
        $alert = "Username successfully updated.";
    }
    //email
    if(isset($_POST['val2']))
    {
        $newEmail = trim($_POST['newEmail']);

        $select = $con->prepare("UPDATE users SET Email= '$newEmail' WHERE Email = '$Email'");
        $select->execute();

        $select = $con->prepare("UPDATE images SET Email= '$newEmail' WHERE Email = '$Email'");
        $select->execute();

        $select = $con->prepare("UPDATE comments SET Email= '$newEmail' WHERE Email = '$Email'");
        $select->execute();

        $Email = $newEmail;
        $_SESSION['id']= $newEmail;

        $alert = "Email successfully updated.";

    }
    //password
    if(isset($_POST['val3']))
    {
        $Password = trim($_POST['newPassword']);

        $enc_password = md5($Password);

        $select = $con->prepare("UPDATE users SET Pass= '$enc_password' WHERE Email = '$Email'");
        $select->execute();

        $select = $con->prepare("UPDATE users SET forgot= '0' WHERE Email = '$Email'");
        $select->execute();

        $alert = "Password successfully updated.";
    }
    //notifications on
    if(isset($_POST['val4']))
    {
        $select = $con->prepare("UPDATE users SET Notifications= '1' WHERE Email = '$Email'");
        $select->execute();
        $alert = "Notifications on.";

    }
    //notifications off
    if(isset($_POST['val5']))
    {
        $select = $con->prepare("UPDATE users SET Notifications= '0' WHERE Email = '$Email'");
        $select->execute();
        $alert = "Notifications off.";

    }

?>

<html>
	<head>
		<title>Login</title>
		<link rel="stylesheet" href= "css/style2.css">
		</head>
	<body>
		<form action="" method ="post">
			<div class="login">
				<div class="form">
                        <img src = "images/fox.png" width = 50%>

                        <input type = "text" name = "newUserName" placeholder = "Enter new username">
                        <input type = "submit" name = "val1" value = "Submit">

                        <input type = "text" name = "newEmail" placeholder = "Enter new email address">
                        <input type = "submit" name = "val2" value = "Submit">

                        <input type = "text" name = "newPassword" placeholder = "Enter new password">
                        <input type = "submit" name = "val3" value = "Submit">

                        <input type = "submit" name = "val4" value = "Enable notifications">
                        <input type = "submit" name = "val5" value = "Disable notifications">
                        <?php echo $alert ?>
        </form>
</div>
</div>
	</body>
</html>
<?php
require_once 'footer.php';
?>