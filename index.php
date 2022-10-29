<?php
session_start();
/**
 * Comprova si existeix els segon, si existeix mira que fa menys 
 * de 60 segons que estàs connectat i et reconnecta a la pàgina 
 * de hola.php, sinó destrueix la sessió
 */
if(isset($_SESSION["hora"])){
    if(time() - $_SESSION["hora"] < 60){
        header("Location:hola.php", true, 302);
    } else {
        session_destroy();
    }
}


/**
 * Llegeix les dades del fitxer. Si el document no existeix torna un array buit.
 *
 * @param string $file
 * @return array
 */
function llegeix(string $file) : array
{
    $var = [];
    if ( file_exists($file) ) {
        $var = json_decode(file_get_contents($file), true);
    }
    return $var;
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <title>Accés</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">

</head>
<body>                  
    <div class="error" <?php //Si no arriba cap error amagar el camp de mostrar l'error
    if(!isset($_GET["error"])){
        echo "style='visibility: hidden;'";
    } else {//Si l'error ve de la sign-up canviem l'estil de mostrar l'error
        if($_GET["error"] == "creacio-fallida"){
            echo "style='color:#FF4B2B;background-color:white';";
        }
    }
    ?>>
    <?php //si arriba un error mostrar el missatge d'error
    if(isset($_GET["error"])){
        echo $_GET["error"];
    } ?>
    </div>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="process.php" method="post">
                <h1>Registra't</h1>
                <span>crea un compte d'usuari</span>
                <input type="hidden" name="method" value="signup"/>
                <input type="text" name="nom"  placeholder="Nom" />
                <input type="email" name="correu"  placeholder="Correu electronic" />
                <input type="password" name="password" placeholder="Contrasenya" />
                <button>Registra't</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="process.php" method="post">
                <h1>Inicia la sessió</h1>
                <span>introdueix les teves credencials</span>
                <input type="hidden" name="method" value="signin"/>
                <input type="email" name="correu" placeholder="Correu electronic" />
                <input type="password" name="password" placeholder="Contrasenya" />
                <button>Inicia la sessió</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Ja tens un compte?</h1>
                    <p>Introdueix les teves dades per connectar-nos de nou</p>
                    <button class="ghost" id="signIn">Inicia la sessió</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Primera vegada per aquí?</h1>
                    <p>Introdueix les teves dades i crea un nou compte d'usuari</p>
                    <button class="ghost" id="signUp">Registra't</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
        container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
        container.classList.remove("right-panel-active");
    });
</script>
</html>