/**
 * TweetUI v1.0. UI related functions
 *
 * @author Pushpak Patel <https://github.com/pushpakpop>
 * License: GPLv3
 *
 */
var TweetUI = (function (self) {

    /**
     * Set the speed for fadeOut function and duration for the setTimeout()
     */
    self.config = {
        speed: 400,
        duration: 2500,
        maxDuration: 15000
    };
    self.loadingOverlayEl = $("#loading-overlay");
    self.notificationEl = $("#notification");
    self.retweetModalEl = $('#retweet-modal');
    self.deleteModalEl = $('#delete-modal');

    /**
     * Shows the loading.gif
     */

    self.showLoader = function () {
        self.loadingOverlayEl.css('display', 'block');
        setTimeout(function () {
            TweetUI.hideLoader();
        }, self.config.maxDuration); // hide loader in 10sec if something goes wrong            
    };

    /**
     * Hide the loadinggif
     */
    self.hideLoader = function () {
        self.loadingOverlayEl.css('display', 'none');
    };

    /**
     * Shows notification
     * @param notification message to be shown
     */
    self.showPopup = function (message) {
    	self.notificationEl.removeAttr("style");
    	$("#Tweetbar").attr("style","margin-top:0px;");
        self.notificationEl.find('#notification-msg').text(message);
        self.notificationEl.fadeIn(self.config.speed);
        setTimeout(function () {
        	self.notificationEl.attr("style","display:none;");
    		$("#Tweetbar").attr("style","margin-top:30px;");
            self.notificationEl.fadeOut(self.config.speed);
        }, self.config.duration);
    };

    /**
     * Populates the handlebar template with the tweets and shows tweets to user
     * @param tweets json object recieved from twitter
     */
    self.loadTweets = function (data) {
        var html = Tweet.tweetTemplate({
            data: data
        });
        Tweet.tweetThreadEl.html(html);
    };

    /**
     * Swaps the retweet/retweeted link
     * @param type[retweet/retweeted] and the id of the tweet whose links are to be swaped
     */
    self.swapRetweetLink = function (type, id) {

        var currentTweetEl;
        if (type === "retweet") {
            currentTweetEl = $('#' + id + ' .retweet');
            currentTweetEl.removeClass('retweet');
            currentTweetEl.addClass('retweeted');
            currentTweetEl.text('Retweeted');
            currentTweetEl.attr('title', 'Undo Retweet');
        } else {
            currentTweetEl = $('#' + id + ' .retweeted');
            currentTweetEl.removeClass('retweeted');
            currentTweetEl.addClass('retweet');
            currentTweetEl.text('Retweet');
            currentTweetEl.attr('title', 'Retweet');
        }
    };

    /**
     * Swaps the favorite/favorited link
     * @param type[favorite/favorited] and the id of the tweet whose links are to be swaped
     */
    self.swapFavoriteLink = function (type, id) {

        var currentTweetEl;
        if (type === "favorite") {
            currentTweetEl = $('#' + id + " .favorite");
            currentTweetEl.removeClass('favorite');
            currentTweetEl.addClass('favorited');
            currentTweetEl.text('favorited');
            currentTweetEl.attr('title', 'Undo Favorite');
        } else {
            currentTweetEl = $('#' + id + " .favorited");
            currentTweetEl.removeClass('favorited');
            currentTweetEl.addClass('favorite');
            currentTweetEl.text('favorite');
            currentTweetEl.attr('title', 'favorite');
        }
    };

    /**
     * Shows the modal containing the tweet that is to be retweeted
     */
    self.showRetweetModal = function (obj) {
        self.retweetId = obj.closest('.tweet').attr('id');
        self.retweetModalEl.children('.modal-body').html(obj.closest('.tweet').html());
        self.retweetModalEl.modal('show');
    };

    /**
     * Shows the modal containing the tweet that is to be deleted
     */
    self.showDeleteModal = function (obj) {
        self.deleteId = obj.closest('.tweet').attr('id');
        self.deleteModalEl.children('.modal-body').html(obj.closest('.tweet').html());
        self.deleteModalEl.modal('show');
    };

    return self;
})(TweetUI || {});