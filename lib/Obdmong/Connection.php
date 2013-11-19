<?php
namespace Obdmong;

class Connection
{
    protected $client;
    protected $maps = array();

    public function __construct($dsn, Array $options = array())
    {
        if (!preg_match('#/(\w+)$#', $dsn, $matchs))
        {
            throw new \InvalidArgumentException(sprintf("Invalid database in dsn '%s'.", $dsn));
        }

        $this->db_name = $matchs[1];

        $this->client = new \MongoClient($dsn, $options);
    }

    public function getMapFor($class_name, $force = false)
    {
        if (!isset($this->maps[$class_name]) or $force)
        {
            $class_name = sprintf("%sMap", $class_name);
            $this->maps[$class_name] = new $class_name($this);
        }

        return $this->maps[$class_name];
    }

    public function getMongoClient()
    {
        return $this->client;
    }

    public function getDatabase($database = null)
    {
        $database = $database == null ? $this->db_name : $database;

        return $this->client->$database;
    }

    public function getCollection($database, $collection)
    {
        return $this->getDatabase()->$collection;
    }

    public function getDbName()
    {
        return $this->db_name;
    }
}
