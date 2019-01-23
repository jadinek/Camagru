<?php
require_once 'database.php';
require_once 'guest_header.php';

if(isset($_POST['signin']))
    {
        $Email = $_POST['Email'];
        $Pass = $_POST['Pass'];

        $select = $con->prepare("SELECT forgot FROM users WHERE Email = '$Email'");
        $select->setFetchMode(PDO::FETCH_ASSOC);
        $select->execute();
        $data=$select->fetch();

        if($data['forgot'] == 1)
            $enc_password = $Pass;
        else
            $enc_password = md5($Pass);

        $select = $con->prepare("SELECT * FROM users WHERE Email= '$Email' and Pass = '$enc_password'");
        $select->setFetchMode(PDO::FETCH_ASSOC);
        $select->execute();
        $data=$select->fetch();
        //why does it first test for correct password before account confirmation
        if($data['Email'] == $Email and $data['Confirmed'] != 1)
        {
            echo "Please verify your email address before signing in.";
        }
        elseif($data['Email'] == $Email and $data['Pass'] == $enc_password)
        {
            $_SESSION['id'] = $data['Email'];
            $_SESSION['Pass'] = $data['Pass'];
            header('Location: http://localhost:8888/index.php');
        }
        /*elseif($data['Email'] == $Email and $data['Pass'] == $Password)
        {
            $_SESSION['Email'] = $data['Email'];
            $_SESSION['Pass'] = $data['Pass'];
            header('Location: http://localhost:8888/index.php');
        }*/
        elseif($data['Email'] != $Email and $data['Pass'] != $enc_password)
        {
            echo "invalid email or password";
        }
    }
?>

<html>
	<head>
		<title>Login</title>
		<link rel="stylesheet" href= "css/style2.css">
		</head>
	<body>
		<form method ="post">
			<div class="login">
				<div class="form">
                        <img src = "images/cow.png" width = 50%>

                        <input type = "text" name = "Email" placeholder = "Email">
                        <input type = "text" name = "Pass" placeholder = "Password">

                        <input type = "submit" name = "signin" value = "SIGN IN">
			<p class= "message"> Not registered? <a href="register.php">Create an account</a></p>
            <p class= "message"> <a href="forgotpassword.php">Forgot password?</a></p>
</div>
</div>
		</form>
	</body>
</html>
<?php
require_once 'footer.php';
?>