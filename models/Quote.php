<?php
    class Quote
    {
        private $connection;
        private $table = 'quotes';

        public $id;
        public $quote;
        public $author_id;
        public $category_id;
        public $author;
        public $category;

        public function __construct($database)
        {
            $this->connection = $database;
        }

        public function read()
        {

            $query = "SELECT
            quotes.id,
            quotes.quote,
            authors.author,
            categories.category
            from {$this->table}
            join authors
                on quotes.author_id = authors.id
            join categories
                on quotes.category_id = categories.id

            ORDER BY quotes.id ASC
            ";

            $statement = $this->connection->prepare($query);
            $statement->execute();

            return $statement;

        }

        public function readSingle()
        {
            $query = "SELECT
            quotes.id,
            quotes.quote,
            authors.author,
            categories.category
            from {$this->table}
            join authors
                on quotes.author_id = authors.id
            join categories
                on quotes.category_id = categories.id
            where quotes.id = ?
            ";

            $statement = $this->connection->prepare($query);
            $statement->bindParam(1, $this->id);
            $statement->execute();

            $row = $statement->fetch(PDO::FETCH_ASSOC);
            if($row)
            {
                $this->category = $row['category'];
                $this->author = $row['author'];
                $this->quote = $row['quote'];
            }
            
            


        }

        public function readParameterQuote()
        {
            $query = "SELECT
            quotes.id,
            quotes.quote,
            authors.author,
            categories.category
            from {$this->table}
            join authors
                on quotes.author_id = authors.id
            join categories
                on quotes.category_id = categories.id
            where quotes.id = ?
            ";

            $statement = $this->connection->prepare($query);
            $statement->bindParam(1, $this->id);
            $statement->execute();

            return $statement;

        }


        public function readParameterAuthor()
        {
            $query = "SELECT
            quotes.id,
            quotes.quote,
            authors.author,
            categories.category
            from {$this->table}
            join authors
                on quotes.author_id = authors.id
            join categories
                on quotes.category_id = categories.id
            where authors.id = ?
            ";

            $statement = $this->connection->prepare($query);
            $statement->bindParam(1, $this->author_id);
            $statement->execute();

            return $statement;

        }

        public function readParamaterCategory()
        {
            $query = "SELECT
            quotes.id,
            quotes.quote,
            authors.author,
            categories.category
            from {$this->table}
            join authors
                on quotes.author_id = authors.id
            join categories
                on quotes.category_id = categories.id
            where categories.id = ?
            ";

            $statement = $this->connection->prepare($query);
            $statement->bindParam(1, $this->category_id);
            $statement->execute();

            return $statement;

        }

        public function readParameterAuthorAndCategory()
        {
            $query = "SELECT
            quotes.id,
            quotes.quote,
            authors.author,
            categories.category
            from {$this->table}
            join authors
                on quotes.author_id = authors.id
            join categories
                on quotes.category_id = categories.id
            where authors.id = :author_id and categories.id = :category_id
            ";

            $statement = $this->connection->prepare($query);
            $statement->bindParam(':author_id', $this->author_id);
            $statement->bindParam(':category_id', $this->category_id);
            $statement->execute();

            return $statement;

        }

       

        public function create()
        {
            $query = "
            INSERT INTO {$this->table} (quote, author_id, category_id)
            VALUES (:quote, :author_id, :category_id)

            ";

            $statement = $this->connection->prepare($query);

            //clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));

            $statement->bindParam(':quote', $this->quote);
            $statement->bindParam(':author_id', $this->author_id);
            $statement->bindParam(':category_id', $this->category_id);

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
                quote = :quote,
                author_id = :author_id,
                category_id = :category_id
            WHERE
                id = :id
            ";

            $statement = $this->connection->prepare($query);

            //clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));

            $statement->bindParam(':id', $this->id);
            $statement->bindParam(':quote', $this->quote);
            $statement->bindParam(':author_id', $this->author_id);
            $statement->bindParam(':category_id', $this->category_id);

            if($statement->execute())
            {
                return true;
            }

            printf("Error: %s.\n", $statement->error);
            return false;
        }

        public function isIdPresent($inputTable, $inputId)
        {
            $query = "
            SELECT
            DISTINCT id
            from {$inputTable}
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

        public function isUpdateForeignKeyConstraintMetAuthorId($id_author)
        {
           return $this->isIdPresent('authors', $id_author);
        }

        public function isUpdateForeignKeyConstraintMetCategoryId($id_category)
        {
            return $this->isIdpresent('categories', $id_category);
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
    }