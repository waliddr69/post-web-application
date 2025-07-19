<?php

session_start();

if(!isset($_SESSION["id"])){
    header("Location: welcome.php");
}

echo "<div class='posts mx-5 bg-light p-5 mb-4 rounded mt-5 text-primary'>
    <h3>".htmlspecialchars($_SESSION["title"])."</h3>
    <p>".htmlspecialchars($_SESSION["content"])."</p>
    <p class='text-end'>".htmlspecialchars($_SESSION["date"])."</p>
</div>";


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>result</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
</body>
</html>