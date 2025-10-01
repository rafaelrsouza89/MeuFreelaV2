<?php
session_start();
require_once 'includes/db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }

$id_usuario = $_SESSION['user_id'];
$message = '';

// Processar o formulário se for enviado (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta de todos os campos do formulário
    $nome = trim($_POST['nome']);
    $data_nascimento = !empty($_POST['data_nascimento']) ? trim($_POST['data_nascimento']) : null;
    $cpf = trim($_POST['cpf']);
    $telefone = trim($_POST['telefone']);
    $linkedin = trim($_POST['linkedin']);
    $cep = trim($_POST['cep']);
    $estado = trim($_POST['estado']);
    $cidade = trim($_POST['cidade']);
    $bairro = trim($_POST['bairro']);
    $logradouro = trim($_POST['logradouro']);
    $numero = trim($_POST['numero']);

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
        $message = '<div class="alert alert-danger">Erro ao atualizar o perfil: ' . $e->getMessage() . '</div>';
    }
}

// Buscar os dados atuais do usuário para exibir no formulário
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
                </div>

                <?php if (!empty($message)) { echo $message; } ?>

                <form class="card" method="POST" action="dashboard.php" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>Dados Pessoais</h5>
                                <div class="row">
                                    <div class="col-md-12 mb-3"><label class="form-label">Nome Completo *</label><input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">Data de Nascimento *</label><input type="date" class="form-control" name="data_nascimento" value="<?php echo htmlspecialchars($usuario['data_nascimento'] ?? ''); ?>"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">CPF *</label><input type="text" class="form-control" name="cpf" value="<?php echo htmlspecialchars($usuario['cpf'] ?? ''); ?>"></div>
                                </div>
                                <hr>
                                <h5>Informações de Contato</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3"><label class="form-label">E-mail *</label><input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" disabled></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">Telefone Celular *</label><input type="text" class="form-control" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>"></div>
                                    <div class="col-md-12 mb-3"><label class="form-label">LinkedIn</label><input type="text" class="form-control" name="linkedin" value="<?php echo htmlspecialchars($usuario['linkedin'] ?? ''); ?>"></div>
                                </div>
                                <hr>
                                <h5>Endereço</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-3"><label class="form-label">CEP *</label><input type="text" class="form-control" name="cep" value="<?php echo htmlspecialchars($usuario['cep'] ?? ''); ?>"></div>
                                    <div class="col-md-4 mb-3"><label class="form-label">Estado *</label><input type="text" class="form-control" name="estado" value="<?php echo htmlspecialchars($usuario['estado'] ?? ''); ?>"></div>
                                    <div class="col-md-4 mb-3"><label class="form-label">Cidade *</label><input type="text" class="form-control" name="cidade" value="<?php echo htmlspecialchars($usuario['cidade'] ?? ''); ?>"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">Bairro *</label><input type="text" class="form-control" name="bairro" value="<?php echo htmlspecialchars($usuario['bairro'] ?? ''); ?>"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">Logradouro *</label><input type="text" class="form-control" name="logradouro" value="<?php echo htmlspecialchars($usuario['logradouro'] ?? ''); ?>"></div>
                                    <div class="col-md-4 mb-3"><label class="form-label">Número</label><input type="text" class="form-control" name="numero" value="<?php echo htmlspecialchars($usuario['numero'] ?? ''); ?>"></div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <img src="https://via.placeholder.com/150" alt="Foto de Perfil" class="img-thumbnail rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                                <input type="file" class="form-control" name="foto">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>

            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>