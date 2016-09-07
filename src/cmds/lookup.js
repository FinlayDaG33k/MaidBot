module.exports = {
    name: "lookup",
    exec: function(data){
		var request = require("request");
        if(typeof data === "undefined") return;
        var username = data.username,
            channelName = data.channelName,
            parameters = data.parameters;
        console.log(parameters);
        if(parameters.length == 1){
			request({
				uri: "https://cointrust.xyz/wp-json/wp/v2/profile?slug=" + username,
				method: "GET",
				timeout: 5000,
				followRedirect: false
			}, 
			function(error, response, data) {
				if (!error && response.statusCode == 200) {
					var array = data[0];
					console.log(array);
					if(typeof array !== "undefined"){
						display_lookup_user(array.suspicion,array.uname,array.link);
					}else{	
						require("../bot.js").maidbot.webClient.doSay("I'm sorry, but I could not find you on Cointrust, but you can try to lookup yourself here: https://www.cointrust.xyz/?s=" + data.username, channelName);
					}
				}else{
					require("../bot.js").maidbot.webClient.doSay("I'm sorry Master, but I am not able to reach Cointrust by myself. You can try to lookup the user manually: https://www.cointrust.xyz/?s=" + data.username, channelName);
				}
			});
        }else if(parameters.length == 2){ 
			request({
				uri: "https://cointrust.xyz/wp-json/wp/v2/profile?slug=" + parameters[1],
				method: "GET",
				timeout: 5000,
				followRedirect: false
			}, 
			function(error, response, array) {
				if (!error && response.statusCode == 200) {
					if(typeof array !== "undefined"){
						display_lookup_user(array.suspicion,array.uname,array.link);
					}else{	
						require("../bot.js").maidbot.webClient.doSay("I'm sorry, but I could not find the user you requested, but you can try to lookup the user here: https://www.cointrust.xyz/?s=" + parameters[1], channelName);
					}
				}else{
					require("../bot.js").maidbot.webClient.doSay("I'm sorry Master, but I am not able to reach Cointrust by myself. You can try to lookup the user manually: https://www.cointrust.xyz/?s=" + parameters[1], channelName);
				}
			});
        }
    }
};

function display_lookup_user(suspicion,username,profilelink){
	if(suspicion == "trustworthy"){
		require("../bot.js").maidbot.webClient.doSay("I was able to find "+ username +" on Cointrust, and he or she seems to be quite Trustworthy. You can read his or her full profile at: " + profilelink, channelName);
	}else if(suspicion == "delayed_loan"){
		require("../bot.js").maidbot.webClient.doSay("I was able to find "+ username +" on Cointrust, but he or she seems to be pay his loans quite late. You can read his or her full profile at: " + profilelink, channelName);
	}else if(suspicion == "beggar"){
		require("../bot.js").maidbot.webClient.doSay("I was able to find "+ username +" on Cointrust, but he or she seems to beg a lot. You can read his or her full profile at: " + profilelink, channelName);
	}else if(suspicion == "hacker"){
		require("../bot.js").maidbot.webClient.doSay("I was able to find "+ username +" on Cointrust, but he or she seems to be a hacker. Be careful with him or her Master. You can read his or her full profile at: " + profilelink, channelName);
	}else if(suspicion == "abuser"){
		require("../bot.js").maidbot.webClient.doSay("I was able to find "+ username +" on Cointrust, but he or she seems to like abusing things. You can read his or her full profile at: " + profilelink, channelName);
	}else if(suspicion == "cleared"){
		require("../bot.js").maidbot.webClient.doSay("I was able to find "+ username +" on Cointrust, but he or she seems to have a past of suspicion, but has cleared it. You can read his or her full profile at: " + profilelink, channelName);
	}else if(suspicion == "scammer"){
		require("../bot.js").maidbot.webClient.doSay("I was able to find "+ username +" on Cointrust, but he or she seems to be a scammer! Be careful with him or her Master. You can read his or her full profile at: " + profilelink, channelName);
	}else if(suspicion == "spammer"){
		require("../bot.js").maidbot.webClient.doSay("I was able to find "+ username +" on Cointrust, but he or she seems to spam a lot. You can read his or her full profile at: " + profilelink, channelName);
	}else if(suspicion == "dwc"){
		require("../bot.js").maidbot.webClient.doSay("I was able to find "+ username +" on Cointrust, but you should be careful with him or her. You can read his or her full profile at: " + profilelink, channelName);
	}else if(suspicion == "none"){
		require("../bot.js").maidbot.webClient.doSay("I was able to find "+ username +" on Cointrust, but he or she seems to be neutral. You can read his or her full profile at: " + profilelink, channelName);
	}
}