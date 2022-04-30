<?php
session_start();
    require('src/connection.php');
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email   =$_POST['email'];
        $password = $_POST['password'];
        $error =1;
        

        $password = sha1($password."12345");
        $req = $db->prepare('SELECT * FROM users WHERE email=?');
        $req->execute(array($email));
        while ($user = $req->fetch()) {
            if ($password == $user['password']) {
                $_SESSION['connect']=1;
                $_SESSION['pseudo']=$user['pseudo'];
                header('location: ?connect=1');
                if(isset($_POST['connect']))
                {
                    setcookie('log',$user['secret'], time() + 365*24*3600 , '/' ,null,false,true);
                }
                $error = 0;
            }
        }
        if ($error == 1) {
            header('location: ?error=1'); 
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>connexion</title>
    <link rel="stylesheet" href="designe/default.css">
</head>
<body>
    <header>
<h1>connexion</h1>
    </header>
    <?php if(!isset($_SESSION['connect'])) { ?>
    <div class="container">
    <p id="info">bienvenue sur mon site pour avoir plus d'info connectez vous ou <a href="register.php">inscrivez-vous</a> <br> si vous avez pas de compte</p>
    <form action="connexion.php" method="POST">
            <?php
                if(isset($_GET['error']))
                {
                    ?>
                        <p id="error" class="ERSU">email ou mot de passe incorect</p>
                    <?php
                }
                else if(isset($_GET['connect']))
                {
                    ?>
                        <p id="succes" class="ERSU">connecté.</p>
                    <?php
                }
            ?>
            <table>
            <tr>
                <td>Email</td>
                <td><input type="email" name="email" placeholder="exemple@gmail.com"require></td>
            </tr>
            <tr>
                <td>mot de passe</td>
                <td><input type="password" name="password" placeholder="*****" require></td>
            </tr>
        </table>
        <p><input type="checkbox" name="connect" checked> Connection automatique</p>
        <div id="button"><button type="submit">Se connecter</button></div>
    </form>
    </div>
    <?php }else { ?>
        <p id="succes" class="ERSU">Salut <?php echo $_SESSION['pseudo'] ; ?></p>
        <a href="deconnexion.php">Deconnexion</a>
        <?php } ?>
</body>
</html>