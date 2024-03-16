<?php
    class Category
    {
        private $connection;
        private $table = 'categories';

        public $id;
        public $category;

        public function __construct($database)
        {
            $this->connection = $database;
        }

        public function read()
        {

            $query = "SELECT
            id,
            category
            from {$this->table}
            ORDER BY category ASC
            ";

            $statement = $this->connection->prepare($query);
            $statement->execute();

            return $statement;

        }

        public function readSingle()
        {
            $query = "SELECT
            id,
            category
            from {$this->table}
            where id = ?
            ";

            $statement = $this->connection->prepare($query);
            $statement->bindParam(1, $this->id);
            $statement->execute();

            $row = $statement->fetch(PDO::FETCH_ASSOC);
            if($row)
            {
                $this->category = $row['category'];
            }
            else
            {
                $this->category = null;
            }
            


        }

        public function create()
        {
            $query = "
            INSERT INTO {$this->table} (category)
            VALUES (:category)

            ";

            $statement = $this->connection->prepare($query);

            //clean data
            $this->category = htmlspecialchars(strip_tags($this->category));

            $statement->bindParam(':category', $this->category);

            if($statement->execute())
            {
                $this->id = $this->connection->lastInsertId();
                return true;
            }

            printf("Error: %s.\n", $statement->error);
            return false;

        }

        public function update()
        {
            $query = "
            UPDATE {$this->table}
            SET
                category = :category
            WHERE
                id = :id
            ";

            $statement = $this->connection->prepare($query);

            //clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->category = htmlspecialchars(strip_tags($this->category));

            $statement->bindParam(':id', $this->id);
            $statement->bindParam(':category', $this->category);

            if($statement->execute())
            {
                return true;
            }

            printf("Error: %s.\n", $statement->error);
            return false;
        }

        public function delete()
        {
            $query = "
            DELETE FROM {$this->table} WHERE id = :id;
            ";

            $statement = $this->connection->prepare($query);

            $this->id = htmlspecialchars(strip_tags($this->id));
            $statement->bindParam(':id', $this->id);

            if($statement->execute())
            {
                return true;
            }

            printf("Error: %s.\n", $statement->error);
            return false;

        }

        public function isIdPresent($inputId)
        {
            $query = "
            SELECT
            DISTINCT id
            from {$this->table}
            where id = ?
            ";

            $statement = $this->connection->prepare($query);
            $statement->bindParam(1, $inputId);
            $statement->execute();

            $row = $statement->fetch(PDO::FETCH_ASSOC);
            if($row)
            {
                return true;
            }
            else
            {
                return false;
            }

        }
    }