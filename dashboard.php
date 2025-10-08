<?php
session_start();
require_once 'includes/db.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }

$id_usuario = $_SESSION['user_id'];
$message = '';

// 1. BUSQUE O USUÁRIO PRIMEIRO
try {
    $sql_select = "SELECT * FROM usuario WHERE id = :id_usuario";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->execute(['id_usuario' => $id_usuario]);
    $usuario = $stmt_select->fetch();
} catch (PDOException $e) { die("Erro ao carregar dados do perfil."); }

// 2. DEPOIS PROCESSE O POST (Lógica para o formulário de edição)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // NOVO: Adiciona campos de Freelancer, se estiverem no POST
    $is_freelancer = in_array(strtolower($usuario['tipo_usuario']), ['freelancer', 'ambos']);
    
    $biografia = $is_freelancer && isset($_POST['biografia']) ? trim($_POST['biografia']) : $usuario['biografia'];
    $especialidades = $is_freelancer && isset($_POST['especialidades']) ? trim($_POST['especialidades']) : $usuario['especialidades'];
    $portfolio_url = $is_freelancer && isset($_POST['portfolio_url']) ? trim($_POST['portfolio_url']) : $usuario['portfolio_url'];


    // Processamento do upload da foto
    $foto_perfil = $usuario['foto_perfil']; // valor atual do banco
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
        $nome_arquivo = 'foto_' . $id_usuario . '_' . time() . '.' . $ext;
        $caminho_destino = 'uploads/' . $nome_arquivo;

        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $caminho_destino)) {
            $foto_perfil = $caminho_destino;
            
            // Opcional: Remover foto antiga 
            if (!empty($usuario['foto_perfil']) && $usuario['foto_perfil'] !== 'default-avatar.png' && file_exists($usuario['foto_perfil'])) {
                @unlink($usuario['foto_perfil']);
            }
        }
    }

    try {
        // CORREÇÃO E CONSOLIDAÇÃO: Adiciona os campos de Freelancer no SQL de UPDATE
        $sql_update = "UPDATE usuario SET nome = :nome, data_nascimento = :data_nascimento, cpf = :cpf, telefone = :telefone, linkedin = :linkedin, cep = :cep, estado = :estado, cidade = :cidade, bairro = :bairro, logradouro = :logradouro, numero = :numero, foto_perfil = :foto_perfil, biografia = :biografia, especialidades = :especialidades, portfolio_url = :portfolio_url WHERE id = :id_usuario";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            'nome' => $nome, 
            'data_nascimento' => $data_nascimento, 
            'cpf' => $cpf, 
            'telefone' => $telefone, 
            'linkedin' => $linkedin,
            'cep' => $cep, 
            'estado' => $estado, 
            'cidade' => $cidade, 
            'bairro' => $bairro, 
            'logradouro' => $logradouro, 
            'numero' => $numero,
            'foto_perfil' => $foto_perfil,
            'biografia' => $biografia,
            'especialidades' => $especialidades,
            'portfolio_url' => $portfolio_url,
            'id_usuario' => $id_usuario
        ]);
        
        $_SESSION['user_name'] = $nome;
        $message = '<div class="alert alert-success">Perfil atualizado com sucesso!</div>';

        // Recarrega $usuario para refletir as alterações
        $sql_select = "SELECT * FROM usuario WHERE id = :id_usuario";
        $stmt_select = $pdo->prepare($sql_select);
        $stmt_select->execute(['id_usuario' => $id_usuario]);
        $usuario = $stmt_select->fetch();

    } catch (PDOException $e) {
        $message = '<div class="alert alert-danger">Erro ao atualizar o perfil.</div>';
    }
}

