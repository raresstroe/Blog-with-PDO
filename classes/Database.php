<?php

/*
Database

A connection to the Database
*/

class Database
{
    /**
     * Hostname
     * @var string
     */
    protected $db_host;

    /**
     * DB name
     * @var string
     */
    protected $db_name;

    /**
     * Username
     * @var string
     */
    protected $db_user;

    /**
     * Password
     * @var string
     */
    protected $db_pass;

    public function __construct($host, $name, $user, $password)
    {
        $this->db_host = $host;
        $this->db_name = $name;
        $this->db_user = $user;
        $this->db_pass = $password;
    }

    /**
     * Get the database connection
     *
     * @return mixed PDO object Connection to the database server
     */
    public function getConn() //This method connects to the DB
    {


        $dsn = "mysql:host=" . $this->db_host . ";dbname=" . $this->db_name . ";charset=utf8";

        try {
            $db = new PDO($dsn, $this->db_user, $this->db_pass);


            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $db;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
