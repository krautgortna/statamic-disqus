var DISQUS_ADDON =
    DISQUS_ADDON ||
    (function () {
        var _args = {}; // private

        const threadDetailsURL = (id, forum, args) => {
            return (
                "https://disqus.com/api/3.0/threads/details.json?thread:ident=" +
                id +
                "&forum=" +
                forum +
                "&api_key=" +
                args.api_key
            );
        };

        const http400message = (id, forum) => {
            return (
                "HTTP 400: Unable to find thread 'ident:" +
                id +
                "' in forum '" +
                forum +
                "'"
            );
        };

        async function getResponse(id, forum) {
            let response;
            try {
                response = await fetch(threadDetailsURL(id, forum, _args));
            } catch (error) {
                console.error(error);
            }

            if (response?.ok) {
                let json = await response.json();
                return json;
            } else if (response?.status === 400) {
                console.log(http400message(id, forum));
            } else {
                console.error(`HTTP Response Code: ${response?.status}`);
            }
        }

        return {
            init: function (Args) {
                _args = Args;
            },
            getCounts: async function (span, id, forum) {
                let json = await getResponse(id, forum);
                if (json) console.log(json);
                if (json?.response?.posts) span.innerHTML = json.response.posts;
            },

            getLikes: async function (span, id, forum) {
                let json = await getResponse(id, forum);
                if (json) console.log(json);
                if (json?.response?.likes) span.innerHTML = json.response.likes;
            },
        };
    })();
