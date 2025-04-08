<?php
try{
    $dsn = "mysql:host=localhost;dbname=myfinance;charset=utf8";
    $pdo = new PDO($dsn,"root","");


}catch (PDOException $e){
    echo $e->getMessage();
    die ("<br>Ein Fehler bei der Datenbankverbindung ist aufgetreten :(");
}
?>