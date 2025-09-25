<?php
// Definições do banco de dados
$host = 'localhost'; // ou o host do seu servidor de banco de dados
$dbname = 'meufreela_db';
$username = 'root'; // seu usuário do banco de dados
$password = ''; // sua senha do banco de dados
$charset = 'utf8mb4';

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

// Opções do PDO para o tratamento de erros e modo de busca
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Cria uma nova instância do PDO para a conexão
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    // Em caso de erro na conexão, exibe uma mensagem e encerra o script
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>