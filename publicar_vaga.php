<?php
session_start();

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['tipo_usuario'], ['contratante', 'ambos'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar Vaga - MeuFreela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main class="container py-5">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Publicar Nova Vaga</h1>
                <form action="processa_vaga.php" method="POST">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título da Vaga:</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição:</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_vaga" class="form-label">Tipo da Vaga:</label>
                        <select class="form-select" id="tipo_vaga" name="tipo_vaga" required>
                            <option value="remunerado">Remunerado</option>
                            <option value="voluntario">Voluntário</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="remuneracao" class="form-label">Remuneração (R$):</label>
                        <input type="number" step="0.01" class="form-control" id="remuneracao" name="remuneracao">
                    </div>
                    <div class="mb-3">
                        <label for="local" class="form-label">Local:</label>
                        <input type="text" class="form-control" id="local" name="local" required>
                    </div>
                    <div class="mb-3">
                        <label for="data_limite" class="form-label">Data Limite:</label>
                        <input type="date" class="form-control" id="data_limite" name="data_limite" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Publicar</button>
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<a href="index.php" class="btn btn-outline-secondary mt-4 mb-2 d-inline-block">
    &larr; Voltar
</a>
</html>

<?php
// Código para alterar a tabela do usuário, adicionando novas colunas
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "meufreela";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// SQL para adicionar colunas à tabela usuario
$sql = "ALTER TABLE usuario
ADD COLUMN foto_perfil VARCHAR(255) NULL,
ADD COLUMN data_nascimento DATE NULL,
ADD COLUMN cpf VARCHAR(20) NULL,
ADD COLUMN linkedin VARCHAR(255) NULL,
ADD COLUMN cep VARCHAR(20) NULL,
ADD COLUMN estado VARCHAR(50) NULL,
ADD COLUMN cidade VARCHAR(100) NULL,
ADD COLUMN bairro VARCHAR(100) NULL,
ADD COLUMN logradouro VARCHAR(100) NULL,
ADD COLUMN numero VARCHAR(10) NULL;";

if ($conn->query($sql) === TRUE) {
    echo "Colunas adicionadas com sucesso à tabela usuario.";
} else {
    echo "Erro ao adicionar colunas: " . $conn->error;
}

$conn->close();
?>