module.exports = {
    name: "no_command",
    exec: function(channelName, call_toggle){
        if(typeof channelName === "undefined") return;
        if(call_toggle == false){
			require("../bot.js").maidbot.webClient.doSay("What do you want me to do Master?",channelName);
		}else{
			require("../bot.js").maidbot.webClient.doSay("Did you call for me Master?",channelName);
		}
    }
};