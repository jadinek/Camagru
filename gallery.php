<?php
    if ($_POST){
        $num = array_search('Delete Image', $_POST);
        $statement = "DELETE FROM `images` WHERE imageID = ".toQuote($num);
        $db->runStatement($db->getDBConn(),$statement);
    }
    $imagelimit = 5;
    $out2 = $db->returnRecord("SELECT * FROM images WHERE username = ".toQuote($_SESSION["username"]));
    $total = count($out2);
    if(isset($_GET["page"])){
        $page = $_GET["page"];
        $i = ($_GET["page"] - 1 )* $imagelimit;
    }
    else{
        $i = 0;
        $page = 1;
    }
    $pages = ceil($total / $imagelimit);
    echo "<div class='centerdiv' style='top:170%'>";
    while ($i < $imagelimit*$page && $out2[$i]){
        echo "<div class='imagediv'><img src=".$out2[$i]["image"]."></div>";
        echo "<br><form method='post' action=''><input type='submit' name='".$out2[$i]["imageID"]."' class='button is-dark' value='Wipe out of memory banks?'></form>";
        $i++;
    }
    echo "<br><div class='imagediv' style='bottom:0%'>";
    for ($x = 1; $x <= $pages; $x++){
        echo "<a href='gallery.php?page=$x'>$x</a>"."\t";
    }
    echo "</div>";
    echo "</div>";
?>

</html>