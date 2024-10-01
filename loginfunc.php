<?php
session_start();

require_once 'Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Cria uma instância do banco de dados e obtém a conexão
    $database = new Database();
    $conn = $database->getConnection();

    // Evita SQL Injection
    $email = $conn->real_escape_string($email);
    $password = $conn->real_escape_string($password);

    // Consulta SQL para verificar o usuário e a senha
    $sql = "SELECT id_usuario, email FROM usuarios WHERE email = ? AND senha = ? AND tp_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $password, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // O usuário foi encontrado
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        $_SESSION['id_analista'] = $row['id_usuario'];
        $_SESSION['role'] = $role;

        // Redireciona para o painel apropriado
        if($role == '1'){
            header("Location: PainelControleAdm.php");
        } else if ($role == '2'){
            header("Location: PainelControleA.php");
        }
        exit();
    } else {
        echo "Usuário ou senha incorretos!";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Método de requisição inválido.";
}
?>
