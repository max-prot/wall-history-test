<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use app\core\services\user\UserService;
use app\core\repositories\user\UserRepository;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class UserController extends Controller
{
    private UserRepository $userRepository;
    private UserService $usrService;

    /**
     * @param $id
     * @param $module
     * @param UserRepository $userRepository
     * @param UserService $usrService
     * @param array $config
     */
    public function __construct($id, $module, UserRepository $userRepository, UserService $usrService, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->userRepository = $userRepository;
        $this->usrService = $usrService;
    }

    /**
     * @return array[]
     */
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
                    'create' => ['POST'],
                    'set-used' => ['POST'],
                    'set-admin' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws ForbiddenHttpException
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if ($action) {
            $user = $this->userRepository->getById(Yii::$app->user->id);
            if ($user && !$user->isAdmin()) {
                throw new ForbiddenHttpException('Вам не разрешено проводить данное действие');
            }
        }

        return parent::beforeAction($action);
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $query = $this->userRepository->getAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);

    }

    /**
     * @return void|Response
     * @throws Exception
     */
    public function actionCreate()
    {
        if (\Yii::$app->request->post()) {
            $users = array_keys($this->userRepository->getAll());
            $userId = (max($users) + 1);

            try {
                $this->usrService->save($userId);
                Yii::$app->session->setFlash('success', 'Пользователь добавлен.');
                return $this->redirect(['user/index']);
            } catch (\DomainException $exception) {
                Yii::$app->errorHandler->logException($exception);
                Yii::$app->session->setFlash('danger', $exception->getMessage());
            }
        }
    }

    /**
     * @return void|Response
     */
    public function actionSetAdmin()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            try {
                $this->usrService->setAdmin($post['id'], $post['isAdmin']);
                Yii::$app->session->setFlash('success', 'Пользователю дана роль администратора.');
                return $this->redirect(['user/index']);
            } catch (\DomainException $exception) {
                Yii::$app->errorHandler->logException($exception);
                Yii::$app->session->setFlash('danger', $exception->getMessage());
            }
        }

    }

    /**
     * @return void|Response
     */
    public function actionSetUsed()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            try {
                $this->usrService->setUsed($post['id'], $post['isUsed']);
                Yii::$app->session->setFlash('success', 'Аккаунт помечен использованным');
                return $this->redirect(['user/index']);
            } catch (\DomainException $exception) {
                Yii::$app->errorHandler->logException($exception);
                Yii::$app->session->setFlash('danger', $exception->getMessage());
            }
        }
    }
}