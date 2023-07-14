<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;
use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\questions\assets\QuestionsAssets;
use humhub\modules\questions\helpers\Url;
use humhub\modules\questions\models\Question;
use humhub\modules\questions\widgets\AnswerForm;
use humhub\modules\questions\widgets\Answers;
use humhub\widgets\Button;
use yii\web\View;

/* @var View $this */
/* @var Question $question */
/* @var int|null $currentAnswerId */
/* @var bool $isDetailView */
/* @var array $options */

QuestionsAssets::register($this);
?>
<?= Html::beginTag('div', $options) ?>

    <div data-ui-markdown>
        <?= RichText::output($question->description) ?>
    </div>

    <?php if (!$isDetailView) : ?>
        <br>
        <?= Button::info(Yii::t('QuestionsModule.base', 'Answer the question'))
            ->link(Url::toCreateAnswer($question))
            ->loader(false) ?>

        <?= Button::info(Yii::t('QuestionsModule.base', 'View all answers ({count})', [
                'count' => $question->getAnswerService()->getCount()]))
            ->link(Url::toViewAnswers($question))
            ->cssClass('active')
            ->loader(false) ?>
    <?php endif; ?>

    <?= Answers::widget([
        'question' => $question,
        'currentAnswerId' => $currentAnswerId,
        'displayAll' => $isDetailView
    ]) ?>

    <?php if ($isDetailView) : ?>
        <?= AnswerForm::widget(['question' => $question]) ?>
    <?php endif; ?>

<?= Html::endTag('div') ?>
