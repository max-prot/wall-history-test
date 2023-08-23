<?php

use app\core\forms\PostForm;
use yii\bootstrap4\ActiveForm;
use yii\captcha\Captcha;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ListView;


/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var yii\web\View $this */
/* @var $model PostForm */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::$app->name;
?>


<div class="row">

    <div class="col-md-6">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => [
                'tag' => 'div',
                'class' => 'list-wrapper',
                'id' => 'list-wrapper',
            ],
            'layout' => "{summary}\n{items}\n{pager}",
            'itemView' => '_post-item',
        ]);
        ?>
    </div>
    <div class="col-md-4">
        <div>
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'author')->textInput(['placeholder' => 'Олег']) ?>

            <?= $form->field($model, 'message')->textarea(['placeholder' => 'Ваши гениальные мысли, которые запомнти история']) ?>

            <?= $form->field($model, 'captcha')->widget(Captcha::class) ?>

            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>





