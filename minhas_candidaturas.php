<?php
session_start();
require 'includes/db.php'; // Inclui a conexão com o banco

// Verifica se o usuário está logado e é um freelancer
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'freelancer') {
    header("Location: login.php");
    exit();
}

$freelancer_id = $_SESSION['usuario_id'];

// Prepara a query SQL para buscar as vagas em que o freelancer se candidatou
// Usamos JOIN para pegar informações da tabela 'vagas' e 'candidaturas'
$sql = "SELECT 
            v.id AS vaga_id, 
            v.titulo, 
            v.orcamento, 
            c.data_candidatura, 
            c.status 
        FROM vagas v 
        JOIN candidaturas c ON v.id = c.vaga_id 
        WHERE c.freelancer_id = ? 
        ORDER BY c.data_candidatura DESC";

// Usando prepared statements para segurança contra SQL Injection
$stmt = $pdo->prepare($sql);
$stmt->execute([$freelancer_id]);
$candidaturas = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Candidaturas - MeuFreela</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Poppins:wght@500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css"> 
    
    <style>
        .dashboard-container {
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: #f4f4f4;
            padding: 20px;
            min-height: 80vh; 
            border-right: 1px solid #ddd;
        }
        .sidebar h2 {
            margin-top: 0;
            font-family: 'Poppins', sans-serif;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin-bottom: 10px;
        }
        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            font-family: 'Roboto', sans-serif;
            font-weight: 700;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .sidebar ul li a:hover {
            background-color: #ddd;
        }
        /* Destaca o link da página ativa */
        .sidebar ul li a.active {
            background-color: #007bff;
            color: white;
        }
        .content {
            flex: 1; /* Ocupa o resto do espaço */
            padding: 20px;
        }
        
        /* Estilo para cada Vaga (Card) */
        .vaga-item {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0e0e0;
        }
        .vaga-item h3 {
            margin-top: 0;
            color: #007bff;
        }
        .vaga-item h3 a {
            color: #007bff;
            text-decoration: none;
        }
        .vaga-item h3 a:hover {
            text-decoration: underline;
        }

        /* Estilos para o Status da Candidatura */
        .status {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
            font-size: 14px;
        }
        .status-pendente {
            background-color: #ffc107; /* Amarelo */
            color: #333;
        }
        .status-aprovado {
            background-color: #28a745; /* Verde */
            color: white;
        }
        .status-rejeitado {
            background-color: #dc3545; /* Vermelho */
            color: white;
        }

        /* Botão (reaproveitando do CSS moderno) */
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 700;
            font-family: 'Poppins', sans-serif;
            display: inline-block;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <header class="header">
        <div class="container">
            <a href="index.php" class="logo">MeuFreelaV2</a>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="dashboard-container container">
        
        <div class="sidebar">
            <h2>Dashboard</h2>
            <ul>
                <li><a href="dashboard.php">Visão Geral</a></li>
                <?php if ($_SESSION['tipo_usuario'] == 'cliente'): ?>
                    <li><a href="publicar_vaga.php">Publicar Vaga</a></li>
                    <li><a href="minhas_vagas.php">Minhas Vagas</a></li>
                <?php else: // Freelancer ?>
                    <li><a href="procurar_vagas.php">Procurar Vagas</a></li>
                    <li><a href="minhas_candidaturas.php" class="active">Minhas Candidaturas</a></li>
                <?php endif; ?>
                <li><a href="gerenciar_perfil.php">Gerenciar Perfil</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="content">
            <h1>Minhas Candidaturas</h1>
            
            <?php if (empty($candidaturas)): ?>
                <p>Você ainda não se candidatou a nenhuma vaga.</p>
            <?php else: ?>
                <?php foreach ($candidaturas as $vaga): ?>
                    <div class="vaga-item">
                        <h3>
                            <a href="detalhes_vaga.php?id=<?php echo $vaga['vaga_id']; ?>">
                                <?php echo htmlspecialchars($vaga['titulo']); ?>
                            </a>
                        </h3>
                        <p><strong>Orçamento:</strong> R$ <?php echo htmlspecialchars(number_format($vaga['orcamento'], 2, ',', '.')); ?></p>
                        <p><strong>Data da Candidatura:</strong> <?php echo date('d/m/Y', strtotime($vaga['data_candidatura'])); ?></p>
                        
                        <?php 
                        // Define a classe CSS e o texto com base no status
                        $status_classe = 'status-pendente';
                        $status_texto = 'Pendente';
                        if ($vaga['status'] == 'aprovado') {
                            $status_classe = 'status-aprovado';
                            $status_texto = 'Aprovado';
                        } elseif ($vaga['status'] == 'rejeitado') {
                            $status_classe = 'status-rejeitado';
                            $status_texto = 'Rejeitado';
                        }
                        ?>
                        <p><strong>Status:</strong> <span class="status <?php echo $status_classe; ?>"><?php echo $status_texto; ?></span></p>
                        
                        <a href="detalhes_vaga.php?id=<?php echo $vaga['vaga_id']; ?>" class="btn">Ver Detalhes da Vaga</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
        </div>
    </div>

</body>
</html>