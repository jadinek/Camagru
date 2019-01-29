<?php
require 'setup.php';
session_start();

try {
  $con = new PDO("mysql:host=$server;", $admin_user, $admin_pass);
  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $camagru = "CREATE DATABASE IF NOT EXISTS camagru DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
  $con->exec($camagru);

  $con = new PDO("mysql:host=$server;dbname=$db", $admin_user, $admin_pass);
  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $users = "CREATE TABLE IF NOT EXISTS users(
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
    $con->exec($users);

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
      img longtext NOT NULL,
			comment varchar(500) NOT NULL
      )";
    $con->exec($comments);
}
catch (PDOException $e){
    echo "Error" .  $e->getMessage();
}
?>