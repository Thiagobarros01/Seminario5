<?php
session_start();

require_once 'database.php';

// Verifica se o email do analista está na sessão
if (!isset($_SESSION['email']) || !isset($_POST['id_ticket'])) {
    header("Location: login.php");
    exit();
}

// Recupera o id do analista da sessão
$id_analista = $_SESSION['id_analista'];

// Recupera o id do ticket do formulário
$id_ticket = $_POST['id_ticket'];

// Cria uma instância do banco de dados e obtém a conexão
$database = new Database();
$conn = $database->getConnection();

// Atualiza o ticket com o id do analista
$sql = "UPDATE ticket SET cod_analista = ?, status_ticket = 'pendente' WHERE id_ticket = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $id_analista, $id_ticket);

if ($stmt->execute()) {
    // Redireciona de volta para a página de tickets abertos
    header("Location: ticketsAbertos.php");
} else {
    echo "Erro ao atribuir ticket.";
}

$stmt->close();
$conn->close();
?>
