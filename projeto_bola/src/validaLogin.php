<?php

if( isset($_POST["usuario"]) && isset($_POST["senha"]) ){
    require_once "database/Database.php";
    
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];
    $login = false;

    $db = new Database();

    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";

    $result = $db->select($sql);

    //var_dump($result);
   
    if( count( $result ) > 0 ) {
        if( $result[0]->senha == sha1($senha) ) {
            session_start();
            $_SESSION["autenticado"] = $result;
            $_SESSION["carrinho"] = array();
            $login = true;
        }
    } 
    
    if($login) {
        if( isset($_POST["admin"]) ) {
            header("location: ../telas/admin_produtos.php");
        } else {
            header("location: ../telas/loja.php");
        }
    } else {
        echo "<script>
                alert('Acesso negado!')
                window.location.replace('../telas/index.php')
              </script>";
    }
    
}