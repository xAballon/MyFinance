<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>

<?php
require_once('dbConnection.php');

if(isset($_POST['submit']) && !empty($_POST['email'])){

    $email = htmlspecialchars(trim($_POST['email']));

    $stmt = $pdo->prepare("SELECT COUNT(email) FROM user WHERE email = :email");
    $stmt->execute([':email' => $email]);
  
    
    $row = $stmt->fetchColumn();
    //Anmeldeformular
    if($row > 0){
        echo "//Nutzer existiert bereits";
?>
        <body>
    <h2>Wilkommen Bei MyFinance</h2>
    

    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post" class="login">
    <table>
    <tr>
        <td>
            <label for="email">Email: </label>
        </td>
        <td>
        <input type="email" name="email" value='<?php echo $email; ?>'>
        </td>
    </tr>
    <tr>
    <td>
            <label for="pass">Passwort: </label>
        </td>
        <td>
        <input type="password" name="pass">
        </td>
    </tr>    

    </table>
        <input type="submit" name="submit">
    </form>
    
</body>
</html>

<?php
    //Regristrierformular
    }else{
        echo "//Nutzer existiert noch nicht";
    ?>
<body>
    <h2>Wilkommen Bei MyFinance</h2>
    

    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post" class="login">
    <table>
    <tr>
        <td>
            <label for="email">Email: </label>
        </td>
        <td>
        <input type="email" name="email" value="<?php echo $email ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for="vorname">Vorname: </label>
        </td>
        <td>
        <input type="text" name="vName">
        </td>
    </tr>
    <tr>
       <td>
            <label for="nachname">Nachname: </label>
        </td>
        <td>
        <input type="text" name="nName">
        </td>
    </tr>
    <tr>
        <td>
            <label for="pass">Passwort: </label>
        </td>
        <td>
        <input type="pass" name="email" value="<?php echo $email ?>">
        </td>
    </tr>
    <tr>
        <td>
            <label for="email">Email: </label>
        </td>
        <td>
        <input type="email" name="email" value="<?php echo $email ?>">
        </td>
    </tr>
    </table>
        <input type="submit" name="submit">
    </form>
    
</body>
</html>

    
    
    
   <?php 
    }



}else{

?>

<body>
    <h2>Wilkommen Bei MyFinance</h2>
    

    <form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post" class="login">
    <table>
    <tr>
        <td>
            <label for="email">Email: </label>
        </td>
        <td>
        <input type="email" name="email">
        </td>
    </tr>
    </table>
        <input type="submit" name="submit">
    </form>
    
</body>
</html>

<?php
}
?>