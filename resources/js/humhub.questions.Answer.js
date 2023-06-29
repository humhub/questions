humhub.module('questions.Answer', function (module, require, $) {
    const Widget = require('ui.widget').Widget;
    const client = require('client');
    const loader = require('ui.loader');

    const Answer = Widget.extend();

    Answer.prototype.vote = function (evt) {
        const voting = evt.$trigger.parent();
        const summary = voting.find('div');

        loader.set(summary, {size: '8px', css: {padding: 0}});

        client.post(evt).then(function (response) {
            voting.replaceWith(response.content);
        }).catch(function (e) {
            module.log.error(e, true);
            loader.reset(summary);
        });
    }

    Answer.prototype.best = function (evt) {
        loader.set(evt.$trigger, {size: '8px', css: {padding: 0}});

        client.post(evt).then(function (response) {
            // TODO: Move answer to proper place after set/reset the best flag
            console.log('BEST');
        }).catch(function (e) {
            module.log.error(e, true);
            loader.reset(evt.$trigger);
        });
    }

    module.export = Answer;
});
