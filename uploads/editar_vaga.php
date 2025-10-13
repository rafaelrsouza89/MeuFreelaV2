<?php
session_start();
require_once 'includes/db.php';

// Verificação de segurança: usuário logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$id_vaga = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$id_contratante = $_SESSION['user_id'];
$vaga = null;
$error_message = '';

if (!$id_vaga) {
    die("ID da vaga inválido.");
}

try {
    // Busca a vaga E garante que ela pertence ao usuário logado
    $sql = "SELECT * FROM vaga WHERE id = :id_vaga AND id_usuario = :id_contratante";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_vaga' => $id_vaga, 'id_contratante' => $id_contratante]);
    $vaga = $stmt->fetch();

    if (!$vaga) {
        die("Acesso não autorizado ou vaga inexistente.");
    }

} catch (PDOException $e) {
    $error_message = "Erro ao carregar a vaga para edição.";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Vaga: <?php echo htmlspecialchars($vaga['titulo'] ?? ''); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main class="container py-5">
        
        <!-- Botão de Voltar -->
        <div class="d-flex justify-content-end mb-4">
            <button onclick="history.back()" class="btn btn-outline-secondary">
                &larr; Voltar
            </button>
        </div>
        
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Editar Vaga: <?php echo htmlspecialchars($vaga['titulo'] ?? ''); ?></h1>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <form action="processa_edicao_vaga.php" method="POST">
                    <input type="hidden" name="vaga_id" value="<?php echo htmlspecialchars($vaga['id'] ?? ''); ?>">
                    
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título da Vaga:</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo htmlspecialchars($vaga['titulo'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição:</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="5" required><?php echo htmlspecialchars($vaga['descricao'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tipo_vaga" class="form-label">Tipo da Vaga:</label>
                        <select class="form-select" id="tipo_vaga" name="tipo_vaga" required>
                            <option value="remunerado" <?php echo ($vaga['tipo_vaga'] ?? '') === 'remunerado' ? 'selected' : ''; ?>>Remunerado</option>
                            <option value="voluntario" <?php echo ($vaga['tipo_vaga'] ?? '') === 'voluntario' ? 'selected' : ''; ?>>Voluntário</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="remuneracao" class="form-label">Remuneração (R$):</label>
                        <input type="number" step="0.01" class="form-control" id="remuneracao" name="remuneracao" value="<?php echo htmlspecialchars($vaga['remuneracao'] ?? ''); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="local" class="form-label">Local:</label>
                        <input type="text" class="form-control" id="local" name="local" value="<?php echo htmlspecialchars($vaga['local'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="data_limite" class="form-label">Data Limite:</label>
                        <!-- Garante que a data seja formatada para o campo input type="date" -->
                        <input type="date" class="form-control" id="data_limite" name="data_limite" value="<?php echo htmlspecialchars($vaga['data_limite'] ?? ''); ?>" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    <a href="minhas_vagas.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
