<?php
session_start();
// Assumindo que este arquivo existe e contém a inicialização do PDO ($pdo)
require_once 'includes/db.php'; 

// Captura os valores dos filtros da URL (se existirem)
$search_term = isset($_GET['q']) ? trim($_GET['q']) : '';
$filter_tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

// Verifica se a variável $pdo está disponível a partir de includes/db.php
if (!isset($pdo)) {
    $error_message = "Erro de conexão com o banco de dados. Verifique includes/db.php.";
    $vagas = []; // Garante que $vagas está definida
} else {
    try {
        // A base da query é a mesma: vagas ativas e não expiradas
        $sql = "SELECT v.id, v.titulo, v.local, u.nome AS nome_contratante FROM vaga AS v JOIN usuario AS u ON v.id_usuario = u.id WHERE v.data_limite >= CURDATE()";
        $params = [];

        // Adiciona filtros à query dinamicamente
        if (!empty($search_term)) {
            // CORREÇÃO: Usando dois placeholders diferentes para garantir que o PDO funcione.
            $sql .= " AND (v.titulo LIKE :search_title OR v.descricao LIKE :search_desc)";
            
            // Atribuindo o mesmo valor de busca a ambos os placeholders únicos.
            $params[':search_title'] = '%' . $search_term . '%';
            $params[':search_desc'] = '%' . $search_term . '%';
        }
       if (!empty($filter_tipo)) {
    // função LOWER() para ignorar se o valor no banco está
    // como 'Remunerado', 'remunerado' ou 'REMUNERADO'.
    $sql .= " AND LOWER(v.tipo_vaga) = :tipo";
    $params[':tipo'] = $filter_tipo;
        }
        
        $sql .= " ORDER BY v.data_publicacao DESC";

        
        // TEMPORARY DEBUG CHECK: para judar a diagnosticar o SQL gerado.
        
        if (isset($_GET['debug']) && $_GET['debug'] == 1) {
            echo "<pre style='background-color: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb;'>";
            echo "<h3>SQL DEBUG MODE</h3>";
            echo "<strong>SQL Gerado:</strong> " . htmlspecialchars($sql) . "\n";
            echo "<strong>Parâmetros (Bindings):</strong>\n" . print_r($params, true);
            echo "</pre><hr>";
        }
       
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $vagas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) { 
        $error_message = "Erro ao buscar vagas: " . $e->getMessage();
        $vagas = [];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vagas Disponíveis - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>
    <main class="container py-5">
       
        
        <div class="job-listing-panel">
            <h1 class="mb-4 text-center">Encontre uma Vaga</h1>

            <!-- FORMULÁRIO DE FILTRO - ESTILO DE CARD AZULADO -->
            <form action="procurar_vagas.php" method="GET" class="card mb-5 p-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Opções de Filtro</h5>
                    <div class="row g-3 align-items-end">
                        
                        <!-- Coluna 1: Input de Busca -->
                        <div class="col-md-6">
                            <label for="q" class="form-label">Palavra-chave</label>
                            <input type="text" id="q" name="q" class="form-control" placeholder="Título, descrição..." value="<?php echo htmlspecialchars($search_term); ?>">
                        </div>
                        
                        <!-- Coluna 2: Dropdown de Tipo -->
                        <div class="col-md-3">
                            <label for="tipo" class="form-label">Tipo</label>
                            <select id="tipo" name="tipo" class="form-select">
                                <option value="">Qualquer Tipo</option>
                                <option value="remunerado" <?php if($filter_tipo == 'remunerado') echo 'selected'; ?>>Remunerado</option>
                                <option value="voluntario" <?php if($filter_tipo == 'voluntario') echo 'selected'; ?>>Voluntário</option>
                            </select>
                        </div>
                        
                        <!-- Coluna 3: Botão Filtrar -->
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                        </div>
                        
                    </div>
                </div>
            </form>
            <!-- FIM DO FORMULÁRIO DE FILTRO -->

            <?php if (isset($error_message)): ?>
                 <div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <?php if (!empty($vagas)): ?>
                <?php foreach ($vagas as $vaga): ?>
                    <!-- CARD DE LISTAGEM DE VAGA - RECEBE O ESTILO DE CARD AZULADO -->
                    <div class="card mb-3">
                        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                            <div class="me-auto p-2">
                                <h5 class="card-title text-primary"><?php echo htmlspecialchars($vaga['titulo']); ?></h5>
                                <p class="card-text mb-1"><strong>Contratante:</strong> <?php echo htmlspecialchars($vaga['nome_contratante']); ?></p>
                                <p class="card-text text-muted"><strong>Local:</strong> <?php echo htmlspecialchars($vaga['local']); ?></p>
                            </div>
                            <div class="p-2">
                                <a href="detalhes_vaga.php?id=<?php echo $vaga['id']; ?>" class="btn btn-primary">Ver Detalhes</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-warning text-center">Nenhuma vaga encontrada com os filtros selecionados.</div>
            <?php endif; ?>
        </div>
        <div class="d-flex justify-content-start mb-4">
            <button onclick="history.back()" class="btn btn-outline-secondary">
                &larr; Voltar
            </button>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
