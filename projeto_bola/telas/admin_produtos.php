<?php
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
    <h1>Administração de Produtos</h1>

    <form method="POST" action="admin_produtos.php">
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
        <input type="text" id="imagem" name="imagem" required />

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
            <?php foreach ($produtos as $produto): ?>
            <tr>
                <td><img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>" /></td>
                <td><?= htmlspecialchars($produto['nome']) ?></td>
                <td><?= htmlspecialchars($produto['descricao']) ?></td>
                <td><?= number_format($produto['preco'], 2, ',', '.') ?></td>
                <td><?= htmlspecialchars(ucfirst($produto['categoria'])) ?></td>
                <td class="acoes">
                    <button onclick="mostrarFormularioEditar(<?= $produto['id'] ?>)" >Editar</button>
                    <form method="POST" action="admin_produtos.php" style="display:inline;">
                        <input type="hidden" name="acao" value="remover" />
                        <input type="hidden" name="id" value="<?= $produto['id'] ?>" />
                        <button type="submit" onclick="return confirm('Tem certeza que deseja remover este produto?')">Remover</button>
                    </form>
                </td>
            </tr>
            <tr id="editar-<?= $produto['id'] ?>" class="form-editar">
                <td colspan="6">
                    <form method="POST" action="admin_produtos.php">
                        <input type="hidden" name="acao" value="editar" />
                        <input type="hidden" name="id" value="<?= $produto['id'] ?>" />

                        <label for="nome-<?= $produto['id'] ?>">Nome:</label>
                        <input type="text" id="nome-<?= $produto['id'] ?>" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required />

                        <label for="descricao-<?= $produto['id'] ?>">Descrição:</label>
                        <input type="text" id="descricao-<?= $produto['id'] ?>" name="descricao" value="<?= htmlspecialchars($produto['descricao']) ?>" required />

                        <label for="preco-<?= $produto['id'] ?>">Preço (R$):</label>
                        <input type="number" id="preco-<?= $produto['id'] ?>" name="preco" step="0.01" min="0" value="<?= number_format($produto['preco'], 2, '.', '') ?>" required />

                        <label for="categoria-<?= $produto['id'] ?>">Categoria:</label>
                        <select id="categoria-<?= $produto['id'] ?>" name="categoria" required>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= htmlspecialchars($cat) ?>" <?= $produto['categoria'] === $cat ? 'selected' : '' ?>><?= ucfirst(htmlspecialchars($cat)) ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label for="imagem-<?= $produto['id'] ?>">URL da Imagem:</label>
                        <input type="text" id="imagem-<?= $produto['id'] ?>" name="imagem" value="<?= htmlspecialchars($produto['imagem']) ?>" required />

                        <button type="submit">Salvar Alterações</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
