<?php
require_once 'header.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>ShutterPress</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/all.css" media="screen" />
<link rel="stylesheet" type="text/css" href="css/prettyPhoto.css" media="screen" />
</head>
<body>
<div id="wrapper">
  <div id="wrapper-top"> </div>
  <div id="left">
    <h1 class="logo"><a href="index.html">ShutterPress</a></h1>
    <ul class="menu">
      <li><a href="account.php">Account</a></li>
      <li><a href="upload.php">Upload</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
    <div id="left-bottom">
      <div class="social"> <span>Social:</span>
        <ul>
          <li><a class="tumblr" href="#">tumblr</a></li>
          <li><a class="picasa" href="#">picasa</a></li>
          <li><a class="vimeo" href="#">vimeo</a></li>
          <li><a class="flickr" href="#">flickr</a></li>
          <li><a class="twitter" href="#">twitter</a></li>
          <li><a class="facebook" href="#">facebook</a></li>
          <li><a class="rss" href="#">rss</a></li>
        </ul>
      </div>
      <div class="search">
        <form action="#" method="get">
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
      $servername = "127.0.0.1";
      $dusername = "root";
      $password = "root";
      $dbname = "camagru";
      $name = "";
      $conn = new PDO("mysql:host=$servername;port=8889;dbname=$dbname", $dusername, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $display = $conn->prepare("SELECT * FROM images");
      $display->execute();
      $imgs = $display->fetchAll();
      foreach ($imgs as $img){
          echo '<li><a href="" ><img src="'.$img['img'].'" alt="" width="150" height="121" /></a></li>';
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