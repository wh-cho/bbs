<?php
namespace DB;
use PDO;
use PDOException;

class Connection
{
    private $conn = null;
    private $config;

    public function __construct()
    {
        // config.ini 통해 DB 접속 정보 가져옴
        $this->config = parse_ini_file(__DIR__ . '/../config.ini');
    }

    public function getConnection()
    {
        if ($this->conn == null) {
            try {
                // 데이터 베이스 연결
                $dsn = "mysql:host={$this->config['DB_HOSTNAME']};charset=utf8";
                $conn = new PDO($dsn, $this->config['DB_USER'], $this->config['DB_PASSWORD']);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // 데이터베이스가 이미 존재하는지 확인하는 쿼리
                $result = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$this->config['DB_NAME']}'");

                if ($result->rowCount() == 0) {
                    // 데이터베이스가 존재하지 않으면 생성
                    $conn->exec("CREATE DATABASE {$this->config['DB_NAME']}");
                    echo "Database {$this->config['DB_NAME']} created successfully.\n";
                }

                $conn->query("use " . $this->config['DB_NAME']);

            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
            $this->conn = $conn;
        }
        // 이상이 없을 경우 연결 객체 반환
        return $this->conn;
    }
}