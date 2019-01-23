<?php
require_once 'header.php';
session_start();
if (!isset($_SESSION['id'])){
  header("location:/signin.php");
}

$servername = "127.0.0.1";
$dusername = "root";
$password = "root";
$dbname = "camagru";
$name = "";

if(isset($_POST['comment']))
      {
        $user = $_SESSION['id'];
        $img = $_POST['img_name'];

        $conn = new PDO("mysql:host=$servername;port=8889;dbname=$dbname", $dusername, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //get the email of the user that the image belongs to
        $getEmail = $conn->prepare("SELECT Email FROM images WHERE img='$img'");
        $getEmail->setFetchMode(PDO::FETCH_ASSOC);
        $getEmail->execute();
        $data=$getEmail->fetch();
        $Email = $data['Email'];

        //save comment into database
        $comment = $_POST['newComment'].PHP_EOL;
        $sql=$conn->prepare("INSERT INTO comments (Email, img, comment) VALUES ('$user','$img','$comment');");
        $sql->execute();

        //check if photo owner has notifications enabled
        $select = $conn->prepare("SELECT Notifications FROM users WHERE Email = '$Email'");
        $select->setFetchMode(PDO::FETCH_ASSOC);
        $select->execute();
        $data=$select->fetch();

        if($data['Notifications'] == 1)
        {
          $message = "$user commented on your photo!";
          mail("$Email", "Someone commented on your photo!", $message, "From: DoNotReply@camagru.com");
        }
      }
if(isset($_POST['like']))
{
  $img = $_POST['img_name'];

  $conn = new PDO("mysql:host=$servername;port=8889;dbname=$dbname", $dusername, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $select = $conn->prepare("UPDATE images SET likes= likes + 1 WHERE img = '$img'");
  $select->execute();

}
if(isset($_POST['delete']))
{
  $user = $_SESSION['id'];
  $img = $_POST['img_name'];

  $conn = new PDO("mysql:host=$servername;port=8889;dbname=$dbname", $dusername, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  //get the email of the user that the image belongs to
  $getEmail = $conn->prepare("SELECT Email FROM images WHERE img='$img'");
  $getEmail->setFetchMode(PDO::FETCH_ASSOC);
  $getEmail->execute();
  $data=$getEmail->fetch();
  $Email = $data['Email'];
  
  if($Email == $user)
  {
    $select = $conn->prepare("DELETE FROM images WHERE img='$img'");
    $select->execute();
    echo "Image successfully deleted.";
  }
  else
  {
    echo "Error: this image does not belong to you.";
  }

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/all.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/prettyPhoto.css" media="screen" />
</head>
<body>
<div id="wrapper">
  <div id="wrapper-top"> </div>
  <div id="left">
    <ul class="menu">
      <li><a href="account.php">Account</a></li>
      <li><a href="upload.php">Upload</a></li>
      <li><a href="logout.php">Logout</a></li>
      <li><a class="twitter-share-button"
      href="https://twitter.com/intent/tweet?text=Camagru.com!"
      data-size="large">
      Tweet</a></li>
      <li><a href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">Facebook</a></li>
    </ul>
    <div id="left-bottom">
      <div class="search">
        <form action="" method="get">
          <fieldset>
            <div class="left">
              <input type="text" value="" />
            </div>
            <div class="right">
              <input type="submit" value="" />
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
  <div id="right">
    <ul class="thumbnails">
      <?php

      $conn = new PDO("mysql:host=$servername;port=8889;dbname=$dbname", $dusername, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $display = $conn->prepare("SELECT * FROM images");
      $display->execute();
      $imgs = $display->fetchAll();
      foreach ($imgs as $img){
          echo '<li><a href="" ><img src="'.$img['img'].'" alt="" width="320" height="240" /></a></li>
          <form action="" method="post">
          <textarea rows="3" style="width:650px;margin-left:auto;margin-right:auto;" class="form-control" name="newComment" required></textarea>
          <input type = "hidden" name = "img_name" value ="' .$img['img'].'">
          <button type="submit" name="comment">Comment</button>
        </form>
        <form action="" method="post">
          <input type = "hidden" name = "img_name" value ="' .$img['img'].'">
          <button type="submit" name="like">Like</button>
          <input type = "hidden" name = "img_name" value ="' .$img['img'].'">
          <button type="submit" name="delete">DELETE</button>
        </form>';

      }
      ?>

    </ul>
    <div class="navigation"> <a href="#" class="prev">Previous</a> <a href="#" class="next">Next</a> </div>
  </div>
  <div id="wrapper-bottom"> </div>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<script type="text/javascript" src="http://cloud.github.com/downloads/malsup/cycle/jquery.cycle.all.latest.js"></script>
<script type="text/javascript" src="js/jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="js/main.js"></script>
</body>
</html>
<?php
require_once 'footer.php';
?>