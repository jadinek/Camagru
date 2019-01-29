<?php
require_once 'header.php';
require_once 'config/database.php';
if (!isset($_SESSION['id'])){
    header("location:/signin.php");
}
$Email = $_SESSION['id'];
global $alert;
echo "Changing email address will result in an automatic logout. Please log in with your new credentials.";
    
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
        if(preg_match('/[;"=]/', $newEmail) || strpos($newEmail, '@') == false || $newEmail == "")
            return;

        $select = $con->prepare("UPDATE users SET Email= '$newEmail' WHERE Email = '$Email'");
        $select->execute();

        $select = $con->prepare("UPDATE images SET Email= '$newEmail' WHERE Email = '$Email'");
        $select->execute();

        $select = $con->prepare("UPDATE comments SET Email= '$newEmail' WHERE Email = '$Email'");
        $select->execute();

        $Email = $newEmail;
        $_SESSION['id']= $newEmail;

        header("location:/logout.php");

    }
    //password
    if(isset($_POST['val3']))
    {
        $Password = trim($_POST['newPassword']);

        if(strlen("Password") < 4 || strlen("Password") > 12)
            return;
        $uppercase_test = preg_match('#[A-Z]+#', $Password);
        $special_test = preg_match('#[!@$%^&*()_+=-]+#', $Password);
        if(!$uppercase_test && !$special_test)
            return;

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
require_once 'user_footer.php';
?>