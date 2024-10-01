<?php
session_start();

$cod_empresa = $_SESSION['cod_empresa'];
// Define o fuso horário
date_default_timezone_set('America/Sao_Paulo');

// Inclui a classe Database
require_once 'database.php';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $status_ticket = $_POST['status_ticket'];
    $prioridade = $_POST['prioridade'];
    $descricao = $_POST['descricao'];

    // Obtém a data e hora atual do sistema
    $data_hora = date('Y-m-d H:i:s');

    // Cria uma instância da classe Database e obtém a conexão
    $database = new Database();
    $conn = $database->getConnection();

    // Prepara a consulta SQL para evitar injeção de SQL
    $sql = "INSERT INTO ticket (titulo, status_ticket, prioridade, descricao, cod_empresa, data_hora) VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Vincula os parâmetros à consulta SQL
        $stmt->bind_param("ssssss", $nome, $status_ticket, $prioridade, $descricao, $cod_empresa, $data_hora);

        // Executa a consulta
        if ($stmt->execute()) {
            echo "success"; // Responde com 'success' em caso de sucesso
        } else {
            echo "Erro: Não foi possível criar o chamado. " . $stmt->error;
        }

        // Fecha a declaração
        $stmt->close();
    } else {
        echo "Erro: Não foi possível preparar a consulta. " . $conn->error;
    }

    // Fecha a conexão
    $conn->close();
} else {
    echo "Método de requisição inválido.";
}
?>
