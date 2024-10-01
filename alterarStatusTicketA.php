<?php
session_start();

require_once 'database.php';

// Cria uma instância do banco de dados e obtém a conexão
$database = new Database();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['ticket_id']) && isset($_POST['new_status'])) {
    // Atualiza o status do ticket
    $ticket_id = $_POST['ticket_id'];
    $new_status = $_POST['new_status'];

    $sql_update = "UPDATE ticket SET status_ticket = ? WHERE id_ticket = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("si", $new_status, $ticket_id);

    if ($stmt->execute()) {
         "Status do ticket atualizado com sucesso.";
    } else {
         "Erro ao atualizar o status do ticket.";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" || $_SERVER["REQUEST_METHOD"] === "GET") {
    // Busca todos os tickets cadastrados no banco junto com as informações do analista, se houver
    $sql = "CALL ConsultarTicketsComAnalistas()";
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
    <title>Chamados - Administrador</title>
    <link rel="stylesheet" href="assets/css/styleChamados.css">
</head>

<body>
<a href="javascript:history.go(-1);" class="back-button">Voltar</a> <!-- Botão de voltar -->
    <div class="container">
        <h2>Todos os Chamados</h2>

        <?php if ($result && $result->num_rows > 0) : ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="call" id="call<?php echo $row['id_ticket']; ?>">
                    <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                    <p><?php echo htmlspecialchars($row['descricao']); ?></p>
                    <div class="meta">
                        <span>Status: <?php echo htmlspecialchars($row['status_ticket']); ?></span>
                        <span>Analista: <?php echo htmlspecialchars($row['nome_analista']); ?></span>
                    </div>
                    <button onclick="showEditForm(<?php echo $row['id_ticket']; ?>)">Editar Status</button>
                    
                    <div class="edit-form" id="edit-form-<?php echo $row['id_ticket']; ?>" style="display: none;">
                        <form method="post" action="">
                            <input type="hidden" name="ticket_id" value="<?php echo $row['id_ticket']; ?>">
                            <select name="new_status">
                                <option value="Aberto" <?php echo $row['status_ticket'] == 'Aberto' ? 'selected' : ''; ?>>Aberto</option>
                                <option value="Pendente" <?php echo $row['status_ticket'] == 'Pendente' ? 'selected' : ''; ?>>Pendente</option>
                                <option value="Resolvido" <?php echo $row['status_ticket'] == 'Resolvido' ? 'selected' : ''; ?>>Resolvido</option>
                            </select>
                            <button type="submit">Salvar</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p>Nenhum chamado encontrado.</p>
        <?php endif; ?>

    </div>

    <script>
        function showEditForm(ticketId) {
            var form = document.getElementById('edit-form-' + ticketId);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>

</html>

<?php
$conn->close();
?>
