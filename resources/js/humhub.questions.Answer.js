humhub.module('questions.Answer', function (module, require, $) {
    const Widget = require('ui.widget').Widget;
    const client = require('client');
    const loader = require('ui.loader');
    const status = require('ui.status');
    const additions = require('ui.additions');

    const Answer = Widget.extend();

    Answer.prototype.init = function () {
        const question = this.$.closest('[data-content-component="questions.Question"]');
        this.Question = question.length ? Widget.instance(question) : null;
    }

    Answer.prototype.vote = function (evt) {
        const that = this;
        const voting = evt.$trigger.parent();
        const answer = voting.parent();
        const summary = voting.find('div');
        const buttons = voting.find('button');

        loader.set(summary, {size: '4px', css: {padding: 0}});
        buttons.prop('disabled', true);

        client.post(evt).then(function (response) {
            if (response.success === true) {
                voting.replaceWith(response.content);
                that.refreshTooltips(answer);
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
                that.moveToNormalList(that.$.find('.questions-best-answer'), response.titleSelect);
                that.moveToBestPlace(answer, response.titleUnselect);
            } else if (response.action === 'unselected') {
                that.moveToNormalList(answer, response.titleSelect);
            }
            that.Question.refreshAnswersCount(response.count);
            that.refreshTooltips(answer);
        }).catch(function (e) {
            module.log.error(e, true);
            loader.reset(button);
        });
    }

    Answer.prototype.refreshTooltips = function (container) {
        additions.apply(container.find('.tt'), 'tooltip');
    }

    Answer.prototype.moveToBestPlace = function (answer, title) {
        answer.addClass('questions-best-answer')
            .find('.questions-best-answer-button [data-original-title]')
            .attr('data-original-title', title);
        this.$.prepend(answer);
    }

    Answer.prototype.moveToNormalList = function (answer, title) {
        if (answer.length === 0) {
            return;
        }

        answer.removeClass('questions-best-answer')
            .find('.questions-best-answer-button [data-original-title]')
            .attr('data-original-title', title);
        this.$.find('.except-best-answers').prepend(answer);
    }

    Answer.prototype.collapse = function (evt) {
        this.toggleList(evt, true);
    }

    Answer.prototype.expand = function (evt) {
        this.toggleList(evt, false);
    }

    Answer.prototype.toggleList = function (evt, collapse) {
        this.Question.answersList().toggleClass('d-none', collapse);
        
        this.$.find('.questions-toggle-btn').removeClass('d-none');
        evt.$target.addClass('d-none');
    }

    Answer.prototype.edit = function (evt) {
        const that = this;
        const answerContent = evt.$target.closest('.questions-answer-content');
        const answerText = answerContent.find('[data-ui-richtext]');

        loader.set(answerText);

        client.get(evt).then(function(response) {
            answerText.replaceWith(response.form);
            that.Question.initWidgets(answerContent);
        }).catch(function(e) {
            module.log.error(e, true);
        });
    }

    Answer.prototype.save = function (evt) {
        this.Question.saveAnswer(evt);
    }

    Answer.prototype.cancelEdit = function (evt) {
        const that = this;

        client.submit(evt).then(function (response) {
            if (typeof(response.answer) !== 'undefined' && typeof(response.content) !== 'undefined') {
                that.Question.getAnswer(response.answer).replaceWith(response.content);
                that.Question.refreshUpdatedAnswer(response.answer);
            }
        }).catch(function (error) {
            module.log.error(error, true);
        });
    }

    Answer.prototype.delete = function (evt) {
        const that = this;
        const answerControls = evt.$target.closest('.nav.nav-pills');

        loader.set(answerControls, {size: '4px', css: {padding: 0, width: '45px'}});

        client.post(evt).then(function (response) {
            if (response.success === true) {
                that.Question.refreshAnswersCount(response.count);

                that.Question.getAnswer(response.answer)
                    .addClass('questions-deleted-answer')
                    .fadeOut('slow', function() {//TODO BS5
                        $(this).remove();
                        status.success(response.message);
                    });
            } else {
                loader.reset(answerControls);
                status.error(response.message);
            }
        }).catch(function (error) {
            module.log.error(error, true);
            loader.reset(answerControls);
        });
    }

    module.export = Answer;
});
