<?php
session_start();
require_once 'includes/db.php';

try {
    // A lógica PHP para buscar as vagas continua a mesma
    $sql = "SELECT 
                v.id, v.titulo, v.descricao, v.tipo_vaga, 
                v.remuneracao, v.local, u.nome AS nome_contratante
            FROM vaga AS v
            JOIN usuario AS u ON v.id_usuario = u.id
            WHERE v.data_limite >= CURDATE()
            ORDER BY v.data_publicacao DESC";

    $stmt = $pdo->query($sql);
    $vagas = $stmt->fetchAll();

} catch (PDOException $e) {
    $error_message = "Erro ao buscar vagas. Tente novamente mais tarde.";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procurar Vagas - MeuFreela</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Estilos adicionais para os cards de vaga */
        .vaga-card {
            border: 1px solid #e0e0e0;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            transition: box-shadow 0.3s ease;
        }
        .vaga-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .vaga-card h3 {
            margin-top: 0;
            color: #4a148c;
        }
        .vaga-card p {
            margin-bottom: 0.5rem;
        }
        .vaga-card .detalhes-link {
            display: inline-block;
            margin-top: 1rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="panel">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h1>Vagas Disponíveis</h1>
            <nav>
                <a href="index.php">Voltar ao Início</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php">Meu Painel</a>
                <?php else: ?>
                    <a href="login.php">Entrar</a>
                <?php endif; ?>
            </nav>
        </div>

        <main>
            <?php if (!empty($vagas)): ?>
                <?php foreach ($vagas as $vaga): ?>
                    <div class="vaga-card">
                        <h3><?php echo htmlspecialchars($vaga['titulo']); ?></h3>
                        <p><strong>Contratante:</strong> <?php echo htmlspecialchars($vaga['nome_contratante']); ?></p>
                        <p><strong>Local:</strong> <?php echo htmlspecialchars($vaga['local']); ?></p>
                        <p>
                            <strong>Remuneração:</strong> 
                            <?php if ($vaga['tipo_vaga'] === 'voluntario'): ?>
                                Trabalho Voluntário
                            <?php else: ?>
                                R$ <?php echo number_format($vaga['remuneracao'], 2, ',', '.'); ?>
                            <?php endif; ?>
                        </p>
                        <a href="detalhes_vaga.php?id=<?php echo $vaga['id']; ?>" class="detalhes-link">Ver Detalhes</a>
                    </div>
                <?php endforeach; ?>
            <?php elseif (isset($error_message)): ?>
                <p style="color: red;"><?php echo $error_message; ?></p>
            <?php else: ?>
                <p>Nenhuma vaga disponível no momento.</p>
            <?php endif; ?>
        </main>
    </div>

</body>
</html>