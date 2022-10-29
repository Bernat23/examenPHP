<?php 
session_start();

/**
 * Comprova que existeix un mètode, i els processa
 */
if(isset($_POST["method"])) {
    $accessos = array();
    $accessos = llegeix("connexions.json");
    /**
     * Si el mètode és de registrar, crea un mapa per guardar les credencials,
     * llegeix el fitxer d'usuaris.
     */
    if($_POST["method"] == "signup"){
        $credencials= array();
        $credencials["nom"] = $_POST["nom"];
        $credencials["correu"] = $_POST["correu"];
        $credencials["password"] = $_POST["password"];
        $registrats = llegeix("users.json");
        $existeix = false;
        /** Comprova que el correu no existeixi abans de crear-lo de nou, si existeix
         * ens envia a la pàgina principal, envia un missatge d'error i guarda la connexió
         * fallida, si no crea un usuari nou, guarda la connexió i el nou usuari i et 
         * reenvia a hola.php
         */ 

         if(array_key_exists($_POST["correu"], $registrats)){
            $accessos[] = ["ip"=> $_SERVER['REMOTE_ADDR'], "user" => $_POST["correu"], "time" => date('Y-m-d H:i:s'), "status" => "creacio-fallida"];
            escriu($accessos, "connexions.json");
            header("Location:index.php?error=creacio-fallida", true, 303);
        } else {
            $registrats[$_POST["correu"]] = $credencials;
            escriu($registrats,"users.json");
            $_SESSION["nom"] = $registrats[$_POST["correu"]]["nom"];
            $accessos[] = ["ip"=> $_SERVER['REMOTE_ADDR'], "user" => $_POST["correu"], "time" => date('Y-m-d H:i:s'), "status" => "nou-usuari"];
            escriu($accessos, "connexions.json");
            header("Location:hola.php" , true, 302);
        }
        /** 
         * Comprova si es un signin, si ho és llegeix la llista 
         * d'usuari i processa el signin 
         */
    } elseif ($_POST["method"] == "signin"){
        $credencials = array();
        $credencials = $_POST["correu"];
        $credencials = $_POST["password"];
        $registrats = llegeix("users.json");
        /**
         * Comprova que l'usuari existeixi, si existeix comprova la contrasenya
         * amb el mapa tret del json, si és correcte ens envia a la pàgina hola.php
         * i guarda la connexió, si el correu no existeix ens retorna a index.php
         * i guarda l'intent de connexió, si la contrasenya és incorrecte passa el mateix 
         * que si el correu no existeix, però l'error és diferent
         */
         if(array_key_exists($_POST["correu"], $registrats)){
            if($registrats[$_POST["correu"]]["password"] ==  $_POST["password"]){
                $_SESSION["nom"] = $registrats[$_POST["correu"]]["nom"];
                $accessos[] = ["ip"=> $_SERVER['REMOTE_ADDR'], "user" => $_POST["correu"], "time" => date('Y-m-d H:i:s'), "status" => "correcte"];
                escriu($accessos, "connexions.json");
                header("Location:hola.php" , true, 302);
            }
            else {
                $accessos[] = ["ip"=> $_SERVER['REMOTE_ADDR'], "user" => $_POST["correu"], "time" => date('Y-m-d H:i:s'), "status" => "contrasenya-incorrecte"];
                escriu($accessos, "connexions.json");
                header("Location:index.php?error=contrasenya-incorrecte", true, 303);
            }
        } else {
            $accessos[] = ["ip"=> $_SERVER['REMOTE_ADDR'], "user" => $_POST["correu"], "time" => date('Y-m-d H:i:s'), "status" => "usuari-incorrecte"];
            escriu($accessos, "connexions.json");
            header("Location:index.php?error=usuari-incorrecte", true, 303);
        } 
    }
    /**
     * Comprova si s'ha clicat el botó de tancar la sessió, 
     * si s'ha clicat destrueix la sessió i ens reenvia a index.php
     */
} elseif(isset($_POST["tanca"])){
    session_destroy();
    header("Location:index.php", true, 302);
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