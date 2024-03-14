<?php
    class Author
    {
        private $connection;
        private $table = 'authors';

        public $id;
        public $author;

        public function __construct($database)
        {
            $this->connection = $database;
        }

        public function read()
        {

            $query = "SELECT
            id,
            author
            from authors
            ORDER BY author ASC
            ";

            $statement = $this->connection->prepare($query);
            $statement->execute();

            return $statement;

        }

        public function readSingle()
        {
            $query = "SELECT
            id,
            author
            from authors
            where id = ?
            ";

            $statement = $this->connection->prepare($query);
            $statement->bindParam(1, $this->id);
            $statement->execute();

            $row = $statement->fetch(PDO::FETCH_ASSOC);
            if($row)
            {
                $this->author = $row['author'];
            }
            else
            {
                $this->author = null;
            }
            


        }

        public function create()
        {
            $query = "
            INSERT INTO {$this->table} (author)
            VALUES (:author)

            ";

            $statement = $this->connection->prepare($query);

            //clean data
            $this->author = htmlspecialchars(strip_tags($this->author));

            $statement->bindParam(':author', $this->author);

            if($statement->execute())
            {
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
                author = :author
            WHERE
                id = :id
            ";

            $statement = $this->connection->prepare($query);

            //clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->author = htmlspecialchars(strip_tags($this->author));

            $statement->bindParam(':id', $this->id);
            $statement->bindParam(':author', $this->author);

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