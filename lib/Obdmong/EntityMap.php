<?php
namespace Obdmong;

abstract class EntityMap
{
    protected $entity_class;
    protected $database;
    protected $collection;
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->initialize();

        if (!isset($this->database))
        {
            throw new \DomainException(sprintf("`database` not set after initialized class '%s'.", get_class($this)));
        }

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
        $collection = $this->connection->getCollection($this->database, $this->collection);

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
        return new ResultIterator($cursor, $this);
    }

    public function saveOne(Entity $entity)
    {
        $fields = $entity->dump();

        if (isset($fields["_id"]))
        {
            $id = $fields["_id"];
            unset($fields["_id"]);
            $result = $this->connection
                ->getCollection($this->database, $this->collection)
                ->update(array("_id" => $id), $fields, array("fsync" => 1));
        }
        else
        {
            $result = $this->connection
                ->getCollection($this->database, $this->collection)
                ->insert($fields, array('fsync' => 1));
            $entity->set("_id", $fields["_id"]);
        }
    }
}
