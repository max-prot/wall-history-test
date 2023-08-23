<?php

/**
 * @var $this View
 * @var $model Post
 */

use app\core\entities\Post;
use yii\helpers\Html;
use yii\web\View;

?>
<div class="card card-default">
    <div class="card-body">
        <h5 class="card-title"><?= $model->getAuthor() ?></h5>
        <p><?= $model->getMessage() ?></p>
        <div class="reactions-container">
            <?php foreach ($model->getReactions() as $reaction): ?>
                <div class="reaction">
                    <span><?= $reaction['emoji'] ?></span>
                    <span><?= $reaction['count'] ?></span>
                </div>
            <?php endforeach; ?>
            <small>
                <?= Html::a("Add reaction", ['reaction', 'id' => $model->id]) ?>
            </small>
        </div>
        <p>
            <small class="text-muted">
                ID: <?= $model->id ?> |
                <?= $model->getFormattedDate() ?> |
                <?= $model->getDisplayedIP() ?>
            </small>
        </p>
    </div>
</div>
