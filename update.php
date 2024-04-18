<?php
$servername = "localhost:3306";
$username = "root";
$password = "root99";
$database = "php_test";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['data'])) {
    die("Dados inválidos.");
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode($_POST['data'], true);

    $stmt = $conn->prepare("INSERT INTO estoque (produto, cor, tamanho, deposito, data_disponibilidade, quantidade) 
                            VALUES (:produto, :cor, :tamanho, :deposito, :data_disponibilidade, :quantidade)
                            ON DUPLICATE KEY UPDATE quantidade = :quantidade");

    foreach ($data as $product) {
        $stmt->bindParam(':produto', $product['produto']);
        $stmt->bindParam(':cor', $product['cor']);
        $stmt->bindParam(':tamanho', $product['tamanho']);
        $stmt->bindParam(':deposito', $product['deposito']);
        $stmt->bindParam(':data_disponibilidade', $product['data_disponibilidade']);
        $stmt->bindParam(':quantidade', $product['quantidade']);
        $stmt->execute();
    }

    echo "Registros atualizados/inseridos com sucesso.";
} catch(PDOException $e) {
    echo "Erro ao atualizar/inserir registros: " . $e->getMessage();
}

$conn = null;
?>
