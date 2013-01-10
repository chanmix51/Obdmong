<?php
namespace Obdmong;

class Connection
{
    protected $client;
    protected $maps = array();

    public function __construct($dsn, Array $options = array())
    {
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

    public function getDatabase($database)
    {
        return $this->client->$database;
    }

    public function getCollection($database, $collection)
    {
        return $this->client->$database->$collection;
    }
}
