<?php
namespace application\modules\favorite\repositories;

use CActiveRecord;

class FavoriteItem
{
    /** @var int  */
    private $modelId;

    /** @var string  */
    private $modelName;

    /** @var CActiveRecord */
    private $model;

    public function __construct(int $modelId, string $modelName)
    {
        $this->modelId = $modelId;
        $this->modelName = $modelName;
        //$this->model = (new $this->modelName)->findByPk($this->modelId);
    }

    /**
     * @return int
     */
    public function getModelId(): int
    {
        return $this->modelId;
    }

    /**
     * @return string
     */
    public function getModelName(): string
    {
        return $this->modelName;
    }

    /**
     * @return CActiveRecord
     */
    public function getModel(): CActiveRecord
    {
        return $this->model;
    }


}