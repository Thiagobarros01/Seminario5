<?php
session_start();

require_once 'database.php';

// Verifica se o email do analista está na sessão
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Cria uma instância do banco de dados e obtém a conexão
$database = new Database();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] === "POST" || $_SERVER["REQUEST_METHOD"] === "GET") {
    // Busca todos os tickets abertos no banco junto com as informações do analista, se houver
    $sql = "CALL ConsultarTicketsAbertos()";
    $result = $conn->query($sql);
} else {
    echo "Método de requisição inválido.";
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chamados Abertos - Administrador</title>
    <link rel="stylesheet" href="assets/css/styleChamados.css">
</head>

<body>
    <div class="container">
        <a href="javascript:history.go(-1);" class="back-button">Voltar</a> <!-- Botão de voltar -->
        <h2>Chamados Abertos</h2>

        <?php if ($result && $result->num_rows > 0) : ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="call" id="call<?php echo $row['id_ticket']; ?>">
                    <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                    <p><?php echo htmlspecialchars($row['descricao']); ?></p>
                    <div class="meta">
                        <span>Status: <?php echo htmlspecialchars($row['status_ticket']); ?></span>
                        <span>Analista: <?php echo htmlspecialchars($row['nome_analista']); ?></span>
                    </div>
                    <form action="atribuirTicket.php" method="post">
                        <input type="hidden" name="id_ticket" value="<?php echo $row['id_ticket']; ?>">
                        <button type="submit">Pegar Ticket</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p>Nenhum chamado aberto encontrado.</p>
        <?php endif; ?>

    </div>
</body>

</html>

<?php
$conn->close();
?>
