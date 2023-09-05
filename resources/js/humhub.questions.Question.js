humhub.module('questions.Question', function (module, require, $) {
    const object = require('util').object;
    const client = require('client');
    const Content = require('content').Content;
    const loader = require('ui.loader');
    const Widget = require('ui.widget').Widget;

    const Question = function (id) {
        Content.call(this, id);

        // Save new Answer form by Ctrl+S
        $(document).bind('keydown', function (evt) {
            const saveAnswerButton = $('[data-action-click=saveAnswer]');
            if (evt.ctrlKey && evt.which === 83 && saveAnswerButton.length && !loader.is(saveAnswerButton)) {
                saveAnswerButton.trigger('focus').trigger('click');
            }
        });
    }

    object.inherits(Question, Content);

    Question.prototype.update = function (update) {
        this.loader();
        update.then($.proxy(this.handleUpdateSuccess, this))
            .catch(Question.handleUpdateError)
            .finally($.proxy(this.loader, this, false));
    }

    Question.prototype.handleUpdateSuccess = function (response) {
        const streamEntry = this.streamEntry();
        return streamEntry.replace(response.output).then(function () {
            module.log.success('success.saved');
        });
    }

    Question.prototype.submitEditForm = function (evt) {
        const that = this;
        const $errorMessage = that.$.find('.errorMessage');
        this.loader();
        $errorMessage.parent().hide();
        client.submit(evt).then(function (response) {
            if (!response.errors) {
                that.handleUpdateSuccess(response);
            } else {
                var errors = '';
                $.each(response.errors, function (key, value) {
                    errors += value + '<br />';
                });
                $errorMessage.html(errors).parent().show();
            }
        }).catch(Question.handleUpdateError)
            .finally($.proxy(this.loader, this, false));
    }

    Question.prototype.cancelEditForm = function (evt) {
        loader.set(evt.$target.parent().find('button'), {size: '8px', css: {padding: 0}});
        this.streamEntry().cancelEdit();
    }

    Question.prototype.loader = function ($loader) {
        this.streamEntry().loader($loader);
    }

    Question.prototype.streamEntry = function () {
        return this.parent();
    }

    Question.handleUpdateError = function (e) {
        module.log.error(e, true);
    }

    Question.prototype.answersList = function () {
        return this.$.find('.except-best-answers');
    }

    Question.prototype.refreshAnswersCount = function (count) {
        this.$.find('.questions-answers-count').html(count);
    }

    Question.prototype.getAnswer = function (id) {
        return this.$.find('[data-answer=' + id + ']');
    }

    Question.prototype.refreshUpdatedAnswer = function (id) {
        const answerBlock = this.getAnswer(id);
        this.initWidgets(answerBlock);
        setTimeout(() => answerBlock.removeClass('questions-highlight-answer'), 1000);
    }

    Question.prototype.initWidgets = function (elements) {
        elements.find('[data-ui-widget]').each(function () {
            Widget.instance($(this));
        });
    }

    Question.prototype.saveAnswer = function (evt) {
        const that = this;

        client.submit(evt).then(function (response) {
            if (typeof response.form === 'string') {
                const answerFormSelector = '.questions-answer-form[data-answer-form=' + response.answerFormId + ']';
                const answerForm = that.$.find(answerFormSelector);
                if (answerForm.length) {
                    answerForm.replaceWith(response.form);
                    that.initWidgets(that.$.find(answerFormSelector));
                }
            }

            if (response.success !== true) {
                return;
            }

            that.refreshAnswersCount(response.count);

            let answerBlock = that.getAnswer(response.answer);
            if (answerBlock.length === 0) {
                const collapseButton = that.$.find('.questions-toggle-btn[data-action-click=collapse]');
                const expandButton = that.$.find('.questions-toggle-btn[data-action-click=expand]');
                if (collapseButton.is(':hidden') && expandButton.is(':hidden')) {
                    collapseButton.show();
                }
                that.answersList().append(response.content);
            } else {
                answerBlock.replaceWith(response.content);
            }

            that.refreshUpdatedAnswer(response.answer);

            loader.init();
        }).catch(function (error) {
            module.log.error(error, true);
        });
    }

    module.export = Question;
});
