module.exports = {
    name: "stats",
    exec: function(data){
        if(typeof data === "undefined") return;
        var channelName = data.channelName;
        require("../bot.js").maidbot.webClient.doSay("You can view my statistics here: https://maidbot.finlaydag33k.nl",channelName);
    }
};