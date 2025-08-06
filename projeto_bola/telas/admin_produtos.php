<?php
    session_start();

   //var_dump( isset($_SESSION["autenticado"]) );

    if ( !isset($_SESSION["autenticado"]) ) {
        echo "<script> window.location.replace('../telas/index.php') </script>";
    }

require_once "../src/database/Database.php";

$db = new Database();

$sql = "SELECT * FROM produtos";

$result = $db->select($sql);

//var_dump($result);

// Arquivo JSON para armazenar os produtos
$produtosFile = __DIR__ . '/produtos.json';

// Função para ler os produtos do arquivo JSON
function lerProdutos() {
    global $produtosFile;
    if (!file_exists($produtosFile)) {
        file_put_contents($produtosFile, json_encode([]));
    }
    $json = file_get_contents($produtosFile);
    return json_decode($json, true);
}

// Função para salvar os produtos no arquivo JSON
function salvarProdutos($produtos) {
    global $produtosFile;
    file_put_contents($produtosFile, json_encode($produtos, JSON_PRETTY_PRINT));
}

// Processar requisições POST para adicionar, editar ou remover produtos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produtos = lerProdutos();

    if (isset($_POST['acao'])) {
        $acao = $_POST['acao'];

        if ($acao === 'adicionar') {
            $novoProduto = [
                'id' => time(),
                'nome' => $_POST['nome'],
                'descricao' => $_POST['descricao'],
                'preco' => floatval($_POST['preco']),
                'categoria' => $_POST['categoria'],
                'imagem' => $_POST['imagem']
            ];
            $produtos[] = $novoProduto;
            salvarProdutos($produtos);
        } elseif ($acao === 'editar' && isset($_POST['id'])) {
            foreach ($produtos as &$produto) {
                if ($produto['id'] == $_POST['id']) {
                    $produto['nome'] = $_POST['nome'];
                    $produto['descricao'] = $_POST['descricao'];
                    $produto['preco'] = floatval($_POST['preco']);
                    $produto['categoria'] = $_POST['categoria'];
                    $produto['imagem'] = $_POST['imagem'];
                    break;
                }
            }
            salvarProdutos($produtos);
        } elseif ($acao === 'remover' && isset($_POST['id'])) {
            $produtos = array_filter($produtos, function($p) {
                return $p['id'] != $_POST['id'];
            });
            salvarProdutos(array_values($produtos));
        }
    }
    header('Location: admin_produtos.php');
    exit();
}

$produtos = lerProdutos();
$categorias = ['bovino', 'suino', 'aves', 'embutidos'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Administração de Produtos - Açougue</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5ddb6;
            margin: 0;
            padding: 20px;
            color: #3c1f13;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            background-color: #e5b57b;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 8px 6px;
            margin-top: 5px;
            border: 1px solid #983422;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            margin-top: 15px;
            background-color: #983422;
            color: #f5ddb6;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #812c1dff;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #e5b57b;
            border-radius: 8px;
            overflow: hidden;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #d9b3b3;
            text-align: center;
        }
        th {
            background-color: #983422;
            color: #f5ddb6;
        }
        td img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }
        .acoes button {
            margin: 5px 5px;
            padding: 6px 10px;
            font-size: 14px;
        }
        .form-editar {
            display: none;
            background-color: #f0d9b6;
            padding: 10px;
            border-radius: 6px;
            margin-top: 10px;
        }
    </style>
    <script>
        function mostrarFormularioEditar(id) {
            const form = document.getElementById('editar-' + id);
            if (form.style.display === 'block') {
                form.style.display = 'none';
            } else {
                form.style.display = 'block';
            }
        }
    </script>
</head>
<body>
    <nav>
    <ul style="display: flex; align-items: center; gap: 15px;">
        <li onclick="window.location.href='../src/logout.php'" style="cursor:pointer; background-color: #db6938ff;; padding: 8px 12px; border-radius: 5px; font-weight: bold; margin-left: auto;">Sair</li>
    </nav>

    <h1>Administração de Produtos</h1>

    <form method="POST" action="../src/cadastroProdutos.php" enctype="multipart/form-data">
        <input type="hidden" name="acao" value="adicionar" />
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required />

        <label for="descricao">Descrição:</label>
        <input type="text" id="descricao" name="descricao" required />

        <label for="preco">Preço (R$):</label>
        <input type="number" id="preco" name="preco" step="0.01" min="0" required />

        <label for="categoria">Categoria:</label>
        <select id="categoria" name="categoria" required>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>"><?= ucfirst(htmlspecialchars($cat)) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="imagem">URL da Imagem:</label>
        <input type="file" id="imagem" name="imagem" required /><br><br>

        <button type="submit">Adicionar Produto</button>
        <button onclick="window.location.href='loja.php'" style="margin-left: 295px">loja</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço (R$)</th>
                <th>Categoria</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $produto): ?>
            <tr>
                <td><img src="<?= htmlspecialchars($produto->foto) ?>" alt="<?= htmlspecialchars($produto->nome) ?>" /></td>
                <td><?= htmlspecialchars($produto->nome) ?></td>
                <td><?= htmlspecialchars($produto->descricao) ?></td>
                <td><?= number_format($produto->valor, 2, ',', '.') ?></td>
                <td><?= htmlspecialchars(ucfirst($produto->categoria)) ?></td>
                
            </tr>
            
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
