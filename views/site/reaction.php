<?php

use app\core\forms\ReactionForm;
use app\core\helpers\Emoji;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/***
 * @var $model ReactionForm
 * @var $post \app\core\entities\Post
 * @var $this \yii\web\View
 * @var $form yii\widgets\ActiveForm
 */


$this->title = "Купить реакцию к посту";
?>


<div>
    <ul>
        <li>Баланс: <?= Html::tag("span", Yii::$app->user->identity->getPrettyBalance(), ['class' => 'badge badge-success'])  ?></li>
        <li>Будет списано: <span class="badge badge-danger" id="to-pay">$0.00</span> </li>
    </ul>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'reaction')->radioList([
        Emoji::THUMBS_UP_SIGN => Emoji::THUMBS_UP_SIGN,
        Emoji::THUMBS_DOWN_SIGN => Emoji::THUMBS_DOWN_SIGN,
        Emoji::HEAVY_BLACK_HEART => Emoji::HEAVY_BLACK_HEART,
        Emoji::PILE_OF_POO => Emoji::PILE_OF_POO,
    ]) ?>

    <?= $form->field($model, 'quantity')->textInput(['type' => 'number', 'id' => 'order-quantity']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var orderQuantity = document.getElementById("order-quantity");
        var toPay = document.getElementById("to-pay");

        orderQuantity.addEventListener("input", function() {
            var amount = parseFloat(orderQuantity.value) * <?= ReactionForm::REACTION_PRICE_PER_ONE ?>;

            // Используем Intl.NumberFormat для форматирования числа
            toPay.textContent = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(amount); // отображаем отформатированную сумму
        });
    });
</script>