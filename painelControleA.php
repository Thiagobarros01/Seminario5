<?php
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once 'Database.php';

// Verifica se o email do analista está na sessão
if (isset($_SESSION['email'])) {
    // Recupera o email do analista da sessão
    $email = $_SESSION['email'];
    $id_analista = $_SESSION['id_analista'];
} else {
    // Se o email do analista não estiver na sessão, redireciona para a página de login
    header("Location: login.php");
    exit();
}

// Instancia a classe Database para obter a conexão
$db = new Database();
$conn = $db->getConnection();

// Consulta para contar o número de tickets em diferentes estados
$sql = "SELECT 
            COUNT(CASE WHEN status_ticket = 'pendente' THEN 1 END) AS pendente,
            COUNT(CASE WHEN status_ticket = 'Aberto' THEN 1 END) AS aberto,
            COUNT(CASE WHEN status_ticket = 'Resolvido' THEN 1 END) AS resolvido,
            COUNT(CASE WHEN cod_analista IS NULL OR cod_analista = '' THEN 1 END) AS nao_atribuido
        FROM ticket";

$result = $conn->query($sql);
$row = $result->fetch_assoc();

$pendente = $row['pendente'];
$aberto = $row['aberto'];
$naoAtribuido = $row['nao_atribuido'];

$sql_tarefas = "SELECT 
                    COUNT(CASE WHEN status_ticket = 'pendente' AND cod_analista = $id_analista THEN 1 END) AS pendentes,
                    COUNT(CASE WHEN status_ticket = 'resolvido' AND cod_analista = $id_analista THEN 1 END) AS resolvidas
                FROM ticket";

$result_tarefas = $conn->query($sql_tarefas);
$row_tarefas = $result_tarefas->fetch_assoc();

$pendentes = $row_tarefas['pendentes'];
$resolvidas = $row_tarefas['resolvidas'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle - Analista</title>
    <link rel="stylesheet" href="assets/css/stylePainelControle.css">
    <script src="assets/js/scriptsPainelControle.js"></script>
</head>
<body>
    <div class="sidebar">
        <h2>Menu</h2>
        <ul>
            <li><a href="alterarStatusTicketA.php">Tickets</a></li>
            <li><a href="interfaceChamadoADM.php">Histórico chamados</a></li>
        </ul>
    </div>
    <div class="main-content">
        <header>
            <div class="header-left">
                <h1>Painel de Controle - Analista </h1>
               
            </div>
            <div class="header-right">
                <div class="dropdown">
                    
                    <a href="CadastroChamados.html" class="dropdown-toggle-link">
                        <button class="dropdown-toggle">Abrir chamado</button>
                    </a>
            <a href="TelaPrincipal.html" class="dropdown-toggle-link">
                <button class="dropdown-toggle">Logout</button>
            </a>
           
        </header>
       
        <main>
            
            <div class="stats">
            
                <div class="stat-item">
                    
                    <p>Pendente</p>
                    <h2><?php echo $pendente; ?></h2>
                </div>
                <div class="stat-item">
                    <p>Aberto</p>
                    <h2><?php echo $aberto; ?></h2>
                </div>
                <div class="stat-item">
                    <p>Não atribuído</p>
                    <h2><?php echo $naoAtribuido; ?></h2>
                </div>
            </div>
            <div class="details">
                <div class="tasks">
                    <h3>Minhas tarefas pendentes</h3>
                    <p>Minhas tarefas pendentes: <?php echo $pendentes; ?></p>
                    <p>Minhas tarefas resolvidas: <?php echo $resolvidas; ?></p>
                    <button class="btn-add-task" onclick="window.location.href='ticketsAbertos.php'">Puxar tickets abertos</button>

                </div>
            </div>
        </main>
    </div>
</body>
</html>
