module.exports = {
    name: "unknown",
    exec: function(data){
        if(typeof data === "undefined") return;
        var channelName = data.channelName;
		require("../bot.js").maidbot.webClient.doSay("I do not understand your command Master, please try again.",channelName);
    }
};