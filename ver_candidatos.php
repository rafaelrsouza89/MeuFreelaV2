<?php
session_start();
require_once 'includes/db.php';

// 1. Validação de segurança básica: usuário logado e é contratante
if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] !== 'contratante' && $_SESSION['user_type'] !== 'ambos')) {
    header('Location: login.php');
    exit();
}

// Pega o ID da vaga da URL e o ID do contratante da sessão
$id_vaga = filter_input(INPUT_GET, 'vaga_id', FILTER_VALIDATE_INT);
$id_contratante = $_SESSION['user_id'];

if (!$id_vaga) {
    die("ID da vaga inválido.");
}

try {
    // 2. Validação de segurança CRÍTICA: Verifica se a vaga pertence ao contratante logado
    $sql_check_owner = "SELECT titulo FROM vaga WHERE id = :id_vaga AND id_usuario = :id_contratante";
    $stmt_check_owner = $pdo->prepare($sql_check_owner);
    $stmt_check_owner->execute(['id_vaga' => $id_vaga, 'id_contratante' => $id_contratante]);
    $vaga = $stmt_check_owner->fetch();

    if (!$vaga) {
        // Se a vaga não pertence a este contratante, nega o acesso
        die("Acesso não autorizado.");
    }
    $titulo_vaga = $vaga['titulo'];

    // 3. Se a validação passar, busca os dados dos candidatos (freelancers)
    $sql_get_candidates = "SELECT 
                                u.nome, 
                                u.email, 
                                u.telefone,
                                c.data_candidatura
                           FROM candidatura AS c
                           JOIN usuario AS u ON c.id_usuario = u.id
                           WHERE c.vaga_id = :id_vaga
                           ORDER BY c.data_candidatura DESC";
                           
    $stmt_get_candidates = $pdo->prepare($sql_get_candidates);
    $stmt_get_candidates->execute(['id_vaga' => $id_vaga]);
    $candidatos = $stmt_get_candidates->fetchAll();

} catch (PDOException $e) {
    // error_log($e->getMessage());
    die("Erro ao consultar os dados.");
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatos para: <?php echo htmlspecialchars($titulo_vaga); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Candidatos para a Vaga: "<?php echo htmlspecialchars($titulo_vaga); ?>"</h1>
        <a href="minhas_vagas.php">Voltar para Minhas Vagas</a>
    </header>

    <main>
        <?php if (empty($candidatos)): ?>
            <p>Ainda não há candidatos para esta vaga.</p>
        <?php else: ?>
            <table border="1" style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Nome do Freelancer</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Data da Candidatura</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($candidatos as $candidato): ?>
                        <tr>
    <td>
        <a href="perfil_freelancer.php?id=<?php echo $candidato['id_usuario']; ?>" target="_blank">
            <?php echo htmlspecialchars($candidato['nome']); ?>
        </a>
    </td>
    <td><?php echo htmlspecialchars($candidato['email']); ?></td>
    <td><?php echo htmlspecialchars($candidato['telefone']); ?></td>
    <td><?php echo date('d/m/Y H:i', strtotime($candidato['data_candidatura'])); ?></td>
</tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>