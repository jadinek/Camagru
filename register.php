<?php
require_once 'database.php';
require_once 'guest_header.php';

function validate($input)
{
    global $x;
    global $alert;
    if($x != 1)
    {
        if(preg_match('/[;"=]/', $input["UserName"]))
        {
            $alert = 'Invalid username.';
            return;
        }
        if(preg_match('/[;"=]/', $input["Email"]) || strpos($input["Email"], '@') == false)
        {
            $alert = 'Invalid email.';
            return;
        }
        if(strlen("Pass") < 4 || strlen("Pass") > 12)
        {
            $alert = 'Password must be 4-12 characters, contain an uppercase letter, and a special character. Please try again.';
            return;
        }
        $uppercase_test = preg_match('#[A-Z]+#', $input["Pass"]);
        $special_test = preg_match('#[!@$%^&*()_+=-]+#', $input["Pass"]);
        if(!$uppercase_test && !$special_test)
        {
            $alert = 'Invalid password.';
            return;
        }
        $x = 2;

    }
    return;
}

if(isset($_POST['Register']))
    {
        $UserName = trim($_POST['UserName']);
        $FirstName = trim($_POST['FirstName']);
        $LastName = trim($_POST['LastName']);
        $Email = trim($_POST['Email']);
        $Pass = trim($_POST['Pass']);

        if ($_POST['UserName'] == "" || $_POST['FirstName'] == "" || $_POST['LastName'] == "" || $_POST['Email'] == "" || $_POST['Pass'] == "")
        {
            $alert = 'Please fill in all feilds.';
            $x = 1;
        }
        else
            validate($_POST);
        
        if($x == 2)
        {
            $enc_password = md5($Pass);

            $Confirmed = 0;
            $ConfirmCode = rand();

            $insert = $con->prepare("INSERT INTO users(UserName, FirstName, LastName, Email, Pass, Confirmed, ConfirmCode)
            values(:UserName, :FirstName, :LastName, :Email, :Pass, :Confirmed, :ConfirmCode)");
            $insert->bindParam(':UserName', $UserName);
            $insert->bindParam(':FirstName', $FirstName);
            $insert->bindParam(':LastName', $LastName);
            $insert->bindParam(':Email', $Email);
            $insert->bindParam(':Pass', $enc_password);
            $insert->bindParam(':Confirmed', $Confirmed);
            $insert->bindParam(':ConfirmCode', $ConfirmCode);

            $message = 
            "Please confirm your email address. Click the link below: 
            http://localhost:".$_SERVER['SERVER_PORT']."/emailconfirmation.php?UserName=$UserName&code=$ConfirmCode";

            mail($Email, "Confirm Email", $message, "From: DoNotReply@camagru.com");

            echo "Please verify your email address.";

            $insert->execute();
        }

    }
elseif(isset($_POST['ForgotPassword']))
{
    header('Location: http://localhost:8888/forgotpassword.php'); 
}
?>
<html>
	<head>
		<title>Create account</title>
		<link rel="stylesheet" href= "css/style2.css">
	</head>
	<body>
		<form action = "register.php" method = "post">
			<div class="login">
				<div class="form">
                        <img src = "images/sheep.png" width = 50%>
                        
                        <input type = "text" name = "UserName" placeholder = "User Name">
                        <input type = "text" name = "FirstName" placeholder = "First Name">
                        <input type = "text" name = "LastName" placeholder = "Last Name">
                        <input type = "text" name = "Email" placeholder = "Email Address">
                        <input type = "text" name = "Pass" placeholder = "Password">
                        <?php echo $alert; ?>

                        <input type = "submit" name = "Register" value = "REGISTER">
                        </form>

                        <form method = "post">
                        <input type = "submit" name = "ForgotPassword" value = "forgot password?">
                        <p class= "message"> Already registered? <a href="signin.php">Sign in</a></p>
        </form>
</div>
</div>
	</body>
</html>
<?php
require_once 'footer.php';
?>