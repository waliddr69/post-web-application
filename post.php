<?php

session_start();
require "connect.php";


if(!isset($_SESSION["way"]) && !isset($_SESSION["id_user"])){
    header("Location: welcome.php");exit;
}
    $emptytitle = false;
    $emptypost = false;
    $failed = false;
    $success = false;

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $title = $_POST["title"];
  $post = $_POST["contenu"];
  if(empty($title)){
    $emptytitle = true;
  }
  elseif(empty($post)){
    $emptypost = true;
  }else{
    try{
  $stmt = $conn->prepare("insert into article(title,content,id_user) values(?,?,?)");
  $id_user = $_SESSION["id_user"];
  $stmt->bind_param("ssi",$title,$post,$id_user);
  $stmt->execute();
  $_SESSION["id_article"] = $stmt->insert_id;
  $success = true;
  }catch(mysqli_sql_exception $e){
  
    $failed = true;
    $error_message = $e->getMessage();

 } 
  }
  
  

} ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>post</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand" href="#" style="font-weight: bold;">POST Page</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav w-100 mb-2 mb-lg-0" dir="rtl">
        <li class="nav-item mx-lg-5 my-md-5 my-lg-0">
          <a class="nav-link btn btn-primary " aria-current="page" href="welcome.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link btn btn-primary  " aria-current="page" href="read.php">Read</a>
        </li>
        
        
        
      </ul>
      
    </div>
  </div>
</nav>

<div class="bg-light p-5 rounded shadow text-center container mt-5 d-flex flex-column justify-content-center align-items-center welcome">
  <h1 class="display-4">Welcome!</h1>
  <p class="lead">This is the post page. post whatever coming to your mind (Your day,Education,Technology,Support or Questions)</p>
 
  <form action=""  method="post" class="postform mt-2" >
    
     <div class="container d-flex flex-column justify-content-evenly align-items-center"><h3 style="font-weight: bold;">Write down your post</h3>
    <input type="text" name="title" class="form-control" placeholder="Title" required>
    <textarea name="contenu" class="form-control" placeholder="write a poste"  required></textarea>
    
    <input type="submit" name="post" value="post" class="form-control btn btn-outline-info text-dark" style="width: 70%;">
 </div>
 
  </form>
</div>


<?php if($emptytitle) :?>
  <script>
    let h3 = document.querySelector("h3");
    h3.textContent = "Enter a title";
    h3.style.color = "red";
  </script>
<?php endif; ?>
<?php if($emptypost) :?>
  <script>
    let h3 = document.querySelector("h3");
    h3.textContent = "Enter a post";
    h3.style.color = "red";
  </script>
<?php endif; ?>

<?php if(!$emptypost && !$emptytitle && $success) :?>
  <script>
    const button = document.createElement("button");
    button.classList.add("btn","btn-outline-info","text-dark","mb-5","mt-5");
    button.textContent = "Go to see my post ->";
    button.style.alignSelf = "center";
    button.onclick = ()=>{
      window.location.href = "read.php";
    }
    let h3 = document.querySelector("h3");
    h3.textContent = "Post added succesfully";
    h3.style.color = "green";
    const body = document.querySelector("body");
    body.appendChild(button);
    
  </script>
<?php endif; ?>
<?php  if($failed): ?>


  <script>
    let h3 = document.querySelector("h3");
    h3.textContent = "fail while posting <?= addslashes($error_message) ?>";
    h3.style.color = "red";
  </script>

  <?php endif; ?>
   
 



<script src="js/bootstrap.js"></script>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    
</body>
</html>