<?php
session_start();
require_once 'database.php';

// Função para inserir um novo analista
function inserirAnalista($nome, $email, $senha, $telefone)
{
    $db = new Database();
    $conn = $db->getConnection();

    // Use prepared statements para evitar SQL injection
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, telefone, tp_usuario) VALUES (?, ?, ?, ?, '2')");
    $stmt->bind_param("ssss", $nome, $email, $senha, $telefone);
    $stmt->execute();

    $stmt->close();
    $conn->close();
}

// Função para deletar um analista
function deletarAnalista($id)
{
    $db = new Database();
    $conn = $db->getConnection();
    
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $stmt->close();
    $conn->close();
}

// Função para atualizar os dados de um analista
function atualizarAnalista($id, $nome, $email, $senha, $telefone)
{
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ?, telefone = ? WHERE id_usuario = ?");
    $stmt->bind_param("ssssi", $nome, $email, $senha, $telefone, $id);
    $stmt->execute();

    $stmt->close();
    $conn->close();
}

// Função para buscar todos os analistas ou pesquisar por nome
function buscarAnalistas($searchTerm = '')
{
    $db = new Database();
    $conn = $db->getConnection();

    if ($searchTerm) {
        $stmt = $conn->prepare("SELECT id_usuario, nome, email, telefone FROM usuarios WHERE tp_usuario = '2' AND nome LIKE ?");
        $likeTerm = "%".$searchTerm."%";
        $stmt->bind_param("s", $likeTerm);
    } else {
        $stmt = $conn->prepare("SELECT id_usuario, nome, email, telefone FROM usuarios WHERE tp_usuario = '2'");
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $analistas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $analistas[] = $row;
        }
    }

    $stmt->close();
    $conn->close();

    return $analistas;
}

// Se o método de requisição for POST, verifica qual ação realizar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == "inserir") {
            inserirAnalista($_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['telefone']);
        } elseif ($action == "deletar") {
            deletarAnalista($_POST['id']);
        } elseif ($action == "atualizar") {
            atualizarAnalista($_POST['id'], $_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['telefone']);
        }
    }
}

// Se o método de requisição for GET, verifica se há um termo de pesquisa
$searchTerm = '';
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}

// Busca todos os analistas ou pesquisa pelo termo
$analistas = buscarAnalistas($searchTerm);

// Variáveis para o formulário de edição
$editAnalista = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == "editar") {
    $editAnalista = [
        'id_usuario' => $_POST['id'],
        'nome' => $_POST['nome'],
        'email' => $_POST['email'],
        'telefone' => $_POST['telefone'],
    ];
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipe - Gerenciamento de Analistas</title>
    <link rel="stylesheet" href="assets/css/styleEquipe.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <a href="javascript:history.go(-1);" class="back-button">Voltar</a> <!-- Botão de voltar -->
    <div class="container">
    
        <h2>Gerenciamento de Analistas</h2>

        <div class="sidebar">
        <h2>Menu</h2>
      

        <h3>Inserir Novo Analista</h3>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="action" value="inserir">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required><br>
            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" required><br>
            <button type="submit">Inserir</button>
        </form>

        <?php if ($editAnalista): ?>
            <h3>Editar Analista</h3>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="action" value="atualizar">
                <input type="hidden" name="id" value="<?php echo $editAnalista['id_usuario']; ?>">
                <label for="edit_nome">Nome:</label>
                <input type="text" id="edit_nome" name="nome" value="<?php echo $editAnalista['nome']; ?>" required><br>
                <label for="edit_email">Email:</label>
                <input type="email" id="edit_email" name="email" value="<?php echo $editAnalista['email']; ?>" required><br>
                <label for="edit_senha">Senha:</label>
                <input type="password" id="edit_senha" name="senha" required><br>
                <label for="edit_telefone">Telefone:</label>
                <input type="text" id="edit_telefone" name="telefone" value="<?php echo $editAnalista['telefone']; ?>" required><br>
                <button type="submit">Atualizar</button>
            </form>
        <?php endif; ?>

        <h3>Buscar Analista</h3>
        <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="search">Nome:</label>
            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit">Pesquisar</button>
        </form>

        <h3>Analistas Cadastrados</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($analistas as $analista) : ?>
                <tr>
                    <td><?php echo $analista['id_usuario']; ?></td>
                    <td><?php echo $analista['nome']; ?></td>
                    <td><?php echo $analista['email']; ?></td>
                    <td><?php echo $analista['telefone']; ?></td>
                    <td>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="action-form">
                            <input type="hidden" name="action" value="deletar">
                            <input type="hidden" name="id" value="<?php echo $analista['id_usuario']; ?>">
                            <button type="submit"><i class="fas fa-trash icon-trash"></i></button>
                        </form>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="action-form">
                            <input type="hidden" name="action" value="editar">
                            <input type="hidden" name="id" value="<?php echo $analista['id_usuario']; ?>">
                            <input type="hidden" name="nome" value="<?php echo $analista['nome']; ?>">
                            <input type="hidden" name="email" value="<?php echo $analista['email']; ?>">
                            <input type="hidden" name="telefone" value="<?php echo $analista['telefone']; ?>">
                            <button type="submit"><i class="fas fa-pen icon-pen"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>
