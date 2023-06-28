humhub.module('questions.Answer', function (module, require, $) {
    const Widget = require('ui.widget').Widget;

    const Answer = Widget.extend();

    Answer.prototype.voteUp = function (evt) {
        console.log('Answer voting UP');
    }

    Answer.prototype.voteDown = function (evt) {
        console.log('Answer voting DOWN');
    }

    Answer.prototype.best = function () {

    }

    module.export = Answer;
});
