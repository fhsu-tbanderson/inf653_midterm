<?php
    class Database
    {
        private $connection;
        private $host;
        private $port;
        private $database_name;
        private $username;
        private $password;

        public function __construct()
        {
            $this->username = getenv('USERNAME');
            $this->password = getenv('PASSWORD');
            $this->database_name = getenv('DB_NAME');
            $this->host = getenv('DB_HOST');
            $this->port = getenv('DB_PORT');

        }

        public function connect()
        {
            if($this->connection)
            {
                return $this->connection;
            }
            else
            {
                $data_source_name = "pgsql:host={$this->host};port={$this->port};dbname={$this->database_name};";

                try
                {
                    $this->connection = new PDO($data_source_name, $this->username, $this->password);
                    $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    return $this->connection;
                }
                catch(PDOException $error)
                {
                    echo "Connection Error: {$error->getMessage()}";
                }
            }
        }
    }