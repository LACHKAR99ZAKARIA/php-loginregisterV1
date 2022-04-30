
<?php
    require('src/connection.php');

    if (isset($_POST['pseudo']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_confirm'])) {
        $pseudo           = $_POST['pseudo'];
        $email            = $_POST['email'];
        $password         = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        if ($password != $password_confirm) {
            header('location: ?error=1&pass=1');
        }
        $req = $db->prepare("SELECT count (*) as numberemail FROM users WHERE email=?");
        $req->execute(array($email));
        while ($email_verification = $req->fetch()) {
            if ($email_verification['numberemail'] !=0) {
                header('location: ?error=1&email=1');
            }
        }
        $secret = sha1($email).time();
        $password = sha1($password."12345");
        $req = $db->prepare("INSERT INTO users(pseudo,email,password,secret) VALUES(?,?,?,?)");
        $req->execute(array($pseudo,$email,$password,$secret));
        header('location: ?success=1');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <link rel="stylesheet" href="designe/default.css">
</head>
<body>
    <header>
    <h1>Register</h1>
    </header>
    <div class="container">
    <p id="info">bienvenue sur mon site pour avoir plus d'info inscrivez vous ou <a href="connexion.php">connectez-vous</a></p>
    <form action="register.php" method="POST">
        <?php
            if(isset($_GET['error']))
            {
                if(isset($_GET['pass']))
                {
                    ?>
                        <p id="error" class="ERSU">Les mots de passe ne sont pas identique</p>
                    <?php
                }
                else
                if(isset($_GET['email']))
                {
                    ?>
                        <p id="error" class="ERSU">l'email existe déja</p>
                    <?php
                }
            }
            else 
            if(isset($_GET['success']))
            {
                ?>
                    <p id="succes" class="ERSU">Inscription Bien prise en compte.</p>
                <?php
            }
        ?>
        <table>
            <tr>
                <td>Pseudo</td>
                <td><input type="text" name="pseudo" placeholder="Pseudo" require></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input type="email" name="email" placeholder="exemple@gmail.com" require></td>
            </tr>
            <tr>
                <td>mot de passe</td>
                <td><input type="password" name="password" placeholder="*****" require></td>
            </tr>
            <tr>
                <td>retapez le mot de passe</td>
                <td><input type="password" name="password_confirm" placeholder="*****" require></td>
            </tr>
        </table>
        <div id="button"><button type="submit">S'inscrire</button></div>
    </form>
    </div>
</body>
</html>