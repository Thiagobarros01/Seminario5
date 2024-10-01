<?php
require_once 'Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera os dados do formulário
    $nomeEmpresa = $_POST['nome_empresa'];
    $cnpj = $_POST['cnpj'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmarSenha = $_POST['confirmar_senha'];

    // Verifica se as senhas coincidem
    if ($senha !== $confirmarSenha) {
        echo "As senhas não coincidem.";
        exit();
    }

    

    // Cria uma instância do banco de dados e obtém a conexão
    $database = new Database();
    $conn = $database->getConnection();

    // Evita SQL Injection
    $nomeEmpresa = $conn->real_escape_string($nomeEmpresa);
    $cnpj = $conn->real_escape_string($cnpj);
    $telefone = $conn->real_escape_string($telefone);
    $email = $conn->real_escape_string($email);


    // Verifica se o CNPJ já existe
    $sql = "SELECT cod_empresa FROM empresa WHERE cnpj = '$cnpj'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "CNPJ já existe.";
        exit();
    }

    // Verifica se o email já existe
    $sql = "SELECT cod_empresa FROM empresa WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "Email já existe.";
        exit();
    }

    // Consulta SQL para inserir o novo usuário na tabela
    $sql = "INSERT INTO empresa (nome_empresa, cnpj, telefone, email, senha, tp_usuario) VALUES ('$nomeEmpresa', '$cnpj', '$telefone', '$email', '$senha', '3')";

    if ($conn->query($sql) === TRUE) {
        echo "Usuário cadastrado com sucesso.";
    } else {
        echo "Erro ao cadastrar usuário: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Método de requisição inválido.";
}

?>
