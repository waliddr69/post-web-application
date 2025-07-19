<?php

try{
    $conn = mysqli_connect("localhost","root","","Article");
}catch(mysqli_sql_exception){
    die("<h1>error while connecting to the database</h1>");
}

?>