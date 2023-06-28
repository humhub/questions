<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

/* @var QuestionAnswer $answer */
/* @var array $options */

use humhub\libs\Html;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\questions\widgets\AnswerVoting;
use humhub\modules\user\widgets\Image;
use humhub\widgets\TimeAgo;

?>
<?= Html::beginTag('div', $options) ?>

    <?= AnswerVoting::widget(['answer' => $answer]) ?>

    <div class="questions-answer-content">
        <div class="questions-answer-header">
            <?= Image::widget([
                'user' => $answer->content->createdBy,
                'width' => 29
            ]) ?>
            <strong><?= $answer->content->createdBy->displayName ?></strong>
            &middot;
            <small><?= Yii::t('QuestionsModule.base', 'answered {date}', [
                'date' => TimeAgo::widget(['timestamp' => $answer->content->created_at])
            ]) ?></small>
        </div>
        <?= $answer->answer ?>
    </div>

<?= Html::endTag('div') ?>
