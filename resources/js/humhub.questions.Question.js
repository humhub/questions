humhub.module('questions.Question', function (module, require, $) {
    const object = require('util').object;
    const client = require('client');
    const Content = require('content').Content;
    const loader = require('ui.loader');
    const modal = require('ui.modal');

    const Question = function (id) {
        Content.call(this, id);
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

    Question.prototype.answersListHeader = function () {
        return this.answersList().children('.except-best-answers-header');
    }

    Question.prototype.getAnswer = function (id) {
        return this.$.find('[data-answer=' + id + ']');
    }

    Question.prototype.addAnswer = function (evt) {
        const that = this;
        modal.load(evt).then(function () {
            modal.global.$.one('submitted', function (e, response) {
                if (response.success !== true) {
                    return;
                }

                const listHeader = that.answersListHeader();
                if (listHeader.length === 0) {
                    that.answersList().append(response.header);
                } else {
                    listHeader.replaceWith(response.header);
                }

                const answerBlock = that.getAnswer(response.answer);
                if (answerBlock.length === 0) {
                    that.answersList().append(response.content);
                } else {
                    answerBlock.replaceWith(response.content);
                }
                setTimeout(function () {
                    that.getAnswer(response.answer).removeClass('questions-highlight-answer')
                }, 1000);
            });
        }).catch(function (e) {
            module.log.error(e, true);
        });
    }

    Question.prototype.loadAnswers = function (evt) {
        const that = this;

        client.post(evt).then(function (response) {
            that.answersList().html(response.html);
            evt.$trigger.remove();
        }).catch(function (e) {
            module.log.error(e, true);
            loader.reset(evt.$trigger);
        });
    }

    module.export = Question;
});
