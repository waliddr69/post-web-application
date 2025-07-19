<?php

session_start();
require "connect.php";
if(!isset($_SESSION["way"]) && !isset($_SESSION["id_user"])){
    header("Location: login.php");
}
$emptysearch = false;
    $noresults = false;

if(isset($_GET["content"])){
    $search = $_GET["content"];
    

    if(empty($search)){
    $emptysearch = true;
    


    }else{
         $searchParam = "%" . $search . "%";
        $stmt = $conn->prepare("select * from article where title like ? or content like ? ");
        $stmt->bind_param("ss",$searchParam,$searchParam);
        $stmt->execute();
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)){
                $_SESSION["id"] = $row["id"];
                $_SESSION["title"] = $row["title"];
                $_SESSION["content"] = $row["content"];
                $_SESSION["date"] = $row["date"];
                header("Location: result.php?content=" . urlencode($search));exit;
            }
        }else{
            $noresults = true;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand" href="#" style="font-weight: bold;">READ Page</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav w-100 mb-2 mb-lg-0" dir="rtl">
        <li class="nav-item mx-lg-5 my-md-5 my-lg-0">
          <a class="nav-link btn btn-primary " aria-current="page" href="login.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link btn btn-primary  " aria-current="page" href="post.php">Post</a>
        </li>
        
        
        
      </ul>
      
    </div>
  </div>
</nav>
<div class="bg-light p-5 rounded shadow text-center container mt-5 d-flex flex-column justify-content-center align-items-center welcome">
  <h1 class="display-4">Welcome!</h1>
  <p class="lead">This is the read page. read and search about any post (Your day,Education,Technology,Support or Questions)</p>
 
  <form action=""  method="get" class="postform mt-2" >
    
    
     <div class="container d-flex flex-column justify-content-evenly align-items-center">
        <h3 style="font-weight: bold;">Search down your post</h3>
        <div class="input-group">
            <div class="input-prepend">
                <span class="input-group-text"><img src="search-svgrepo-com.svg" alt="" width="30px" height="30px"></span>
            </div>
            <input type="search" name="content" class="form-control" placeholder="Search" required>
        </div>
    
    
    
    <input type="submit" name="search" value="search" class="form-control btn btn-outline-info text-dark" style="width: 70%;">
 </div>
 
  </form>
</div>

    <h2 class="mt-5 mx-5 mb-3" style="font-weight: bold;">Posts</h2>

    <?php if($noresults): ?>
        <script>
            let h3 = document.querySelector("h3");
            h3.textContent = "no results were found"

        </script>
    <?php endif; ?>
    <?php if($emptysearch): ?>
        <script>
            let h3 = document.querySelector("h3");
            h3.textContent = "enter a search content"
            h3.style.color = "red";

        </script>
    <?php endif; ?>
        
        <script src="js/jquery-3.7.1.min.js"></script>
    
</body>
<?php



$stmt = $conn->prepare("select * from article JOIN user ON article.id_user = user.id_user order by article.id DESC");
$stmt->execute();
$result = mysqli_stmt_get_result($stmt);
if(mysqli_num_rows($result)>0){
    while($row = mysqli_fetch_assoc($result)){
            $liked = false;
        $check = $conn->prepare("SELECT * FROM likes WHERE id_user = ? AND id_article = ?");
        $check->bind_param("ii", $_SESSION["id_user"], $row["id"]);
        $check->execute();
        $like_result = $check->get_result();
        

        //Set heart icon color based on whether user liked it
        $heartColor =  $liked ? "heart liked" : "heart";
        
        
    echo "<div class='posts mx-5 bg-light p-5 mb-4 rounded'>
        <div class='img-container rounded-circle mb-4 d-flex align-items-center w-100 ' >
            <img src='uploads/".htmlspecialchars($row["img"])."'  class=' rounded-circle' style='object-fit: cover;' height='60px' width='60px'>
            
                <p class='lead mx-4 ' style='width: 50%'>@".htmlspecialchars($row["name"])."</p>
            
        </div>
    <h3>".htmlspecialchars($row["title"])."</h3>
    <p>".htmlspecialchars($row["content"])."</p>
    <form action='like.php' method='post' style='width:0;height:0' class='like-form'>
        <input type='number' name='idarticle' value=".htmlspecialchars($row["id"])." hidden id='id'>
        <button class='like d-flex align-items-center' type='submit'>
    
        <img src='heart-svgrepo-com.svg' alt='' class = 'heart' style = '$heartColor'>
        <span class='num'>".htmlspecialchars($row["likes"])."</span></button>

    </form>
    <p class='text-end'>".htmlspecialchars($row["date"])."</p>
</div>";
    }
}else{
    echo "<h3>No Posts for now </h3>";
}



?>
<script>
            $(function(){
              $(".like-form").submit(function(e){
                e.preventDefault();
                var form = $(this);
                 var id = form.find("input[name='idarticle']").val(); // article ID
                var span = form.find(".num");
                var currentLikes = parseInt(span.text());
                var heart = form.find(".heart");

                $.post("like.php",{idarticle:id, content: currentLikes},function(data,status){
                    span.text(data.num);
                    
                    if(data.deleteLike){
                        heart.removeClass("liked");
                    }else{
                        heart.addClass("liked");
                    }

                },"json")
            })  
            })
    
</script>


</html>


