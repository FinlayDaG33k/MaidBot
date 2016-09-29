module.exports = {
    name: "help",
    exec: function(data){
        if(typeof data === "undefined") return;
        var channelName = data.channelName;
        require("../bot.js").maidbot.webClient.doSay("You can view my wiki here: https://github.com/FinlayDaG33k/MaidBot/wiki/Commands/. Or if you run into any problems, you can report them here: https://github.com/FinlayDaG33k/MaidBot/issues",channelName);
    }
};