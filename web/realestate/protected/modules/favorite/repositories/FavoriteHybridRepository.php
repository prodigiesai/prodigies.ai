<?php
namespace application\modules\favorite\repositories;

use Yii;

class FavoriteHybridRepository implements FavoriteRepositoryInterface
{
    private $repo;

    private $sessionKey;

    public function __construct(string $sessionKey)
    {
        $this->sessionKey = $sessionKey;
        $sessionRepo = new FavoriteSessionRepository($this->sessionKey);

        if (Yii::app()->user->isGuest) {
            $this->repo = $sessionRepo;
        } else {
            $dbRepo = new FavoriteDbRepository(Yii::app()->user->id);
            if ($sessionItems = $sessionRepo->loadList()) {
                $items = array_merge($dbRepo->loadList(), $sessionItems);
                $dbRepo->saveList($items);
                $sessionRepo->saveList([]);
            }
            $this->repo = $dbRepo;
        }
    }

    public function loadList() : array
    {
        return $this->repo->loadList();
    }

    public function saveList(array $items)
    {
        $this->repo->saveList($items);
    }

    /**
     * @param $modelId integer
     * @param $modelName string
     * @return mixed
     */
    public function add($modelId, $modelName)
    {
        return $this->repo->add($modelId, $modelName);
    }

    /**
     * @param $modelId integer
     * @param $modelName string
     * @return mixed
     */
    public function remove($modelId, $modelName)
    {
        return $this->repo->remove($modelId, $modelName);
    }
}