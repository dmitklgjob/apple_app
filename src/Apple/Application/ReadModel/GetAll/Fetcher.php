<?php

declare(strict_types=1);

namespace Apple\Application\ReadModel\GetAll;

use Apple\Domain\Apple;
use yii\data\ActiveDataProvider;

final class Fetcher
{
    public function fetch(Query $query): ActiveDataProvider
    {
        $dbQuery = Apple::find();

        $dbQuery->andFilterWhere(['status' => $query->getStatus()]);

        return new ActiveDataProvider([
            'query' => $dbQuery,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);
    }
}
