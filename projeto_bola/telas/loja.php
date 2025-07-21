<?php
// Tela de loja com tema açougue inspirada no iFood
// Espaço para personalização indicado nos comentários abaixo

// Carregar produtos do arquivo JSON
$produtosJson = file_get_contents(__DIR__ . '/produtos.json');
$produtosArray = json_decode($produtosJson, true);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Loja Açougue - Seu Açougue Online</title>
    <style>
        /* Estilo básico tema açougue */
        body {
            font-family: Arial, sans-serif;
            background-color: #fff8f0;
            margin: 0;
            padding: 0;
            color: #e5b57b;
        }
        header {
            background-color: #983422; /* vermelho escuro */
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        header .logo {
            display: flex;
            align-items: center;
        }
        header .logo img {
            height: 50px;
            margin-right: 15px;
        }
        header .logo h1 {
            font-size: 1.8em;
            margin: 0;
        }
        nav {
            background-color: #e5b57b; /* marrom avermelhado */
            padding: 10px 20px;
        }
        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 15px;
        }
        nav ul li {
            cursor: pointer;
            padding: 8px 12px;
            background-color: #3c1f13;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        nav ul li:hover {
            background-color: #d65a5a;
        }
        main {
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            background-color: #f5ddb6;
            min-height: 80vh;
        }
        .produto {
            background-color: #e5b57b;
            border: 1px solid #d9b3b3;
            border-radius: 8px;
            width: 220px;
            height: 350px;
            box-shadow: 2px 2px 6px rgba(139,0,0,0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
            transition: transform 0.2s ease;
        }
        .produto:hover {
            transform: scale(1.05);
            box-shadow: 3px 3px 10px rgba(139,0,0,0.4);
        }
        .produto img {
            width: 180px;
            height: 140px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 10px;
        }
        .produto h3 {
            margin: 0 0 8px 0;
            color: #3c1f13;
        }
        .produto p {
            font-size: 0.9em;
            color: #5a2a2a;
            flex-grow: 1;
            text-align: center;
        }
        .produto .preco {
            font-weight: bold;
            margin: 10px 0;
            color: #a52a2a;
            font-size: 1.1em;
        }
        .produto button {
            background-color: #8b0000;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .produto button:hover {
            background-color: #b22222;
        }

        .titulo{
            color: #e5b57b;
        }
        /* Espaço para personalização: cores, fontes, layout */
    </style>
</head>
<body>

<header>
    <div class="logo">
        <!-- Espaço para personalizar logo: substitua o src pela logo do açougue -->
<img src="../img/bola.png" alt="Logo Açougue" />
        <h1 class="titulo">Açougue do Bola</h1>
    </div>
    <!-- Botão de carrinho funcional -->
    <div class="botao-carrinho" onclick="abrirCarrinho()" title="Abrir Carrinho" style="cursor:pointer; position: relative; font-size: 24px; color: white;">
        🛒
        <span id="contador-carrinho" style="position: absolute; top: -8px; right: -10px; background: #b22222; color: white; border-radius: 50%; padding: 2px 6px; font-size: 14px; font-weight: bold;">0</span>
    </div>
</header>

<nav>
    <ul style="display: flex; align-items: center; gap: 15px;">
        <li onclick="filtrarCategoria('todas')" style="cursor:pointer; padding: 8px 12px; background-color: #3c1f13; border-radius: 5px; transition: background-color 0.3s ease;">Todas</li>
        <li onclick="filtrarCategoria('bovino')" style="cursor:pointer; padding: 8px 12px; background-color: #3c1f13; border-radius: 5px; transition: background-color 0.3s ease;">Bovino</li>
        <li onclick="filtrarCategoria('suino')" style="cursor:pointer; padding: 8px 12px; background-color: #3c1f13; border-radius: 5px; transition: background-color 0.3s ease;">Suíno</li>
        <li onclick="filtrarCategoria('aves')" style="cursor:pointer; padding: 8px 12px; background-color: #3c1f13; border-radius: 5px; transition: background-color 0.3s ease;">Aves</li>
        <li onclick="filtrarCategoria('embutidos')" style="cursor:pointer; padding: 8px 12px; background-color: #3c1f13; border-radius: 5px; transition: background-color 0.3s ease;">Embutidos</li>
        <li onclick="window.location.href='index.php'" style="cursor:pointer; background-color: #3c1f13;; padding: 8px 12px; border-radius: 5px; font-weight: bold; margin-left: auto;">Sair</li>
</nav>

<main id="produtos-container">
    <!-- Produtos serão carregados aqui via JavaScript -->
</main>

<script>
    // Dados de exemplo dos produtos - espaço para personalização ou integração backend
    // Removido array fixo de produtos para carregar via PHP
    const produtos = <?php echo json_encode($produtosArray); ?>;

    // Função para renderizar produtos filtrados
    function renderizarProdutos(categoria) {
        const container = document.getElementById('produtos-container');
        container.innerHTML = '';

        const filtrados = categoria === 'todas' ? produtos : produtos.filter(p => p.categoria === categoria);

        filtrados.forEach(produto => {
            const card = document.createElement('div');
            card.className = 'produto';

            card.innerHTML = `
                <img src="${produto.imagem}" alt="${produto.nome}" />
                <h3>${produto.nome}</h3>
                <p>${produto.descricao}</p>
                <div class="preco">R$ ${produto.preco.toFixed(2)} kg</div>
                <button onclick="adicionarAoCarrinho(${produto.id})">Adicionar ao carrinho</button>
            `;

            container.appendChild(card);
        });
    }

    // Função para obter o carrinho do localStorage
    function obterCarrinho() {
        const carrinhoJSON = localStorage.getItem('carrinho');
        return carrinhoJSON ? JSON.parse(carrinhoJSON) : [];
    }

    // Função para salvar o carrinho no localStorage
    function salvarCarrinho(carrinho) {
        localStorage.setItem('carrinho', JSON.stringify(carrinho));
    }

    // Atualiza o contador do carrinho na página
    function atualizarContadorCarrinho() {
        const carrinho = obterCarrinho();
        const totalQuantidade = carrinho.reduce((acc, item) => acc + item.quantidade, 0);
        const contador = document.getElementById('contador-carrinho');
        contador.textContent = totalQuantidade;
    }

    // Função para adicionar produto ao carrinho
    function adicionarAoCarrinho(id) {
        const produto = produtos.find(p => p.id === id);
        if (!produto) return;

        let carrinho = obterCarrinho();
        const itemExistente = carrinho.find(item => item.id === id);

        if (itemExistente) {
            itemExistente.quantidade += 1;
        } else {
            carrinho.push({
                id: produto.id,
                nome: produto.nome,
                preco: produto.preco,
                quantidade: 1,
                imagem: produto.imagem
            });
        }

        salvarCarrinho(carrinho);
        atualizarContadorCarrinho();
        alert(`Produto "${produto.nome}" adicionado ao carrinho!`);
    }

    // Atualiza o contador ao carregar a página
    atualizarContadorCarrinho();

    // Função para abrir a página do carrinho
    function abrirCarrinho() {
        window.location.href = './carrinho.php';
    }

    // Função para filtrar produtos por categoria
    function filtrarCategoria(categoria) {
        renderizarProdutos(categoria);
    }

    // Inicializa mostrando todos os produtos
    renderizarProdutos('todas');
</script>

</body>
</html>
