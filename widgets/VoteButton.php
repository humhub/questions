<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\widgets;

use humhub\components\Widget;
use humhub\modules\questions\helpers\Url;
use humhub\modules\questions\models\QuestionAnswer;
use humhub\widgets\Button;
use Yii;

class VoteButton extends Widget
{
    public ?QuestionAnswer $answer;

    public string $type;

    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        return $this->answer instanceof QuestionAnswer &&
            $this->answer->question->getAnswerService()->canVote() &&
            parent::beforeRun();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return Button::defaultType()
            ->icon($this->getIcon())
            ->action('vote', $this->getUrl())
            ->cssClass($this->getStyleClass())
            ->tooltip($this->getTooltip())
            ->options(['disabled' => !$this->answer->getVoteService()->canVote()])
            ->loader(false);
    }

    private function getIcon(): string
    {
        return $this->type === 'up' ? 'caret-up' : 'caret-down';
    }

    private function getUrl(): string
    {
        return $this->type === 'up'
            ? Url::toVoteUpAnswer($this->answer)
            : Url::toVoteDownAnswer($this->answer);
    }

    private function getStyleClass(): string
    {
        return $this->answer->getVoteService()->isVotedWithType($this->type)
            ? 'active'
            : '';
    }

    private function getTooltip(): string
    {
        return $this->type === 'up'
            ? Yii::t('QuestionsModule.base', 'Upvote')
            : Yii::t('QuestionsModule.base', 'Downvote');
    }
}
