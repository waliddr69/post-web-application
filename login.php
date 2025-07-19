<?php

session_start();
require "connect.php";
$wrongtype = false;
$userexists = false;
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])){
    $way = $_POST["way"];
    $name = $_POST["name"];
    $pass = $_POST["pass"];
    $img = $_FILES["img"];
    if(empty($way) || empty($name) || empty($pass)){
        header("Location: login.php");exit;
    }else{
        $_SESSION["way"] = $way;
        $_SESSION["name"]  = $name;
        
        
        if($img["error"] == 0){
            $allowedtypes = ["image/jpeg","image/png"];
            $imgtype = $img["type"];
            $ext = pathinfo($img["name"],PATHINFO_EXTENSION);
            if(in_array($imgtype,$allowedtypes)){
                $img_name = uniqid() . ".".$ext;
                move_uploaded_file($img["tmp_name"],"uploads/".$img_name);
            }else{
                $wrongtype = true;
            }
        }else{
            $img_name = "profile_pic.jpg";
        }
        
    if(!$wrongtype){
        $_SESSION["img"] = $img_name;
        
        $select = $conn->prepare("select * from user where name = ?");
        $select->bind_param("s",$name);
        $select->execute();
        $result = mysqli_stmt_get_result($select);
        if(mysqli_num_rows($result)>0){
            $userexists = true;
       
         
    }else{
        $stmt = $conn->prepare("insert into user(name,password,img) values(?,?,?)");
        $hashed = password_hash($pass,PASSWORD_DEFAULT);
        $stmt->bind_param("sss",$name,$hashed,$img_name);
        $stmt->execute();
        $_SESSION["id_user"] = $stmt->insert_id;
         if($way == "post"){
        header("Location: post.php");exit;
    }elseif($way == "read"){ 
        header("Location: read.php");exit;
    }
        }
        
            
        
   
    }
    
}}


?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark">
    <header class="bg-primary p-5">
        <h1 class="header text-center text-light fw-bold">
            Welcome to <span class="text-info"></span>
        </h1>
    </header>

    <section>
    <form action="" method="post" class="bg-light px-5" enctype="multipart/form-data" style="height: 80vh;margin:20px 0">
        <h2 style="font-weight: bold;" class="mt-4">choose your way:</h2>
        <small class="form-text text-muted" >username</small><br>
        <input type="text" name="name" class="form-control" required>
        <small class="form-text text-muted">password</small><br>
        <input type="password" name="pass" class="form-control" required>
        <small class="form-text text-muted">profile pic (optional)</small><br>
        <input type="file" name="img" class="form-control">
        <div class="way">
            
            
            
            <label for="post">
            <input type="radio" id="post" name="way"  value="post" required hidden>
            <img src="post.png" alt="post" height="60px" width="60px"><br>
            <p style="font-weight: bold;">post</p>
            </label>
            
            
               
            <label for="read"> 
            <input type="radio" id="read" name="way"  value="read" required  hidden>
            <img src="open-book.png" alt="read" height="60px" width="60px"><br>
            <p style="font-weight: bold;">read</p>
            </label>
        
        </div>
            
        
        
        <p>Already have an account? <a href="signup.php" style="text-decoration: none;" class="text-primary">Sign in</a></p>
        <input type="submit" class="btn btn-outline-info text-dark mb-4" name="submit" value="Log in">
    </form>
    </section>

    

    

    
    <script >
    var typed = new Typed(".text-info",{
    strings:["LearnSphere","MindMatters","The Idea Archive","BrainScroll"],
    typeSpeed:100,
    backSpeed:150,
    loop:true,
});
    </script>

    <?php if($wrongtype) : ?>
    <script>
        let h2 = document.querySelector("h2");
        h2.textContent = "incompatible image type";
        h2.style.color = "red";
    </script>
    <?php endif; ?>
    <?php if($userexists) : ?>
    <script>
        let h2 = document.querySelector("h2");
        h2.textContent = "user already exists";
        h2.style.color = "red";
    </script>
    <?php endif; ?>

    <script src="js/bootstrap.js"></script>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    
</body>
</html>