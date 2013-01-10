<?php
namespace Obdmong;

class ResultIterator extends \IteratorIterator
{
    protected $map;

    public function __construct(\MongoCursor $mongo_cursor, EntityMap $entity_map)
    {
        $this->map = $entity_map;
        parent::__construct($mongo_cursor);
    }

    public function current()
    {
        $entity_class = $this->map->getEntityClass();

        return new $entity_class(parent::current());
    }

    public function export()
    {
        $results = array();

        foreach($this as $result)
        {
            $results[] = $result;
        }

        return $results;
    }
}
