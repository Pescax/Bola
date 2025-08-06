<?php

require_once "database/Database.php";

$usuario = $_POST["usuario"];
$nome = $_POST["nome"];
$senha = $_POST ["senha"];
$senha = sha1("senha");
    //O endereço do arquivo de imagem está na variável
    //$target_file

    //Instanciar classe do Database
    $db = new Database();

    //Pesquisar se já existe o personagem
    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' ";
    $pesquisa = $db->select($sql);

    if( count($pesquisa) == 0 ) {
        //Criar instrução para o banco de dados
        $sql = "INSERT INTO usuarios(nome, senha, tipo, usuario)
                VALUES('$nome', '$senha', 'cliente', '$usuario')";

        $db->insert($sql);

        //Mensagem de cadastro bem suscedido
        echo "<script>
                alert('✅ Usuário cadastrado com sucesso!')
                window.location.href='../telas/index.php'
              </script>";
    } else {
        echo "<script>
                alert('❌ Usuário já cadastrado!!')
                window.location.replace('../telas/cadastro.php')
              </script>";
    }

?>