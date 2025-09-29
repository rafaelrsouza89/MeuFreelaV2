<?php
session_start();
require_once 'includes/db.php';

// Validação de segurança: verifica se o usuário está logado e se é contratante
if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] !== 'contratante' && $_SESSION['user_type'] !== 'ambos')) {
    header('Location: login.php');
    exit();
}

$id_contratante = $_SESSION['user_id'];

try {
    // Query para buscar as vagas do contratante e contar o número de candidaturas em cada uma
    $sql = "SELECT 
                v.id, 
                v.titulo, 
                v.data_limite,
                COUNT(c.id) AS total_candidaturas
            FROM vaga AS v
            LEFT JOIN candidatura AS c ON v.id = c.vaga_id
            WHERE v.id_usuario = :id_contratante
            GROUP BY v.id, v.titulo, v.data_limite
            ORDER BY v.data_publicacao DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_contratante' => $id_contratante]);
    $vagas = $stmt->fetchAll();

} catch (PDOException $e) {
    // error_log($e->getMessage());
    $error_message = "Erro ao carregar suas vagas.";
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Vagas Publicadas - MeuFreela</title>
</head>
<body>
    <header>
        <h1>Minhas Vagas Publicadas</h1>
        <a href="dashboard.php">Voltar para o Painel</a>
    </header>

    <main>
        <?php if (isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php elseif (empty($vagas)): ?>
            <p>Você ainda não publicou nenhuma vaga. <a href="publicar_vaga.php">Clique aqui para publicar sua primeira vaga.</a></p>
        <?php else: ?>
            <table border="1" style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>Título da Vaga</th>
                        <th>Status</th>
                        <th>Candidaturas Recebidas</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vagas as $vaga): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($vaga['titulo']); ?></td>
                            <td>
                                <?php
                                // Verifica se a vaga está ativa ou expirada
                                $hoje = new DateTime();
                                $data_limite = new DateTime($vaga['data_limite']);
                                echo ($data_limite < $hoje) ? 'Expirada' : 'Ativa';
                                ?>
                            </td>
                            <td><?php echo $vaga['total_candidaturas']; ?></td>
                            <td>
                                <?php if ($vaga['total_candidaturas'] > 0): ?>
                                    <a href="ver_candidatos.php?vaga_id=<?php echo $vaga['id']; ?>">Ver Candidatos</a>
                                <?php else: ?>
                                    Nenhum candidato
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
           </main>
           <a href="dashboard.php" class="button button-secondary" style="margin-top: 1rem;">Voltar ao Painel</a>
</body>
</html>