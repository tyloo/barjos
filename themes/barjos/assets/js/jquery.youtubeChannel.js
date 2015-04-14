
;(function($, undef) {
    $.fn.youtubeChannel = function(settings) {
        var that = this,
            // the API version to use
            apiVersion = 2.1,
            // the element where the plugin was called
            $ytEl = $(this),
            // the list element
            $ytList = $("<ul/>", { "class": "yt-channel-list" }),
            // maximum number of results that the api can return per call
            maxApiResults = 50,
            // string to hold the html to be inserted
            listHtml = '',
            // the plugin's options
            options = $.extend({}, {
                apiKey: "",
                username: "",
                channel: "",
                query: "",
                startIndex: 1,
                maxResults: 50,
                orderBy: "published",
                callback: function() {}
            }, settings),
            // the current offset (must start at 1)
            resultOffset = options.startIndex = (options.startIndex < 1 ? 1 : options.startIndex),
            /*  -- API OBJECT --  */
            api = {
                // failed to get videos?
                failed: false,
                // the array of videos
                videos: {},
                // number of videos
                videoCount: 0
            },
            /*  -- PLUGIN FUNCTIONS --  */
            // get the html for the header
            getTitle = function() {
                var isChannel = (options.channel !== "");
                if (options.username !== "") {
                    return "<a href=\"http://www.youtube.com/" + (isChannel ? "user/" : "/") +
                        options.username + "\" target=\"_blank\">" +
                        (isChannel ? options.channel : options.username) + "</a>";
                } else if (options.query !== '') {
                    return "<a href=\"http://www.youtube.com/results?" +
                        encodeURIComponent(options.query) + "&aq=f\" target=\"_blank\">&quot;" +
                        options.query + "&quot;</a>";
                } else {
                    return "<a href=\"http://www.youtube.com/\" target=\"_blank\">YouTube</a>";
                }
            },
            // calculate the next set of results to request from the API
            nextSet = function() {
                var resultCall = options.startIndex + options.maxResults - resultOffset;
                return (resultCall > maxApiResults) ? maxApiResults : resultCall;
            },
            // build the url to make the API call
            buildUrl = function() {
                var base = "https://gdata.youtube.com/feeds/api/videos",
                    params = [
                        "alt=json",
                        "v=" + apiVersion,
                        "orderby=" + options.orderBy,
                        "start-index=" + resultOffset,
                        "max-results=" + nextSet()
                    ];
                if (options.username !== "") {
                    params.push("author=" + options.username);
                } else if (options.query !== "") {
                    params.push("q=" + encodeURIComponent(options.query));
                }
                if (options.apiKey !== "") {
                    params.push("key=" + options.apiKey);
                }
                params.push("callback=?");
                return base + "?" + params.join("&");
            },
            // parse the videos' time (from secs to mins:secs)
            parseTime = function(secs) {
                var m, s = parseInt(secs, 10);
                m = Math.floor(s / 60);
                s -= (m * 60);
                return m + ":" + zeroFill(s, 2);
            },
            zeroFill = function (number, width)
            {
                width -= number.toString().length;
                if ( width > 0 )
                {
                    return new Array( width + (/\./.test( number ) ? 2 : 1) ).join( '0' ) + number;
                }
                return number + ""; // always return a string
            },
            // add a video to the list
            addVideo = function(vid) {
                // change the id to be more html friendly
                vid.htmlId = "videoid-" + vid.id;
                // add video data to the videos array
                api.videos[vid.id] = vid;
                // return the styled HTML
                return [
                    "<li id=\"", vid.htmlId, "\" class=\"yt-channel-video\">",
                        "<a target=\"_blank\" href=\"", vid.link, "\">",
                            "<span class=\"thumb-wrap\">",
                                "<img class=\"vid-thumb\" alt=\"", vid.title, "\" src=\"", vid.thumb, "\"/>",
                                "<span class=\"vid-duration\">", parseTime(vid.duration), "</span>",
                            "</span>",
                            "<div class=\"vid-details\">",
                                "<span class=\"vid-title\">", vid.title, "</span>",
                                "<span class=\"vid-views\">", vid.views, " vues</span>",
                            "</div>",
                        "</a>",
                    "</li>"
                ].join("");
            },
            // output the final HTML
            outputHtml = function() {
                // append the list of videos
                $ytFoot.before(listHtml);
                // clear the html string for further loadMore calls
                listHtml = "";
            },
            // parse the list of videos sent from the API
            parseList = function(data) {
                var e, i, feedlen;
                // do we have videos to add?
                if (data.feed.entry) {
                    feedlen = data.feed.entry.length;
                    // parse each video returned
                    for (i = 0; i < feedlen; i++) {
                        // local cache of the video entry
                        e = data.feed.entry[i];
                        // add the video to the videos array and return the HTML for the list
                        listHtml += addVideo({
                            id: (e ? e.media$group.yt$videoid.$t : ""),
                            link: (e ? e.media$group.media$player.url : ""),
                            title: (e ? e.media$group.media$title.$t : ""),
                            thumb: (e ? e.media$group.media$thumbnail[1].url : ""),
                            duration: (e ? e.media$group.yt$duration.seconds : 0),
                            views: (e && e.yt$statistics ? e.yt$statistics.viewCount : 0)
                        });
                        resultOffset++;
                        api.videoCount++;
                    }
                    // check if we want to list more results
                    if (resultOffset < options.maxResults) {
                        // make one more api call for more results
                        $.getJSON(buildUrl(), parseList);
                    } else {
                        // done retrieving videos, compile the HTML
                        outputHtml();
                        // use callback, if set
                        options.callback.apply(that, [api]);
                    }
                } else {
                    // if no results were returned on the first call...
                    if (resultOffset === options.startIndex) {
                        api.failed = true;
                        listHtml += "<li class=\"yt-channel-video\"><a>NO RESULTS</a></li>";
                    }
                    // we're done here, compile the HTML
                    outputHtml();
                    // use callback, if set
                    options.callback.apply(that, [api]);
                }
            };
        /*  -- API FUNCTIONS --  */
        api.loadMore = function loadMore(num) {
            // increase the maximum number of results
            options.maxResults += parseInt(num, 10);
            // make one more api call for more results
            $.getJSON(buildUrl(), parseList);
        };
        /*  -- PLUGIN MAIN CODE --  */
        // apply styling to the parent element
        $ytEl.addClass("yt-channel-holder");
        // set the header and append it
        // set copyright notice and append it
        $ytFoot = $("<li/>", {
            "class": "yt-channel-copy"
        }).appendTo($ytList);
        // display the list of videos
        $ytList.appendTo($ytEl);
        // start querying the API
        $.getJSON(buildUrl(), parseList);
        // maintain jQuery chainability
        that.api = api;
        return that;
    };
}(jQuery));
