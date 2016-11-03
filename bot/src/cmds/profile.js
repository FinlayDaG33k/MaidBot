module.exports = {
    name: "unknown",
    exec: function(data){
        if(typeof data === "undefined") return;
        var channelName = data.channelName,
			username = data.username,
			parameters = data.parameters;
		if(typeof parameters[1] !== "undefined"){
			require("../bot.js").maidbot.webClient.doSay("You can visit your MaidBot page on https://maidbot.finlaydag33k.nl/index.php?username=" + parameters[1],channelName);
		}else{
			require("../bot.js").maidbot.webClient.doSay("You can visit your MaidBot page on https://maidbot.finlaydag33k.nl/index.php?username=" + username,channelName);
		}
	}
};