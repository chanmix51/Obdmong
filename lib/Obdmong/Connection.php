<?php
namespace Obdmong;

class Connection
{
    protected $client;
    protected $maps = array();

    public function __construct($dsn, $options)
    {
        $this->client = new MongoClient($dsn, $options);
    }

    public function getMapFor($class_name, $force = false)
    {
        if (!$isset($this->maps[$class_name] or $force))
        {
            $class_name = sprintf("%sMap", $class_name);
            $this->maps[$class_name] = new $class_name;
        }

        return $this->maps[$class_name];
    }

    public function getMongo($collection = null)
    {
        if (is_null($collection))
        {
            return $this->client;
        }
        else
        {
            return $this->client->$collection;
        }
    }
}
