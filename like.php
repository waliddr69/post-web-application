<?php

session_start();
require "connect.php";
header('Content-Type: application/json');

if(!isset($_SESSION["id_user"])){
    die("you are not a user");
}

if(!isset($_POST["content"])||!isset($_POST["idarticle"])){
    die("id article is not set");
}

$deletelike = false;
$num = (int)$_POST["content"];
//insert into likes

    $id_article = (int) $_POST["idarticle"];
$data = [
    "deleteLike"=>$deletelike,
    "num"=>$num
];



    $stmt = $conn->prepare("select * from likes where id_user = ? and id_article = ?");
    $stmt->bind_param("ii",$_SESSION["id_user"],$id_article);
    $stmt->execute();
    $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result)>0){
    $stmt = $conn->prepare("delete from likes where id_user = ? and id_article = ?");
    $stmt->bind_param("ii",$_SESSION["id_user"],$id_article);
    $stmt->execute();
    $stmt = $conn->prepare("update article set likes = likes-1 where id= ?");
    $stmt->bind_param("i",$id_article);
    $stmt->execute();
    $deletelike = true;
    $num -=1;
    $data = [
    "deleteLike"=>$deletelike,
    "num"=>$num
];
    echo json_encode($data);
    }else{
$stmt = $conn->prepare("insert into likes(id_user,id_article) values(?,?)");
    $stmt->bind_param("ii",$_SESSION["id_user"],$id_article);
$stmt->execute();
//updates like counter
$stmt = $conn->prepare("update article set likes = likes+1 where id= ?");
$stmt->bind_param("i",$id_article);
$stmt->execute();
$num+=1;
$data = [
    "deleteLike"=>$deletelike,
    "num"=>$num
];
echo json_encode($data);


    }



    



// header("Location: read.php");





?>

