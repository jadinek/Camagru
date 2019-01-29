<?php
require_once 'config/database.php';
require_once 'guest_header.php';

if(isset($_POST['ForgotPassword']))
    {
        $Email = trim($_POST['Email']);

        $Password = md5(uniqid(rand(), true));

        $select = $con->prepare("UPDATE users SET Pass= '$Password' WHERE Email = '$Email'");
        $select->execute();

        $select = $con->prepare("UPDATE users SET forgot= '1' WHERE Email = '$Email'");
        $select->execute();

        $message = 
        "Please log in with your new password: $Password
        Remember to update your password in your account settings!";

        mail($Email, "Retrieve password", $message, "From: DoNotReply@camagru.com");
        
        echo "Please check your email.";
    }

?>
<html>
	<head>
		<title>Forgot password</title>
		<link rel="stylesheet" href= "css/style2.css">
	</head>
	<body>
        <form action="" method ="post">
			<div class="login">
				<div class="form">
                        <img src = "images/turtle.png" width = 50%>
                        
                        <input type = "text" name = "Email" placeholder = "Email Address">
                        <input type = "submit" name = "ForgotPassword" value = "FORGOT PASSWORD">
                        </form>

                        <form method = "post">
                        <p><a href="signin.php">Sign in?</a></p>
		</form>
        </div>
        </div>
	</body>
</html>
<?php
require_once 'footer.php';
?>