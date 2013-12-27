// JavaScript Document for handlebar helper
// helper function for handlebars
(function (root, factory) {
    if (typeof exports === 'object') {
        // Node. Does not work with strict CommonJS, but
        // only CommonJS-like enviroments that support module.exports,
        // like Node.
        module.exports = factory(require('handlebars'));
    } else if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['handlebars'], factory);
    } else {
        // Browser globals (root is window)
        root.returnExports = factory(root.Handlebars);
    }
}(this, function (Handlebars) {

    /**
     * compares the specified two walues for equality. used to check for the current user's tweet
     */
    Handlebars.registerHelper('if_eq', function (context, options) {
        if (context == options.hash.compare) {
            return options.fn(this);
        } else {
            return options.inverse(this);
        }
    });

    /**
     * Converts the twitter's timestamp of the tweet to more userfreindly format.
     *@param timestamp to be converted
     */
    Handlebars.registerHelper('getDateTime', function (createdAt) {
        var rtnDate = moment(createdAt).fromNow();
        if (rtnDate.indexOf("days") < 0) {
            return rtnDate;
        } else {
            return moment(createdAt).fromNow();
        }
    });

    //handlebar helper for making clickabe links,hashtags,etc
    /**
     * Converts the text links,hastags and usernames to clickable ones.
     *@param tweet/text to be converted
     */
    Handlebars.registerHelper('twityfy', function (text) {
        var tweetText = Tweet.parseTweet(text);
        tweetText.replace(/<a/gi, "<a target='_blank'"); //add target="_blank" to all anchors.
        return new Handlebars.SafeString(tweetText);

    });

}));