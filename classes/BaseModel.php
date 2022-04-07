<?php

class BaseModel
{
    public function runQuery($sql, $params)
    {
        $conn = new PDO('mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $statement = $conn->prepare($sql);

        if (count($params)) {
            foreach ($params as $key => $value) {
                $statement->bindParam($key, $value);
            }
        }

        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
