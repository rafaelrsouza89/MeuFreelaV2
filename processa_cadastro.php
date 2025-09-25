<?php
// 1. Incluir o arquivo de conexão com o banco de dados
require_once 'includes/db.php';

// 2. Verificar se o formulário foi submetido (se a requisição é do tipo POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 3. Coletar e limpar os dados do formulário
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha']; // A senha não precisa de 'trim'
    $telefone = trim($_POST['telefone']);
    $tipo_usuario = $_POST['tipo_usuario'];

    // 4. Validações básicas
    if (empty($nome) || empty($email) || empty($senha) || empty($telefone) || empty($tipo_usuario)) {
        // Redireciona com mensagem de erro se algum campo estiver vazio
        header('Location: cadastro.php?status=error&msg=emptyfields');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Redireciona com mensagem de erro se o e-mail for inválido
        header('Location: cadastro.php?status=error&msg=invalidemail');
        exit();
    }
    // 5. Verificar se o e-mail já existe no banco de dados [cite: 81]
    $sql_check = "SELECT id FROM usuario WHERE email = :email";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute(['email' => $email]);
    
    if ($stmt_check->fetch()) {
        // Redireciona com mensagem de erro se o e-mail já estiver cadastrado
        header('Location: cadastro.php?status=error&msg=emailtaken');
        exit();
    }

    // 6. Criptografar a senha (Segurança é fundamental)
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // 7. Preparar e executar a inserção no banco de dados
    try {
        $sql_insert = "INSERT INTO usuario (nome, email, senha, telefone, tipo_usuario, data_cadastro) 
                       VALUES (:nome, :email, :senha, :telefone, :tipo_usuario, NOW())";
        
        $stmt_insert = $pdo->prepare($sql_insert);
        
        $stmt_insert->execute([
            'nome' => $nome,
            'email' => $email,
            'senha' => $senha_hash,
            'telefone' => $telefone,
            'tipo_usuario' => $tipo_usuario
        ]);

        // 8. Redirecionar para a página de cadastro com mensagem de sucesso
        header('Location: cadastro.php?status=success');
        exit();

    } catch (PDOException $e) {
        // Em caso de erro, redireciona com uma mensagem genérica
        // Em um ambiente de produção, você pode logar o erro: error_log($e->getMessage());
        header('Location: cadastro.php?status=error&msg=dberror');
        exit();
    }

} else {
    // Se o script for acessado diretamente (não via POST), redireciona para a home
    header('Location: index.php');
    exit();
}
?>