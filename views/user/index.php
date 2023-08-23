<?php

/* @var $this View */

/* @var $dataProvider ArrayDataProvider */

use yii\web\View;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use app\core\entities\User;


$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;

?>
    <p>
        <?= Html::a('Создать пользователя', ['user/create'], [
            'class' => 'btn btn-success',
            'data' => [
                'confirm' => 'Вы действительно хотите создать нового пользователя?',
                'method' => 'post',
                'params' => ['type' => 'userCreate']
            ],
        ]) ?>
    </p>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'username:text:Логин',
        'password:text:Пароль',
        'accessToken:text:Ключ API',
        'isAdmin:boolean:Администратор?',
        'isUsed:boolean:Аккаунт использован?',
        [
            'template' => '{set-admin} {set-used}',
            'class' => \yii\grid\ActionColumn::class,
            'buttons' => [
                'set-admin' => function ($url, $model) {
                    $confirmMessage = !$model['isAdmin'] ? 'Вы уверены, что хотите установить пользователю роль администратора?' : 'Вы уверены, что хотите снять права администратора?';
                    $icon = $model['isAdmin'] ? '[Снять администратора]' : '[Сделать администратором]';

                    return Html::a($icon, ['user/set-admin'],
                        [
                            'title' => 'Установить пользователю роль Администратора',
                            'data' => [
                                'confirm' => $confirmMessage,
                                'method' => 'post',
                                'params' => ['id' => $model['id'], 'isAdmin' => $model['isAdmin']]
                            ]
                        ]);


                },
                'set-used' => function ($url, $model) {
                    $confirmText = !$model['isUsed'] ? 'Вы уверены, что хотите пометить, что аккаунт использован?' : 'Вы уверены, что хотите пометить, что аккаунт не используется?';
                    $icon = $model['isUsed'] ? '[Не использован]' : '[Использован]';

                    return Html::a($icon, ['user/set-used', 'id' => $model['id'], 'isUsed' => $model['isUsed'] ?? 0],
                        [
                            'title' => 'Пометить аккаунт использованным',
                            'data' => [
                                'confirm' => $confirmText,
                                'method' => 'post',
                                'params' => ['id' => $model['id'], 'isUsed' => $model['isUsed']]

                            ]
                        ]);
                }
            ]
        ]
    ]
]) ?>