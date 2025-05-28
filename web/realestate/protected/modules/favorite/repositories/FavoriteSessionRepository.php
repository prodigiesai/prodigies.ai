<?php
namespace application\modules\favorite\repositories;


class FavoriteSessionRepository implements FavoriteRepositoryInterface
{
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function loadList() : array
    {
        return isset($_SESSION[$this->key]) ? unserialize($_SESSION[$this->key]) : [];
    }

    public function saveList(array $items)
    {
        $_SESSION[$this->key] = serialize($items);
    }

    /**
     * @param $modelId integer
     * @param $modelName string
     * @return mixed
     */
    public function add($modelId, $modelName)
    {
        $list = $this->loadList();
        if(empty($list[$modelName][$modelId])){
            $list[$modelName][$modelId] = $modelId;
            $this->saveList($list);
        }
        return true;
    }

    /**
     * @param $modelId integer
     * @param $modelName string
     * @return mixed
     */
    public function remove($modelId, $modelName)
    {
        $list = $this->loadList();
        if(isset($list[$modelName][$modelId])){
            unset($list[$modelName][$modelId]);
            $this->saveList($list);
        }
        return true;
    }
}