<?php

try {
    new \PDO('mysql:host=localhost;dbname=test','root','root');
    echo'Conexao bem sucedida';
} catch (\PDOException $ex) {
    echo $ex->getMessage();
}