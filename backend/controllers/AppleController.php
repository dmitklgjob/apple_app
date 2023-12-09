<?php

declare(strict_types=1);

namespace backend\controllers;

use Apple\Application\ReadModel\GetAll;
use Apple\Application\UseCase\Bite;
use Apple\Application\UseCase\Fell;
use Apple\Application\UseCase\Generate;
use Apple\Domain\AppleRepositoryInterface;
use backend\forms\BiteUpdateForm;
use Throwable;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;

/**
 * @property-read Request $request
 * @property-read Response $response
 */
final class AppleController extends Controller
{

    public function __construct(
        string $id,
        Module $module,
        private readonly AppleRepositoryInterface $appleRepository,
        private readonly Generate\Handler $generateApplesHandler,
        private readonly GetAll\Fetcher $getAllFetcher,
        private readonly Fell\Handler $fellHandler,
        private readonly Bite\Handler $biteHandler,
        array $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    /**
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        $this->appleRepository->checkGrowingApples();
        $this->appleRepository->checkRottenApples();

        return parent::beforeAction($action);
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'generate-apples' => ['post'],
                    'fell' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $dataProvider = $this->getAllFetcher->fetch(
            new GetAll\Query(),
        );

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionGenerateApples(): Response
    {
        $appleCount = $this->generateApplesHandler->handle();

        Yii::$app->session->setFlash('success', 'Яблоки созданы. Количество: '. $appleCount);

        return $this->redirect('/apple/index');
    }

    public function actionFell(int $id): void
    {
        try {
            $this->fellHandler->handle($id);
            Yii::$app->session->setFlash('success', 'Яблоко упало. ID: '. $id);
        } catch (Throwable $e) {
            Yii::$app->session->setFlash('error', "При падении яблока ID: $id произошла ошибка: {$e->getMessage()}");
        }

        $this->redirect(Yii::$app->request->referrer ?: '/apple/index');
    }

    public function actionAjaxBite(int $id): array|string
    {
        $apple = $this->appleRepository->get($id);

        $form = new BiteUpdateForm();

        if (!$this->request->isPost) {
            return $this->renderAjax(
                '_bite_form',
                [
                    'model' => $form,
                    'apple' => $apple,
                ]
            );
        }

        $this->response->format = Response::FORMAT_JSON;

        $form->load($this->request->post());
        if (!$form->validate()) {
            Yii::$app->session->setFlash(
                'warning',
                "Ошибка валидации яблоко ID: $id '{$form->getFirstError('percent')}'"
            );

            $this->response->statusCode = 400;

            return [];
        }

        try {
            $restPercent = $this->biteHandler->handle(
                new Bite\Command(
                    $id,
                    $form->getPercent(),
                )
            );

            if ($restPercent === 0) {
                Yii::$app->session->setFlash('success', 'Яблоко полностью съедено');
            } else {
                Yii::$app->session->setFlash('success', 'Яблоко откушено');
            }
        } catch (Throwable $e) {
            Yii::$app->session->setFlash(
                'error',
                "При попытке откусить яблоко ID: $id произошла ошибка: {$e->getMessage()}"
            );
        }

        return [];
    }
}
