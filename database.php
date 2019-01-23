<?php
session_start();
require 'dbconfig.php';


try {
  $second = new PDO("mysql:host=$host;port=8889;", $admin_user, $admin_pass);
  $second->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
  $second->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $orders = "CREATE DATABASE IF NOT EXISTS camagru DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
  $second->exec($orders);

  $con = new PDO("mysql:host=$host;port=8889;dbname=camagru", $admin_user, $admin_pass);
  $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $orders = "CREATE TABLE IF NOT EXISTS users(
    UserID int(11) UNSIGNED AUTO_INCREMENT not null PRIMARY KEY,
    UserName varchar(32) not null,
    FirstName varchar(32) not null,
    LastName varchar(32) not null,
    Email varchar(32) not null UNIQUE,
    Pass varchar(32) not null,
    Confirmed int(11) not null,
    ConfirmCode int(30) not null,
    forgot int(2) not null DEFAULT '0',
    Notifications int(1) not null DEFAULT '1'
    )";
    $con->exec($orders);

    $images = "CREATE TABLE IF NOT EXISTS images ( 
      ImageID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
      img_name varchar(200),
      img longtext NOT NULL,
      Email varchar(32) NOT NULL,
      uploaddate datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			likes INT(2) NOT NULL DEFAULT '0'
      )";
    $con->exec($images);

    $comments = "CREATE TABLE IF NOT EXISTS comments ( 
      ID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
      Email varchar(32) NOT NULL,
      img_name varchar(200) NOT NULL,
			comment varchar(500) NOT NULL
      )";
    $con->exec($comments);
}
catch (PDOException $e){
    echo "Error" .  $e->getMessage();
}
?>