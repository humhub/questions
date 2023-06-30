humhub.module('questions.Answer', function (module, require, $) {
    const Widget = require('ui.widget').Widget;
    const client = require('client');
    const loader = require('ui.loader');

    const Answer = Widget.extend();

    Answer.prototype.vote = function (evt) {
        const voting = evt.$trigger.parent();
        const summary = voting.find('div');
        const buttons = voting.find('button');

        loader.set(summary, {size: '8px', css: {padding: 0}});
        buttons.prop('disabled', true);

        client.post(evt).then(function (response) {
            if (response.success === true) {
                voting.replaceWith(response.content);
            } else {
                loader.reset(summary);
                buttons.prop('disabled', false);
            }
        }).catch(function (e) {
            module.log.error(e, true);
            loader.reset(summary);
            buttons.prop('disabled', false);
        });
    }

    Answer.prototype.best = function (evt) {
        const that = this;
        const button = evt.$trigger;
        const answer = button.closest('.questions-answer');

        loader.set(button, {size: '8px', css: {padding: 0}});

        client.post(evt).then(function (response) {
            if (response.success !== true) {
                loader.reset(button);
                return;
            }

            if (response.action === 'selected') {
                that.moveAnswerToNormalList(that.$.find('.questions-best-answer'));
                that.moveAnswerToBestPlace(answer);
            } else if (response.action === 'unselected') {
                that.moveAnswerToNormalList(answer);
            }
            that.refreshHeader(response.header);
        }).catch(function (e) {
            module.log.error(e, true);
            loader.reset(button);
        });
    }

    Answer.prototype.refreshHeader = function (headerHtml) {
        const answersList = this.$.find('.except-best-answers')
        const answersHeader = this.$.find('.except-best-answers-header');
        const answersExist = answersList.find('.questions-answer').length > 0;

        if (!answersExist && answersHeader.length) {
            answersHeader.remove();
        } else if (answersHeader.length) {
            answersHeader.replaceWith(headerHtml);
        } else {
            answersList.prepend(headerHtml);
        }
    }

    Answer.prototype.moveAnswerToBestPlace = function (answer) {
        answer.addClass('questions-best-answer');
        this.$.prepend(answer);
    }

    Answer.prototype.moveAnswerToNormalList = function (answer) {
        if (answer.length === 0) {
            return;
        }

        const exceptBestAnswersHeader = this.$.find('.except-best-answers-header');

        answer.removeClass('questions-best-answer');
        if (exceptBestAnswersHeader.length) {
            exceptBestAnswersHeader.after(answer);
        } else {
            this.$.find('.except-best-answers').prepend(answer);
        }
    }

    module.export = Answer;
});
