<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\questions\helpers;

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\questions\models\Question;
use humhub\modules\questions\models\QuestionAnswer;
use yii\helpers\Url as BaseUrl;

class Url extends BaseUrl
{
    public const ROUTE_MODULE = '/questions';
    public const ROUTE_SETTINGS = self::ROUTE_MODULE . '/settings';

    public const ROUTE_QUESTION = self::ROUTE_MODULE . '/question';
    public const ROUTE_QUESTION_CREATE = self::ROUTE_QUESTION . '/create';
    public const ROUTE_QUESTION_CREATE_FORM = self::ROUTE_QUESTION . '/create-form';
    public const ROUTE_QUESTION_EDIT = self::ROUTE_QUESTION . '/edit';
    public const ROUTE_QUESTION_VIEW = self::ROUTE_QUESTION . '/view';

    public const ROUTE_ANSWER = self::ROUTE_MODULE . '/answer';
    public const ROUTE_ANSWER_EDIT = self::ROUTE_ANSWER . '/edit';
    public const ROUTE_ANSWER_CONTENT = self::ROUTE_ANSWER . '/content';
    public const ROUTE_ANSWER_VOTE = self::ROUTE_ANSWER . '/vote';
    public const ROUTE_ANSWER_SELECT_BEST = self::ROUTE_ANSWER . '/select-best';
    public const ROUTE_ANSWER_DELETE = self::ROUTE_ANSWER . '/delete';

    private static function create($route, $params = [], ContentContainerActiveRecord $container = null, bool $scheme = false)
    {
        if ($container) {
            return $container->createUrl($route, $params, $scheme);
        } else {
            $params[0] = $route;
            return static::to($params, $scheme);
        }
    }

    public static function toContainerSettings(ContentContainerActiveRecord $container): string
    {
        return $container->createUrl(static::ROUTE_SETTINGS);
    }

    public static function toViewQuestion(Question $question, array $params = [], bool $scheme = false): string
    {
        if (isset($params['#'])) {
            $anchor = '#' . $params['#'];
            unset($params['#']);
        } else {
            $anchor = '';
        }

        return static::create(
            static::ROUTE_QUESTION_VIEW,
            array_merge(['id' => $question->id], $params),
            $question->content->container,
            $scheme,
        ) . $anchor;
    }

    public static function toEditQuestion(Question $question): string
    {
        return static::create(static::ROUTE_QUESTION_EDIT, ['id' => $question->id], $question->content->container);
    }

    public static function toCreateAnswer(Question $question): string
    {
        return self::toViewQuestion($question, ['#' => 'create-answer-form']);
    }

    public static function toViewAnswers(Question $question): string
    {
        return self::toViewQuestion($question, ['#' => 'answers']);
    }

    public static function toEditAnswer(QuestionAnswer $answer): string
    {
        return static::create(static::ROUTE_ANSWER_EDIT, ['qid' => $answer->question->id, 'id' => $answer->id], $answer->question->content->container);
    }

    public static function toContentAnswer(QuestionAnswer $answer): string
    {
        return static::create(static::ROUTE_ANSWER_CONTENT, ['id' => $answer->id], $answer->question->content->container);
    }

    public static function toViewAnswer(QuestionAnswer $answer, bool $scheme = false): string
    {
        return self::toViewQuestion($answer->question, ['aid' => $answer->id, '#' => 'answer' . $answer->id], $scheme);
    }

    public static function toVoteUpAnswer(QuestionAnswer $answer): string
    {
        return static::create(static::ROUTE_ANSWER_VOTE, ['id' => $answer->id, 'vote' => 'up'], $answer->question->content->container);
    }

    public static function toVoteDownAnswer(QuestionAnswer $answer): string
    {
        return static::create(static::ROUTE_ANSWER_VOTE, ['id' => $answer->id, 'vote' => 'down'], $answer->question->content->container);
    }

    public static function toSelectBestAnswer(QuestionAnswer $answer): string
    {
        return static::create(static::ROUTE_ANSWER_SELECT_BEST, ['id' => $answer->id], $answer->question->content->container);
    }

    public static function toDeleteAnswer(QuestionAnswer $answer): string
    {
        return static::create(static::ROUTE_ANSWER_DELETE, ['id' => $answer->id], $answer->question->content->container);
    }
}
