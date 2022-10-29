<?php
session_start();
/**
 * Comprova si existeix els segon, si no existeix crea els segons actuals 
 * al mapa de session["hora"], en cas contrari. Si fa més de 60 segons que estàs connectat
 *  destrueix la sessió i et renvia a index.php
 */
if(!isset($_SESSION["hora"])){
    $_SESSION["hora"] = time();
} elseif(time() - $_SESSION["hora"] >= 60) {
    session_destroy();
    header("Location:index.php", true, 303);
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <title>Benvingut</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">

</head>
<body>
<div class="container noheight" id="container">
    <div class="welcome-container">
        <h1>Benvingut!</h1>
        <div>Hola <?php 
        /*Si existeix un nom mostra al nom per saludar al iniciar sessió*/
        if(isset($_SESSION["nom"])){
            echo $_SESSION["nom"];
        }
        ?>, les teves darreres connexions són:</div>
        <form action="process.php" method="post">
            <button name="tanca">Tanca la sessió</button>
        </form>
    </div>
</div>
</body>
</html>