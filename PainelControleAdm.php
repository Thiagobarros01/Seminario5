<?php
// Inclui o arquivo de conexão com o banco de dados
require_once 'Database.php';


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
$resolvido = $row['resolvido'];
$naoAtribuido = $row['nao_atribuido'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle</title>
    <link rel="stylesheet" href="assets/css/stylePainelControle.css">
    <script src="assets/js/scriptsPainelControle.js"></script>
</head>
<body>
    <div class="sidebar">
        <h2>Menu</h2>
        <ul>
            <li><a href="#">Tickets</a></li>
            <li><a href="#">Admin</a></li>
            <li><a href="interfaceChamadoADM.php">Histórico chamados</a></li>
            <li><a href="equipe.php">Equipe</a></li>
        </ul>
    </div>
    <div class="main-content">
        <header>
            <div class="header-left">
                <h1>Painel de Controle</h1>
            </div>
            <div class="header-right">
                <div class="dropdown">
                    <a href="CadastroChamados.html" class="dropdown-toggle-link">
                        <button class="dropdown-toggle">Abrir chamado</button>
                    </a>
                </div>
                <input type="text" placeholder="Pesquisar">
            </div>
        </header>
        <main>
            <h3>Status dos Tickets</h3>
            <div class="stats">
                <div class="stat-item">
                    <p>pendente</p>
                    <h2><?php echo $pendente; ?></h2>
                </div>
                <div class="stat-item">
                    <p>Aberto</p>
                    <h2><?php echo $aberto; ?></h2>
                </div>
                <div class="stat-item">
                    <p>resolvido</p>
                    <h2><?php echo $resolvido; ?></h2>
                </div>
                <div class="stat-item">
                    <p>Não atribuído</p>
                    <h2><?php echo $naoAtribuido; ?></h2>
                </div>
            </div>
            <div class="details">
                <!-- Aqui você pode adicionar mais informações como desejado -->
            </div>
        </main>
    </div>
</body>
</html>
