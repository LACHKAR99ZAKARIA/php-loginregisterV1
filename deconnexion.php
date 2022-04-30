<?php
    session_start();//lancer la sesion

    session_unset();//desacriver la sesion

    session_destroy(); //detruire la sesion

    header('location: /Project2/connexion.php');
?>