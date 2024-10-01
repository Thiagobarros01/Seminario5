<?php
class Database {
    private $host = "localhost";
    private $db_name = "hdesk";
    private $username = "root";
    private $password = "";
    public $conn;

    // Método para obter a conexão com o banco de dados
    public function getConnection() {
        $this->conn = null;

        // Cria uma nova conexão mysqli
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

        // Verifica se houve um erro na conexão
        if ($this->conn->connect_error) {
            die("Erro de conexão: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}
?>
