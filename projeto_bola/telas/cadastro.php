

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Açougue</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5ddb6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container-login {
            background-color: #e5b57b;
            border: 2px solid #983422;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(178, 34, 34, 0.3);
            width: 300px;
        }
        h2 {
            color: #3c1f13;
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
            color: #333;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px 6px;
            margin-top: 5px;
            border: 1px solid #b22222;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            margin-top: 20px;
            width: 100%;
            background-color: #3c1f13;
            color: #e5b57b;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #7a0d0d;
        }
    </style>
</head>
<body>
    <div class="container-login">
        <img src="../img/bola.png" alt="Logo Açougue" style="display:block; margin: 0 auto 15px auto; max-height: 80px;" />
        <h2>Cadastrar - Açougue</h2>
        <form method="POST" action="../src/validaCadastro.php">
            <label for="usuario">Usuário:</label>
            <input type="text" id="usuario" name="usuario" required />
            
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required />

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required />

            <input type="submit" value="Cadastrar" />
        </form>
    </div>
</body>
</html>