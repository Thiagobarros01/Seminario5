<?php
session_start();

require_once 'Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cnpj = $_POST['cnpj'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Cria uma instância do banco de dados e obtém a conexão
    $database = new Database();
    $conn = $database->getConnection();

    // Evita SQL Injection
    $cnpj = $conn->real_escape_string($cnpj);
    $password = $conn->real_escape_string($password);

    // Consulta SQL para verificar o usuário e a senha
    $sql = "SELECT cod_empresa FROM empresa WHERE cnpj = '$cnpj' AND senha = '$password'";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // O usuário foi encontrado, armazena o código da empresa na sessão
        $row = $result->fetch_assoc();
        $_SESSION['cod_empresa'] = $row['cod_empresa'];
        $_SESSION['role'] = $role;
        
        // Redireciona para o painel apropriado
        if($role == '3') {
            header("Location: PainelControle.html");
            exit();
        }
    } else {
        echo "Usuário ou senha incorretos!";
    }

    $conn->close();
} else {
    echo "Método de requisição inválido.";
}
?>
