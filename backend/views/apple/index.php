<?php

/** @var yii\web\View $this */

use Apple\Domain\Apple;
use yii\bootstrap5\Modal;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/**
 * @var $dataProvider ActiveDataProvider
 */

$this->title = 'My Yii Application';
Modal::begin([
    'id' => 'modal-dialog-bite',
    'title' => 'Откусить яблоко',
    'size' => 'modal-md',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => TRUE],
    'centerVertical' => true,
]);
echo '<div id="modalContent"></div>';
Modal::end();
?>

<div class="site-index">
    <div class="body-content">
        <div class="mb-4 bg-transparent rounded-3">
            <div class="container-fluid text-right">
                <?= Html::beginForm(['/apple/generate-apples']) ?>
                <button type="submit" class="btn btn-lg btn-primary">
                    Создать яблоки
                </button>
                <?= Html::endForm() ?>
            </div>
        </div>
        <div class="row">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{items}\n{pager}",
                'tableOptions' => ['class' => 'table table-bordered'],
                'rowOptions' => function(Apple $model) {
                    if($model->getStatus()->isRotten()) {
                        return ['class' => 'table-danger'];
                    } else if($model->getStatus()->isOnTree()) {
                        return ['class' => 'table-success'];
                    } else {
                        return ['class' => 'table-primary'];
                    }
                },
                'columns' => [
                    [
                        'attribute' => 'id',
                        'headerOptions' => [
                            'style' => 'width:3%',
                        ],
                    ],
                    [
                        'attribute' => 'color',
                        'headerOptions' => [
                            'style' => 'width:5%',
                        ],
                        'value' => static function (Apple $model) {
                            $imageName = $model->getColor()->image();
                            $alt = $model->getColor()->label();

                            return "<img width='64' height='64' src='/img/$imageName' alt='$alt'>";
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'status',
                        'headerOptions' => [
                            'style' => 'width:5%',
                        ],
                        'value' => static function (Apple $model) {
                            return $model->getStatus()->label();
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'on_tree_at',
                        'headerOptions' => [
                            'style' => 'width:5%',
                        ],
                        'value' => static function (Apple $model) {
                            return $model->getOnTreeAt()->format('d.m.Y H:i:s');
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'fell_at',
                        'headerOptions' => [
                            'style' => 'width:5%',
                        ],
                        'value' => static function (Apple $model) {
                            return $model->getFellAt() ? $model->getFellAt()->format('d.m.Y H:i:s') : '';
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'rest_percent',
                        'headerOptions' => [
                            'style' => 'width:5%',
                        ],
                    ],
                    [
                        'header' => 'Действия',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{fell}{bite}',
                        'headerOptions' => [
                            'style' => 'width:5%',
                        ],
                        'buttons' => [
                            'fell' => function ($url, Apple $model, $key) {
                                return Html::a(
                                    '<button type="button" class="btn btn-success btn-sm rounded-5">
                                        Упасть
                                    </button>',
                                    ['/apple/fell', 'id' => $model->id],
                                    ['data-method' => 'POST'],
                                );
                            },
                            'bite' => function ($url, Apple $model, $key) {
                                return Html::a(
                                    'Откусить',
                                    ['/apple/ajax-bite', 'id' => $model->id],
                                    [
                                        'data-percent' => $model->getRestPercent(),
                                        'class' => 'btn btn-primary btn-sm rounded-5',
                                        'data' => [
                                            'toggle' => 'modal',
                                            'target' => '#modal-dialog-bite',
                                        ],
                                    ],
                                );
                            },
                        ],
                        'visibleButtons' => [
                            'fell' => function (Apple $model) {
                                return $model->getStatus()->isOnTree();
                            },
                            'bite' => function (Apple $model) {
                                return $model->getStatus()->isFell();
                            }
                        ]
                    ],
                ],
                'pager' => [
                    'options' => ['class' => 'pagination'],
                    'linkOptions' => ['class' => 'page-link'],
                    'activePageCssClass' => 'page-item active',
                    'disabledPageCssClass' => 'page-link',
                ],
            ]) ?>
        </div>
    </div>
</div>
