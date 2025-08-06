<?php
// Tela de carrinho com tema açougue
// Espaço para personalização indicado nos comentários abaixo
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Carrinho - Açougue</title>
    <style>
        /* Estilo básico tema açougue */
        body {
            font-family: Arial, sans-serif;
            background-color: #e5b57b;
            margin: 0;
            padding: 0;
            color: #4a2c2a;
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
        main {
            padding: 20px;
            background-color: #e5b57b;
            min-height: 80vh;
            max-width: 900px;
            margin: 0 auto;
        }
        h2 {
            color: #3c1f13;
            margin-bottom: 20px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f5ddb6;
            box-shadow: 2px 2px 6px rgba(139,0,0,0.2);
            border-radius: 8px;
            overflow: hidden;
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
        input[type="number"] {
            width: 60px;
            padding: 5px;
            border: 1px solid #b22222;
            border-radius: 4px;
            text-align: center;
        }
        button.remover {
            background-color: #b22222;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        button.remover:hover {
            background-color: #7a0d0d;
        }
        .total {
            margin-top: 20px;
            font-size: 1.3em;
            font-weight: bold;
            text-align: right;
            color: #3c1f13;
        }
        .botoes {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .botoes button {
            background-color: #3c1f13;
            color: #f5ddb6;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .botoes button:hover {
            background-color: #983422;
        }

        .titulo{
            color: #f5ddb6
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
</header>

<main>
    <h2>Seu Carrinho</h2>
    <table id="tabela-carrinho">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Nome</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
                <th>Subtotal</th>
                <th>Remover</th>
            </tr>
        </thead>
        <tbody>
            <!-- Produtos do carrinho serão inseridos aqui via JavaScript -->
        </tbody>
    </table>
    <div class="total" id="total-carrinho">Total: R$ 0,00</div>
    <div class="botoes">
        <button onclick="continuarComprando()">Continuar Comprando</button>
        <button onclick="finalizarCompra()">Finalizar Compra</button>
    </div>
</main>

<script>
    // Dados de exemplo do carrinho - espaço para personalização ou integração backend
    let carrinho = [];

    // Função para formatar valores em moeda BRL
    function formatarPreco(valor) {
        return valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }

    // Função para renderizar o carrinho na tabela
    function renderizarCarrinho() {
        const tbody = document.querySelector('#tabela-carrinho tbody');
        tbody.innerHTML = '';

        let total = 0;

        carrinho.forEach(item => {
            const subtotal = item.preco * item.quantidade;
            total += subtotal;

            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td><img src="${item.imagem}" alt="${item.nome}" /></td>
                <td>${item.nome}</td>
                <td><input type="number" min="1" value="${item.quantidade}" onchange="atualizarQuantidade(${item.id}, this.value)" /></td>
                <td>${formatarPreco(item.preco)}</td>
                <td>${formatarPreco(subtotal)}</td>
                <td><button class="remover" onclick="removerProduto(${item.id})">X</button></td>
            `;

            tbody.appendChild(tr);
        });

        document.getElementById('total-carrinho').textContent = 'Total: ' + formatarPreco(total);
    }

    // Atualiza a quantidade do produto no carrinho
    function atualizarQuantidade(id, novaQuantidade) {
        novaQuantidade = parseInt(novaQuantidade);
        if (isNaN(novaQuantidade) || novaQuantidade < 1) {
            alert('Quantidade inválida');
            renderizarCarrinho();
            return;
        }
        const item = carrinho.find(p => p.id === id);
        if (item) {
            item.quantidade = novaQuantidade;
            renderizarCarrinho();
        }
    }

    // Remove um produto do carrinho
    function removerProduto(id) {
        carrinho = carrinho.filter(p => p.id !== id);
        renderizarCarrinho();
    }

    // Botão continuar comprando - redireciona para loja.php
    function continuarComprando() {
        window.location.href = 'loja.php';
    }

    // Botão finalizar compra - alerta simples
    function finalizarCompra() {
        alert('Compra finalizada! Obrigado pela preferência.');
        // Aqui poderia ser implementado o processo de finalização real
        carrinho = [];
        renderizarCarrinho();
    }

    // Inicializa a renderização do carrinho
    renderizarCarrinho();
</script>

</body>
</html>
