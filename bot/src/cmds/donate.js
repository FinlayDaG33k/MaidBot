module.exports = {
    name: "help",
    exec: function(data){
        if(typeof data === "undefined") return;
        var channelName = data.channelName;
        require("../bot.js").maidbot.webClient.doSay("If you want to donate, you can send BTC to me to fund my development: 1BRoDCbnJ7kTS5dvVhjLdQnyqSWWjWC6SS",channelName);
    }
};