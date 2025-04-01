<?php

require_once('dbConnection.php');

if(isset($_POST['submit'])){
    $name = htmlspecialchars(trim($_POST['name']));
}
if(isset($_POST['submit']) && !empty($_POST['name'])){

    

    $stmt = $pdo->prepare("INSERT INTO user (vorname, nachname, email, passwort) VALUES (:name, :nName, :email, :pass)");
    $stmt->execute([':name' => $name, ':nName'=>'Default', ':email' => "default@mail.com", ':pass' => 'Passwort']);

}else{
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>MF_DB-Test</title>
</head>
<body>
    <h2>Dies ist ein Test</h2>
    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">

<input type="text" name="name">
<input type="submit" name="submit">

    
    </form>
</body>
</html>








<?php
}
?>