// Lógica para mostrar o botão 'Procurar Vagas' e campos de freelancer
$mostrarBotaoProcurarVagas = false;
$is_freelancer_db = false;
if (isset($usuario['tipo_usuario'])) {
    if (in_array($usuario['tipo_usuario'], ['freelancer', 'ambos'])) {
        $mostrarBotaoProcurarVagas = true;
        $is_freelancer_db = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky">
                    <h4 class="px-3">MeuFreela</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item"><a class="nav-link active" href="dashboard.php">Meu Perfil</a></li>
                        <?php if (isset($usuario['tipo_usuario']) && in_array($usuario['tipo_usuario'], ['contratante', 'ambos'])): ?>
                            <li class="nav-item"><a class="nav-link" href="minhas_vagas.php">Minhas Vagas</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Sair</a></li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Meu Perfil</h1>
                    <div>
                        <button id="editButton" class="btn btn-outline-primary">Editar Perfil</button>
                        <?php if ($mostrarBotaoProcurarVagas): ?>
                            <a href="procurar_vagas.php" class="btn btn-success ms-2">Procurar Vagas</a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($message)) { echo $message; } ?>

                <div id="infoDisplay" class="card">
                    <div class="card-body">
                         <div class="row">
                            <div class="col-md-3 text-center">
                                <?php
                                $foto_display = !empty($usuario['foto_perfil']) ? htmlspecialchars($usuario['foto_perfil']) : 'https://via.placeholder.com/150';
                                ?>
                                <img src="<?php echo $foto_display; ?>" alt="Foto de Perfil" class="img-thumbnail rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                                <p class="text-muted small">Tipo de Conta: <?php echo ucfirst(htmlspecialchars($usuario['tipo_usuario'] ?? 'Não informado')); ?></p>
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
                                
                                <?php if ($is_freelancer_db): ?>
                                    <hr>
                                    <h5>Informações de Freelancer</h5>
                                    <div class="info-display">
                                        <p><strong>Biografia:</strong> <?php echo nl2br(htmlspecialchars($usuario['biografia'] ?? 'Não informado')); ?></p>
                                        <p><strong>Especialidades:</strong> <?php echo htmlspecialchars($usuario['especialidades'] ?? 'Não informado'); ?></p>
                                        <p><strong>Portfólio:</strong> <?php echo !empty($usuario['portfolio_url']) ? '<a href="' . htmlspecialchars($usuario['portfolio_url']) . '" target="_blank">Ver Portfólio</a>' : 'Não informado'; ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="editForm" class="card d-none" method="POST" action="dashboard.php" enctype="multipart/form-data">
                    <div class="card-body">
                        <h5 class="card-title">Editando Perfil</h5>
                        <div class="row">
                            <div class="col-md-3 text-center mb-3">
                                <img src="<?php echo $foto_display; ?>" alt="Foto de Perfil" class="img-thumbnail rounded-circle mb-2" style="width: 120px; height: 120px; object-fit: cover;">
                                <input type="file" name="foto_perfil" accept="image/*" class="form-control form-control-sm mt-2">
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-12 mb-3"><label class="form-label">Nome Completo *</label><input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">Data de Nascimento</label><input type="date" class="form-control" name="data_nascimento" value="<?php echo htmlspecialchars($usuario['data_nascimento'] ?? ''); ?>"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">CPF *</label><input type="text" class="form-control" name="cpf" value="<?php echo htmlspecialchars($usuario['cpf'] ?? ''); ?>"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">Telefone</label><input type="text" class="form-control" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">LinkedIn</label><input type="text" class="form-control" name="linkedin" value="<?php echo htmlspecialchars($usuario['linkedin'] ?? ''); ?>"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">CEP</label><input type="text" class="form-control" name="cep" value="<?php echo htmlspecialchars($usuario['cep'] ?? ''); ?>"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">Estado</label><input type="text" class="form-control" name="estado" value="<?php echo htmlspecialchars($usuario['estado'] ?? ''); ?>"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">Cidade</label><input type="text" class="form-control" name="cidade" value="<?php echo htmlspecialchars($usuario['cidade'] ?? ''); ?>"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">Bairro</label><input type="text" class="form-control" name="bairro" value="<?php echo htmlspecialchars($usuario['bairro'] ?? ''); ?>"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">Logradouro</label><input type="text" class="form-control" name="logradouro" value="<?php echo htmlspecialchars($usuario['logradouro'] ?? ''); ?>"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">Número</label><input type="text" class="form-control" name="numero" value="<?php echo htmlspecialchars($usuario['numero'] ?? ''); ?>"></div>
                                </div>
                                
                                <?php if ($is_freelancer_db): ?>
                                    <hr class="my-4">
                                    <h4 class="mb-3">Informações de Freelancer</h4>
                                    <div class="mb-3">
                                        <label for="biografia" class="form-label">Biografia / Resumo Profissional:</label>
                                        <textarea class="form-control" id="biografia" name="biografia" rows="5"><?php echo htmlspecialchars($usuario['biografia'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="especialidades" class="form-label">Especialidades (separadas por vírgula):</label>
                                        <input type="text" class="form-control" id="especialidades" name="especialidades" value="<?php echo htmlspecialchars($usuario['especialidades'] ?? ''); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="portfolio_url" class="form-label">Link do Portfólio:</label>
                                        <input type="text" class="form-control" id="portfolio_url" name="portfolio_url" value="<?php echo htmlspecialchars($usuario['portfolio_url'] ?? ''); ?>">
                                    </div>
                                <?php endif; ?>
                                
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                            <button id="cancelEdit" class="btn btn-secondary">Cancelar</button>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const editButton = document.getElementById('editButton');
    const infoDisplay = document.getElementById('infoDisplay');
    const editForm = document.getElementById('editForm');
    const cancelEdit = document.getElementById('cancelEdit');

    if (editButton && infoDisplay && editForm) {
        editButton.addEventListener('click', function() {
            infoDisplay.classList.add('d-none');
            editForm.classList.remove('d-none');
        });
    }
    if (cancelEdit) {
        cancelEdit.addEventListener('click', function(e) {
            e.preventDefault();
            editForm.classList.add('d-none');
            infoDisplay.classList.remove('d-none');
        });
    }
});
</script>
</body>
</html>