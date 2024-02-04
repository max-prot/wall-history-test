<?php

namespace app\controllers;

use app\core\entities\Post;
use app\core\forms\LoginForm;
use app\core\forms\PostForm;
use app\core\forms\ReactionForm;
use Yii;
use yii\base\Response;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'rules', 'about'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $form = new PostForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $post = Post::make(Yii::$app->user->id, $form->author, $form->message, Yii::$app->request->userIP);
                $post->save(false);
                Yii::$app->session->setFlash('success', 'Пост успешно добавлен!');
                return $this->refresh();
            } catch (\DomainException $exception) {
                Yii::$app->errorHandler->logException($exception);
                Yii::$app->session->setFlash('danger', $exception->getMessage());
            }
        }

        return $this->render('index', [
            'model' => $form,
            'dataProvider' => new ActiveDataProvider([
                'query' => Post::find()->where(['user_id' => Yii::$app->user->id]),
                'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
                'pagination' => [
                    'pageSize' => false,
                ]
            ])
        ]);
    }

    public function actionReaction($id)
    {
        $form = new ReactionForm();
        $post = $this->findModel($id);
        $user = Yii::$app->user->identity;

        try {
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                $user->withdrawBalance($form->calculateReactionCost());
                $post->addReaction($form->reaction, $form->quantity);
                $post->save(false);
                Yii::$app->session->setFlash('success', 'Добавлена реакция для поста №' . $post->id);
                return $this->redirect(['index']);
            }
        } catch (\DomainException $exception) {
            Yii::$app->errorHandler->logException($exception);
            Yii::$app->session->setFlash('danger', $exception->getMessage());
        }

        return $this->render('reaction', [
            'model' => $form,
            'post' => $post,
        ]);
    }

    /**
     * @return string
     */
    public function actionRules(): string
    {
        return $this->render('rules');
    }

    /**
     * @return string
     */
    public function actionAbout(): string
    {
        return $this->render('about');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Пост не найден.');
    }


}
