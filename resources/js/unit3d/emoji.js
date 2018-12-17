// emoji list for .textcomplete() sourced from node_modules/emojione-assets/emoji.json
export const emojiStrategy = require('../../../node_modules/emojione-assets/emoji.json');

export function textcomplete(selector = null) {
    selector = selector === null ? '.wysibb-body' : selector;

    $(selector).textcomplete(
        [
            {
                match: /\B:([\-+\w]*)$/,
                search(term, callback) {
                    let results = [];
                    let results2 = [];
                    let results3 = [];

                    _.each(emojiStrategy, (data, basename) => {
                        if (data.shortname.indexOf(term) > -1) {
                            results.push(basename);
                        } else {
                            if (data.shortname_alternates !== null && data.shortname_alternates.indexOf(term) > -1) {
                                results2.push(basename);
                            } else if (data.keywords !== null && data.keywords.indexOf(term) > -1) {
                                results3.push(basename);
                            }
                        }
                    });

                    if (term.length >= 3) {
                        results.sort((a, b) => {
                            return a.length > b.length;
                        });

                        results2.sort((a, b) => {
                            return a.length > b.length;
                        });

                        results3.sort();
                    }

                    let newResults = results.concat(results2).concat(results3);

                    callback(newResults);
                },

                template: function(basename) {
                    return (
                        '<img class="emojione" src="https://cdn.jsdelivr.net/emojione/assets/4.0/png/32/' +
                        basename +
                        '.png"> ' +
                        emojiStrategy[basename].shortname +
                        ''
                    );
                },

                replace: function(basename) {
                    return emojiStrategy[basename].shortname + ' ';
                },

                index: 1,
                maxCount: 10,
            },
        ],
        {
            footer: '<a href="http://www.emoji.codes" target="_blank">Browse All<span class="arrow">»</span></a>',
        }
    );
}

export default { emojiStrategy, textcomplete };
