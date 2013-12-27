$(document).ready(function () {

    //get hashtag from the url when the document is ready
    var currentLocation = window.location.hash.substring(1);

    if (currentLocation === "" || currentLocation === "home") //if home page
    {
        //get users home timeline when page loads
        Tweet.getHomeTimeline();
    } else if (currentLocation === "me") {
        //get the users own tweets
        Tweet.getUserTimeline(currentLocation);
    } else {
        //get the tweets of the username that is present in the URL hashtag
        Tweet.getUserTimeline(currentLocation);
    }

});


// contains event handlers
(function () {

    var statusEl = $('#status'),
        countEl = $('#count span'),
        statusCountEl = $('#status'),
        filterEl = $("#filter"),
        userTweetEl = $(".tweet-user-list li");


    //get users home timeline when clicks on home
    $('#home').click(function () {
        Tweet.getHomeTimeline();
		window.location.hash = "home";
    });


    // current user's tweets
    $('#my-tweets').click(function () {
        Tweet.getUserTimeline("me");
        window.location.hash = "me";
    });


    //get the followers timeline tweets
    $('.followers').click(function () {
        var uname = $(this).children('div').children('div').children('p').children('span').text();
        window.location.hash = uname;
        Tweet.getUserTimeline(uname);

    });


    //open modal window when user clicks on retweet link and set retweet id
    $('.delete-tweet').live('click', function () {
        TweetUI.showDeleteModal($(this));
    });


    // Delete a status from user's timeline
    $('#btn-delete').click(function () {
        setTimeout(function () {
            TweetUI.deleteModalEl.modal('hide');
        }, 1500);
        Tweet.deleteTweet(TweetUI.deleteId);

    });


    //open modal window when user clicks on retweet link and set retweet id
    $('.retweet').live('click', function () {
        TweetUI.showRetweetModal($(this));
    });


    // retweet the tweet when user confirms to retweet from the modal window
    $('#btn-retweet').click(function () {
        setTimeout(function () {
            TweetUI.retweetModalEl.modal('hide');
        }, 1500);
        Tweet.reTweet(TweetUI.retweetId);
    });


    //undo retweet of a retweeted status
    $('.retweeted').live('click', function () {
        var unRetweetId = $(this).closest('.tweet').attr('id');
        Tweet.undoRetweet(unRetweetId);
    });


    //farovite a tweet
    $('.favorite').live('click', function () {
        var favoriteId = $(this).closest('.tweet').attr('id');
        Tweet.favoriteTweet(favoriteId);
    });


    // unfavorite a tweet
    $('.favorited').live('click', function () {
        var favoritedId = $(this).closest('.tweet').attr('id');
        Tweet.undoFavorite(favoritedId);
    });


    //post tweets to the current user's timeline
    $("#update-status").click(function () {
        if (statusEl.val() === "") {
            TweetUI.showPopup(strings.errorBlankStatus);
            statusEl.focus();
            return false;
        }
        if (statusEl.val().length > 140) {
            TweetUI.showPopup(strings.errorMaxCharacters);
            statusEl.focus();
            return false;
        }
        TweetUI.showLoader(); //show loading animation
        Tweet.updateStatus(statusEl.val());
    });

    //update remaining characters count as user types
    statusCountEl.on('keyup', function () {
        countEl.text(140 - statusCountEl.val().length);

    });

    //download tweets
    $('#export-csv').click(function () {
        TweetUI.showLoader(); //show loading animation
        Tweet.exportCsvTweets();
    });
    
    $('#export-xls').click(function () {
        TweetUI.showLoader(); //show loading animation
        Tweet.exportXlsTweets();
    });

    //filter followers as the user types in the search box
    filterEl.on("keyup", function () {
        var filter = $(this).val();
        userTweetEl.each(function () {
            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                $(this).slideUp();
            } else {
                $(this).slideDown();
                $(this).removeAttr("style");
                $(this).keyup();
            }
        });
        
    });

})();
