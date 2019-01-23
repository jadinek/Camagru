<?php
require_once 'database.php';
require_once 'header.php';
session_start();
if (!isset($_SESSION['id'])){
  header("location:/signin.php");
}
?>
<!DOCTYPE html>
<html lang = "en">
<head>
<meta charset = "UTF-8">
<style>
    .booth{
        width: 400px;
        background-color: #ddd;
        border: 10px #ccc solid;
        margin-right: 10%;
    }
    .booth-capture-button{
        display: block;
        margin: 10px 0;
        padding: 10px 20px;
        background-color: cornflowerblue;
        color: #fff;
        text-align: center;
        text-decoration: none;
    }
    .upload-photo{
        display: block;
        margin: 10px 0;
        padding: 10px 20px;
        background-color: cornflowerblue;
        color: #fff;
        text-align: center;
        text-decoration: none;
    }
    .covers{
        display: flex;
        float: center;
    }
    .outsideWrapper{ 
        width: 180px;
        border:1px solid blue;
    }
    .insideWrapper{ 
        width: 180px;
        position:relative;
    }
    .coveredImage{ 
        width: 180px;
        position:absolute; top:0px; left:20px;
        margin-left: 45%;
    }
    .coveringCanvas{ 
        width: 180px;
        position:absolute; top:0px; left:20px;
        margin-left: 45%;
    }
</style>
</head>
<body>
    <div class = "superimposable">
        <div class = "covers">
        <div class= "booth">
            <video id = "video" width= "400" height = "300" autoplay></video>
            <a id = "capture" class = "booth-capture-button">Take photo</a>
                        <form id ="export" action="" method="post">
                            <input name="photo" id="merged_image" type="hidden">
                            <input type="submit" name="upload" class="btn2" value="Upload">
                        </form>

            <div class="outsideWrapper">
                <div class="insideWrapper">
                    <img class="coveredImage">

                    <canvas id= "canvas" width= "400" height= "300"></canvas>
                    <canvas class="coveringCanvas"></canvas>
    </div>
</div>
            </div>
            
            <p>
            <img class="stickers" width="200" src= "images/heartcrown.png">
            <img class="stickers" width="200" src= "images/bunny-ears.png">
            <img class="stickers" width="200" src= "images/crown3.png">
            </p>
            <p>
            <img class="stickers" width="200" src= "images/cat-ears.png">
            <img class="stickers" width="200" src= "images/crown.png">
            <img class="stickers" width="200" src= "images/sorting_hat.png">
            <p>
            <img class="stickers" width="200" src= "images/halo.png">
            <img class="stickers" width="200" src= "images/fez.png">
            <img class="stickers" width="200" src= "images/hearthat.png">
            <img class="stickers" width="200" src= "images/case.png">
            </p>
        </div>
        <script>
                var sticker = document.querySelectorAll('.stickers');
                var covered = document.querySelector('.coveredImage');

                sticker.forEach(function(stickers, index){
                    stickers.onclick = function(){
                    covered.src = stickers.src;
                };
            });
            </script>
    </div>
    <script>
    (function(){
        var video = document.getElementById('video'),
        canvas = document.getElementById('canvas'),
        context = canvas.getContext('2d'),
        vendorUrl = window.URL || window.webkitURL;
        navigator.getMedia =    navigator.getUserMedia ||
                                navigator.webkitGetUserMedia ||
                                navigator.mozGetUserMedia ||
                                navigator.msGetUserMedia;
        navigator.getMedia({
            video: true,
            audio: false
        }, function(stream){
            video.srcObject = stream;
            video.play();
        }, function (error){
            //
        });
        var picture;
        document.getElementById('capture').addEventListener('click', function(){
            context.drawImage(video, 0, 0, 400, 300);
            context.drawImage(covered, 90, 0, 200, 150);
            if (picture = canvas.getContext('2d')){
                picture = canvas.toDataURL('image/png');
                
                var upload_img = document.getElementById('merged_image');
                upload_img.value = picture;
                console.log("img upload : " + upload_img.value);
            }
        })
    })();
    
    </script>
    </body>
    <form action="" method="POST" enctype="multipart/form-data">
    <label class="label">Upload a file:</label>
    <input type="file" name="file" class="btn2">
    <input type="submit" name="but_upload" class="btn2" value="Upload">
</form>
    <?php require_once 'footer.php'; ?>
    </html>

<?php
if(isset($_POST['but_upload']))
{
    $currentDir = getcwd();
    $uploads = "upload/";
    $servername = "127.0.0.1";
    $dusername = "root";
    $password = "root";
    $dbname = "camagru";
    $name = "";
    $Email = $_SESSION['id'];

    $fileTmpName = $_FILES['file']['tmp_name'];
 
    $name = $_FILES['file']['name'];
    $target_dir = "upload/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    
    // Select file type
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
    // Valid file extensions
    $extensions_arr = array("jpg","jpeg","png","gif");
    
    // Check extension
    if( in_array($imageFileType,$extensions_arr) ){
    
    // Insert record
    $image_base64 = base64_encode(file_get_contents($fileTmpName));
    $image = 'data:image/' .$imageFileType. ';base64,' .$image_base64;
    $conn = new PDO("mysql:host=$servername;port=8889;dbname=$dbname", $dusername, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $str = "INSERT INTO images (img_name, img, Email) VALUES ('$name', '$image', '$Email')";
    $conn->exec($str);
    // Upload file
    move_uploaded_file($_FILES['file']['tmp_name'],$target_dir.$name);
    }
}
if(isset($_POST['upload']))
{
    $servername = "127.0.0.1";
    $dusername = "root";
    $password = "root";
    $dbname = "camagru";
    $name = "";
    $Email = $_SESSION['id'];
    
    $conn = new PDO("mysql:host=$servername;port=8889;dbname=$dbname", $dusername, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $new_img = $_POST['photo'];
    if (!strlen($new_img) < 50)
    {
        $str = "INSERT INTO images (img_name, img, Email) VALUES ('random', '$new_img', '$Email')";
        $conn->exec($str);
    }
}
//display images under the canvas as thumbnails
$display = $conn->prepare("SELECT * FROM images");
$display->execute();
$imgs = $display->fetchAll();
foreach ($imgs as $img){
    if ($img['Email'] == $_SESSION['id'])
    {
        echo '<img width = 150 src="'.$img['img'].'" alt="Image"/>';
    }
}
?>