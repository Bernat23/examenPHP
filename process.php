<?php 
//comprova si es un signup


if(isset($_POST["method"])) {
    if($_POST["method"] == "signup"){
        $credencials= array();
        $credencials["nom"] = $_POST["nom"];
        $credencials["correu"] = $_POST["correu"];
        $credencials["password"] = $_POST["password"];
        $registrats = llegeix("users.json");
        $existeix = false;
        //Comprova que el correu no existeixi abans de crearlo de nou
         if(array_key_exists($_POST["correu"], $registrats)){
            header("Location:index.php", true, 303);
        } else {//si no crea un usuari nou
            $registrats[$_POST["correu"]] = $credencials;
            escriu($registrats,"users.json");
            header("Location:index.php", true, 302);
        }

        //comprova si es un signin
    } elseif ($_POST["method"] == "signin"){
        $credencials = array();
        $credencials = $_POST["correu"];
        $credencials = $_POST["password"];
        $registrats = llegeix("users.json");
        echo $registrats[$_POST["correu"]]["nom"];
        //Comprova que l'usuari existeixi
        if(array_key_exists($_POST["correu"], $registrats)){
            //Comprova que la contrasenya sigui correcte
            if($registrats[$_POST["correu"]]["password"] ==  $_POST["password"]){
                header("Location:hola.php?nom=" . $registrats[$_POST["correu"]]["nom"] , true, 302);
            }
            else {
                header("Location:index.php", true, 303);
            }
        } 
        else {
            header("Location:index.php", true, 303);
        } 
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

/**
 * Guarda les dades a un fitxer
 *
 * @param array $dades
 * @param string $file
 */
function escriu(array $dades, string $file): void
{
    file_put_contents($file,json_encode($dades, JSON_PRETTY_PRINT));
}

?>