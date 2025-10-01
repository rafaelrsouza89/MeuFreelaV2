<?php
session_start();
require_once 'includes/db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }

$id_usuario = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CORREÇÃO: Adicionada verificação 'isset()' em todos os campos para evitar avisos.
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $data_nascimento = isset($_POST['data_nascimento']) && !empty($_POST['data_nascimento']) ? trim($_POST['data_nascimento']) : null;
    $cpf = isset($_POST['cpf']) ? trim($_POST['cpf']) : null;
    $telefone = isset($_POST['telefone']) ? trim($_POST['telefone']) : null;
    $linkedin = isset($_POST['linkedin']) ? trim($_POST['linkedin']) : null;
    $cep = isset($_POST['cep']) ? trim($_POST['cep']) : null;
    $estado = isset($_POST['estado']) ? trim($_POST['estado']) : null;
    $cidade = isset($_POST['cidade']) ? trim($_POST['cidade']) : null;
    $bairro = isset($_POST['bairro']) ? trim($_POST['bairro']) : null;
    $logradouro = isset($_POST['logradouro']) ? trim($_POST['logradouro']) : null;
    $numero = isset($_POST['numero']) ? trim($_POST['numero']) : null;

    try {
        $sql_update = "UPDATE usuario SET nome = :nome, data_nascimento = :data_nascimento, cpf = :cpf, telefone = :telefone, linkedin = :linkedin, cep = :cep, estado = :estado, cidade = :cidade, bairro = :bairro, logradouro = :logradouro, numero = :numero WHERE id = :id_usuario";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            'nome' => $nome, 'data_nascimento' => $data_nascimento, 'cpf' => $cpf, 'telefone' => $telefone, 'linkedin' => $linkedin,
            'cep' => $cep, 'estado' => $estado, 'cidade' => $cidade, 'bairro' => $bairro, 'logradouro' => $logradouro, 'numero' => $numero,
            'id_usuario' => $id_usuario
        ]);
        $_SESSION['user_name'] = $nome;
        $message = '<div class="alert alert-success">Perfil atualizado com sucesso!</div>';
    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Erro ao atualizar o perfil.</div>';
    }
}

try {
    $sql_select = "SELECT * FROM usuario WHERE id = :id_usuario";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->execute(['id_usuario' => $id_usuario]);
    $usuario = $stmt_select->fetch();
} catch (PDOException $e) { die("Erro ao carregar dados do perfil."); }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { background-color: #fff; height: 100vh; padding-top: 20px; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
        .sidebar .nav-link { color: #555; font-weight: 500; }
        .sidebar .nav-link.active { color: #0d6efd; background-color: #e9f5ff; border-right: 3px solid #0d6efd; }
        .info-display p { margin-bottom: 0.75rem; }
        .info-display strong { min-width: 160px; display: inline-block; color: #6c757d; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky">
                    <h4 class="px-3">MeuFreela</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link active" href="dashboard.php">Meu Perfil</a></li>
                        <li class="nav-item"><a class="nav-link" href="minhas_vagas.php">Minhas Vagas</a></li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Meu Perfil</h1>
                    <button id="editButton" class="btn btn-outline-primary">Editar Perfil</button>
                </div>

                <?php if (!empty($message)) { echo $message; } ?>

                <div id="infoDisplay" class="card">
                    <div class="card-body">
                         <div class="row">
                            <div class="col-md-3 text-center">
                                <img src="https://via.placeholder.com/150" alt="Foto de Perfil" class="img-thumbnail rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                            <div class="col-md-9">
                                <h5>Dados Pessoais</h5>
                                <div class="info-display">
                                    <p><strong>Nome Completo:</strong> <?php echo htmlspecialchars($usuario['nome'] ?? 'Não informado'); ?></p>
                                    <p><strong>Data de Nascimento:</strong> <?php echo htmlspecialchars($usuario['data_nascimento'] ?? 'Não informado'); ?></p>
                                    <p><strong>CPF:</strong> <?php echo htmlspecialchars($usuario['cpf'] ?? 'Não informado'); ?></p>
                                </div><hr>
                                <h5>Informações de Contato</h5>
                                <div class="info-display">
                                    <p><strong>E-mail:</strong> <?php echo htmlspecialchars($usuario['email'] ?? 'Não informado'); ?></p>
                                    <p><strong>Telefone:</strong> <?php echo htmlspecialchars($usuario['telefone'] ?? 'Não informado'); ?></p>
                                    <p><strong>LinkedIn:</strong> <?php echo htmlspecialchars($usuario['linkedin'] ?? 'Não informado'); ?></p>
                                </div><hr>
                                <h5>Endereço</h5>
                                <div class="info-display">
                                    <p><strong>Logradouro:</strong> <?php echo htmlspecialchars($usuario['logradouro'] ?? 'Não informado'); ?>, <?php echo htmlspecialchars($usuario['numero'] ?? 's/n'); ?></p>
                                    <p><strong>Bairro:</strong> <?php echo htmlspecialchars($usuario['bairro'] ?? 'Não informado'); ?></p>
                                    <p><strong>Cidade/Estado:</strong> <?php echo htmlspecialchars($usuario['cidade'] ?? 'Não informado'); ?>/<?php echo htmlspecialchars($usuario['estado'] ?? 'SC'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="editForm" class="card d-none" method="POST" action="dashboard.php">
                    <div class="card-body">
                        <h5 class="card-title">Editando Perfil</h5>
                        <div class="row">
                           <div class="col-md-12 mb-3"><label class="form-label">Nome Completo *</label><input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>"></div>
                            <div class="col-md-6 mb-3"><label class="