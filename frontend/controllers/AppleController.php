<?php

declare(strict_types=1);

namespace frontend\controllers;

use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use Apple\Application\UseCase\GenerateApples;

final class AppleController extends Controller
{

    public function __construct(
        string $id,
        Module $module,
        private readonly GenerateApples\Handler $generateApplesHandler,
        array $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'generate-apples' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        return $this->render('index');
    }

    public function actionGenerateApples(): string
    {
        $this->generateApplesHandler->handle();
        return $this->render('index');
    }
}
