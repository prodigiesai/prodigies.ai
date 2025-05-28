<?php
/* * ********************************************************************************************
 * 								Open Real Estate
 * 								----------------
 * 	version				:	V1.38.0
 * 	copyright			:	(c) 2016 Monoray
 * 							http://monoray.net
 * 							http://monoray.ru
 *
 * 	website				:	http://open-real-estate.info/en
 *
 * 	contact us			:	http://open-real-estate.info/en/contact-us
 *
 * 	license:			:	http://open-real-estate.info/en/license
 * 							http://open-real-estate.info/ru/license
 *
 * This file is part of Open Real Estate
 *
 * ********************************************************************************************* */
namespace application\modules\favorite\services;

use \application\modules\favorite\repositories\FavoriteHybridRepository;
use FavoriteForm;

class FavoriteStorageService
{
    /** @var FavoriteHybridRepository */
    private $repository;

    public function __construct()
    {
        $this->repository = new FavoriteHybridRepository('fav');
    }

    public function getListCriteria()
    {
        $list = $this->getList();

        $data = [];

        foreach ($list as $modelName => $ids) {
            $data[] = [
                'modelName' => $modelName,
                'title' => $this->getTitleByModelName($modelName),
                'criteria' => $this->getCriteria($modelName, $ids)
            ];
        }

        return $data;
    }

    public function getCriteria($modelName, $ids)
    {
        $listAlias = [
            \Apartment::class => 't.',
        ];

        $alias = isset($listAlias[$modelName]) ? $listAlias[$modelName] : '';

        $criteria = new \CDbCriteria();
        $criteria->compare($alias . 'id', $ids);

        return $criteria;
    }

    public function getDataProvider($modelName, $ids)
    {
        return new \CActiveDataProvider($modelName, [
            'criteria' => $this->getCriteria($modelName, $ids),
        ]);
    }

    public function getTitleByModelName(string $modelName)
    {
        $list = [
            \Apartment::class => tt('Apartments list', 'apartments'),
        ];

        return isset($list[$modelName]) ? $list[$modelName] : null;
    }

    public function getList() : array
    {
        return $this->repository->loadList();
    }

    public function add(FavoriteForm $model)
    {
        return $this->repository->add($model->model_id, $model->model_name);
    }

    public function remove(FavoriteForm $model)
    {
        return $this->repository->remove($model->model_id, $model->model_name);
    }
}