<?php
namespace Obdmong;

class ResultIterator implements \OuterIterator
{
    protected $map;
    protected $cursor;

    public function __construct(\MongoCursor $mongo_cursor, EntityMap $entity_map)
    {
        $this->map = $entity_map;
        $this->cursor = $mongo_cursor;
    }

    public function getInnerIterator()
    {
        return $this->cursor;
    }

    public function current()
    {
        $entity_class = $this->map->getEntityClass();

        return new $entity_class($this->getInnerIterator()->current());
    }

    public function next()
    {
        $this->getInnerIterator()->next();
    }

    public function rewind()
    {
        $this->getInnerIterator()->rewind();
    }

    public function key()
    {
        return $this->getInnerIterator()->key();
    }

    public function valid()
    {
        return $this->getInnerIterator()->valid();
    }

    public function export()
    {
        $results = array();

        foreach($this->getInnerIterator() as $result)
        {
            $results[] = $result;
        }

        return $results;
    }
}
