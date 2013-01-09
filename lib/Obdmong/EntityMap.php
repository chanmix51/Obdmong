<?php
namespace Obdmong;

abstract class EntityMap
{
    protected $entity_class;
    protected $collection;
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->initialize();

        if (!isset($this->collection))
        {
            throw new \DomainException(sprintf("`collection` not set after initialized class '%s'.", get_class($this)));
        }

        if (!isset($this->entity_class))
        {
            throw new \DomainException(sprintf("`entity_class` not set after initialized class '%s'.", get_class($this)));
        }
    }

    abstract protected function initialize();

    public function find(Array $filter, Array $select = null)
    {
        $cursor = $this->doFind($filter, $select);

        return $this->getIteratorFromResult($cursor);
    }

    public function getEntityClass()
    {
        return $this->entity_class;
    }

    protected function doFind(Array $filter, Array $select = null)
    {
        $collection = $this->connection->getMongo($this->collection);

        if (is_null($select))
        {
            return $collection->find($filter);
        }
        else
        {
            return $collection->find($filter, $select);
        }
    }

    protected function getIteratorFromResult(\MongoCursor $cursor)
    {
        return new Iterator($cursor, $this);
    }
}
