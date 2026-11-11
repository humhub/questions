<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace questions;

use humhub\modules\notification\components\NotificationManager;
use humhub\modules\notification\models\Notification;
use humhub\modules\questions\notifications\AnsweredNotification;
use humhub\modules\questions\notifications\NewQuestionNotification;

class NotificationTest extends UnitTester
{
    public function testNewQuestionNotifiesTheMembers()
    {
        $this->becomeUser('Admin');

        $question = $this->createQuestion('Which colour?', 'Description.');

        // The members of the space are notified, the author is not
        $this->assertNotNull($this->findNotification(NewQuestionNotification::class, 3));
        $this->assertNull($this->findNotification(NewQuestionNotification::class, 1));

        $notification = NotificationManager::load($this->findNotification(NewQuestionNotification::class, 3));

        // The category keeps its id, so the settings of the users stay valid
        $this->assertSame('questions', $notification::category()->id);

        // The sentences keep their wording and placeholders
        $web = $notification->asWeb();
        $this->assertStringContainsString('has created the Question', $web);
        $this->assertStringContainsString('Which colour?', $web);
        $this->assertStringContainsString($question->content->container->displayName, $web);
        $this->assertSame('New Question "Which colour?"', $notification->asMailSubject());
    }

    public function testAnswerNotifiesTheAuthorOfTheQuestion()
    {
        $this->becomeUser('Admin');
        $question = $this->createQuestion('Which colour?', 'Description.');

        $this->becomeUser('User2');
        $answer = $this->createAnswer('Blue.', $question);
        $this->assertFalse($answer->isNewRecord);

        $record = $this->findNotification(AnsweredNotification::class, 1);
        $this->assertNotNull($record);
        $this->assertNull($this->findNotification(AnsweredNotification::class, 3));

        $notification = NotificationManager::load($record);
        $web = $notification->asWeb();
        $this->assertStringContainsString('has answered your Question', $web);
        $this->assertStringContainsString('Which colour?', $web);
        $this->assertStringContainsString($question->content->container->displayName, $web);
        $this->assertSame('New answer to your Question "Which colour?"', $notification->asMailSubject());
        $this->assertStringContainsString('aid=' . $answer->id, (string) $notification->getUrl());
    }

    public function testNotificationGoesWithTheAnswer()
    {
        $this->becomeUser('Admin');
        $question = $this->createQuestion('Which colour?', 'Description.');

        $this->becomeUser('User2');
        $answer = $this->createAnswer('Blue.', $question);
        $this->assertNotNull($this->findNotification(AnsweredNotification::class, 1));

        $answer->delete();
        $this->assertNull($this->findNotification(AnsweredNotification::class, 1));
    }

    private function findNotification(string $class, int $userId): ?Notification
    {
        return Notification::findOne(['class' => $class, 'user_id' => $userId]);
    }
}
