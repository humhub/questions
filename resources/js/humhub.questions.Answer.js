humhub.module('questions.Answer', function (module, require, $) {
    const Widget = require('ui.widget').Widget;
    const client = require('client');
    const loader = require('ui.loader');

    const Answer = Widget.extend();

    Answer.prototype.vote = function (evt) {
        const votingArea = evt.$trigger.parent();

        loader.set(votingArea, {size: '8px', css: {padding: 0}});

        client.post(evt).then(function (response) {
            votingArea.replaceWith(response.content);
        }).catch(function (e) {
            module.log.error(e, true);
            loader.reset(evt.$trigger);
        });
    }

    Answer.prototype.best = function () {

    }

    module.export = Answer;
});
