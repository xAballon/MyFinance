<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
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



    </form>
    
</body>
</html>