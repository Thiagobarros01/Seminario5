<?php
session_start();

require_once 'database.php';

// Verifica se o cliente está logado
if (!isset($_SESSION['cod_empresa'])) {
    header('Location: login.php');
    exit();
}

// Obtém o código da empresa associada ao cliente logado
$cod_empresa = $_SESSION['cod_empresa'];

// Cria uma instância do banco de dados e obtém a conexão
$database = new Database();
$conn = $database->getConnection();

// Busca os chamados associados ao cliente logado e os detalhes do analista
$sql = "CALL ConsultarTicketsPorEmpresa(?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cod_empresa);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seus Chamados</title>
    <link rel="stylesheet" href="assets/css/styleChamados.css">
    <script src="assets/js/scriptChamados.js"></script>
</head>

<body>
    <div class="container">
        <a href="javascript:history.go(-1);" class="back-button">Voltar</a>
        <h2>Seus Chamados</h2>

        <?php while ($row = $result->fetch_assoc()) : ?>
            <div class="call" id="call<?php echo $row['id_ticket']; ?>">
                <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                <p><?php echo htmlspecialchars($row['descricao']); ?></p>
                <div class="meta">
                    <span>Status: <?php echo htmlspecialchars($row['status_ticket']); ?></span>
                </div>

                <p>Analista: <?php echo htmlspecialchars($row['nome_analista']); ?></p>
            </div>
        <?php endwhile; ?>

    </div>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>
