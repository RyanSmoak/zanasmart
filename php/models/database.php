<?php
//A database class to connect to a mysql database
class Database{
    //The connection string
    private $connection;
    //The constructor
    function __construct($host="localhost", $username="root", $password="", $database="zana")
    {
        //Create a connection
        $this->connection = new mysqli($host, $username, $password, $database);
        //Check if the connection was successful
        if($this->connection->connect_error)
        {
            //If not, return an error
            die("Connection failed: " . $this->connection->connect_error);
        }
    }
    
    //A function to execute a query
    public function query($query)
    {
        //Execute the query
        $result = $this->connection->query($query);
        //Check if the query was successful
        if($result === false)
        {
            //If not, return an error
            die("Query failed: " . $this->connection->error);
        }
        //Return the result
        return $result;
    }

    //A function to get the last inserted id
    public function lastInsertId()
    {
        //Return the last inserted id
        return $this->connection->insert_id;
    }

    //A function to escape a string
    public function escape($string)
    {
        //Escape the string
        return $this->connection->real_escape_string($string);
    }
}