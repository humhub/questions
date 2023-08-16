<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\models\forms;

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\components\ContentContainerSettingsManager;
use humhub\modules\questions\Module;
use Yii;
use yii\base\Model;

/**
 * ContainerSettings class manages Settings per Container(Space/User)
 *
 * @package humhub.modules.questions.models.forms
 * @author Luke
 */
class ContainerSettings extends Model
{

    public ContentContainerActiveRecord $contentContainer;
    protected ?ContentContainerSettingsManager $settings = null;
    public bool $showAnswersInStream = false;

    public function init()
    {
        parent::init();

        /* @var $module Module */
        $module = Yii::$app->getModule('questions');
        $this->settings = $module->settings->contentContainer($this->contentContainer);

        $this->showAnswersInStream = (bool) $this->settings->get('showAnswersInStream', false);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['showAnswersInStream'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'showAnswersInStream' => Yii::t('QuestionsModule.base', 'Show preview of best answers in stream'),
        ];
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $this->settings->set('showAnswersInStream', $this->showAnswersInStream);

        return true;
    }
}
