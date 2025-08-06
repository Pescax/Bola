<?php
require_once "database/Database.php";

$descricao = $_POST["descricao"];
$nome = $_POST["nome"];
$categoria = $_POST ["categoria"];
$foto = $_POST ["imagem"];
$valor = $_POST ["preco"];

    //O endereço do arquivo de imagem está na variável
    //$target_file

    //Instanciar classe do Database
    $db = new Database();

    //Pesquisar se já existe o personagem
    $sql = "SELECT * FROM produtos WHERE nome = '$nome' ";
    $pesquisa = $db->select($sql);

    if( count($pesquisa) == 0 ) {

        if( $_FILES["imagem"]["name"] == "" ) {
            $sql = "INSERT INTO produtos(nome, descricao, categoria, valor) 
                   VALUES ('$nome', '$descricao', '$categoria', $valor)";
        } else {
            require_once "up.php";

            //Criar instrução para o banco de dados
            $sql = "INSERT INTO produtos(nome, descricao, categoria, valor, foto) 
                   VALUES ('$nome', '$descricao', '$categoria', $valor, '$target_file')";
        }

        $db->insert($sql);

        //Mensagem de cadastro bem suscedido
        echo "<script>
                alert('✅ produto cadastrado com sucesso!')
                window.location.href='../telas/admin_produtos.php'
              </script>";
    } else {
        echo "<script>
                alert('❌ produto já cadastrado!!')
                window.location.href='../telas/admin_produtos.php'
              </script>";
    }


