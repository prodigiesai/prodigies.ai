<?php
namespace application\modules\favorite\repositories;

use application\modules\favorite\models\Favorite;

class FavoriteDbRepository implements FavoriteRepositoryInterface
{
    private $userId;

    private $_cache = [];

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function loadList() : array
    {
        if (empty($this->_cache)) {
            foreach (Favorite::model()->findAll('user_id = :user_id', [':user_id' => $this->userId]) as $row) {
                $this->_cache[$row->model_name][$row->model_id] = $row->model_id;
            }
        }

        return $this->_cache;
    }

    public function saveList(array $items)
    {
        Favorite::model()->deleteAll('user_id = :user_id', [':user_id' => $this->userId]);

        /** @var FavoriteItem $item */
        foreach ($items as $modelName => $ids) {
            foreach ($ids as $modelId){
                $this->add($modelId, $modelName, false);
            }
        }
    }

    /**
     * @param $modelId integer
     * @param $modelName string
     * @param $find bool
     * @return mixed
     */
    public function add($modelId, $modelName, $find = true)
    {
        $favorite = null;

        if($find){
            $favorite = Favorite::model()->find(
                'user_id = :user_id AND model_name = :model_name AND model_id = :model_id',
                [
                    ':model_id' => $modelId,
                    ':model_name' => $modelName,
                    ':user_id' => $this->userId,
                ]);
        }

        if(!$favorite){
            $favorite = new Favorite();
        }

        $favorite->model_id = $modelId;
        $favorite->model_name = $modelName;
        $favorite->user_id = $this->userId;
        $favorite->date_created = new \CDbExpression('NOW()');

        return $favorite->save(false);
    }

    /**
     * @param $modelId integer
     * @param $modelName string
     * @return mixed
     */
    public function remove($modelId, $modelName)
    {
        return Favorite::model()->deleteAll(
            'user_id = :user_id AND model_name = :model_name AND model_id = :model_id',
            [
            ':model_id' => $modelId,
            ':model_name' => $modelName,
            ':user_id' => $this->userId,
            ]
        );
    }
}