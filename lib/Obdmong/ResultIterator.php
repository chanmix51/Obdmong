<?php
namespace Obdmong;

class Iterator extends \IteratorIterator
{
    protected $map;

    public function __contruct(\MongoCursor $mongo_cursor, EntityMap $entity_map)
    {
        $this->map = $entity_map;
        parent::__construct($mongo_cursor);
    }

    public function current()
    {
        $entity_class = $this->map->getEntityClass();

        return new $entity_class(parent::current());
    }
}