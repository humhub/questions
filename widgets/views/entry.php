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
use humhub\widgets\Button;
use yii\web\View;

/* @var View $this */
/* @var Question $question */
/* @var array $options */

QuestionsAssets::register($this);
?>
<?= Html::beginTag('div', $options) ?>

    <div data-ui-markdown>
        <?= RichText::output($question->description) ?>
    </div>

    <br>
    <?= Button::info(Yii::t('QuestionsModule.base', 'Answer the question'))
        ->action('addAnswer', Url::toCreateAnswer($question)) ?>

    <?= Button::info(Yii::t('QuestionsModule.base', 'See all answers'))
        ->action('loadAnswers')
        ->cssClass('active') ?>

<?= Html::endTag('div') ?>
