<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\modules\questions\models\QuestionAnswer;
use humhub\modules\questions\widgets\Answer;

/* @var QuestionAnswer[] $answers */
/* @var int $count */
?>
<h4><?= Yii::t('QuestionsModule.base', '{count} Answers', ['count' => $count]) ?></h4>

<?php foreach ($answers as $answer) : ?>
    <?= Answer::widget(['answer' => $answer]) ?>
<?php endforeach; ?>
