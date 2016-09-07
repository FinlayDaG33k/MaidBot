module.exports = {
    name: "rep",
    exec: function(data,clienttoken){
		var request = require("request");
        if(typeof data === "undefined") return;
        var username = data.username,
            channelName = data.channelName,
            parameters = data.parameters;
		if(typeof parameters[2] !== "undefined"){
			if(typeof parameters[3] !== "undefined"){
				if(parameters[2].toLowerCase() == "maidbot" && parameters[3] == "+"){
					require("../bot.js").maidbot.webClient.doSay("I am glad to hear that you like my service Master.",channelName);
				}else if(parameters[2].toLowerCase() == "maidbot" && parameters[3] == "-"){
					require("../bot.js").maidbot.webClient.doSay("I am sad to hear that you don't like my service Master",channelName);
				}
				if(typeof parameters[4] !== "undefined"){
					request({
						uri: "https://dev.finlaydag33k.nl/maidbot/?clienttoken=" + clienttoken + "&method=rep&username=" + tokens[2] + "&rep=" + encodeURIComponent(tokens[3]) + "&message=" + tokens[4] + "&issuer=" + data.username,
						method: "GET",
						timeout: 5000,
						followRedirect: false
					}, 
					function(error, response, data) {
						require("../bot.js").maidbot.webClient.doSay(data,channelName);
					});
				}else{
					request({
						uri: "https://dev.finlaydag33k.nl/maidbot/?clienttoken=" + clienttoken + "&method=rep&username=" + parameters[2] + "&rep=" + encodeURIComponent(parameters[3]) + "&message=&issuer=" + data.username,
						method: "GET",
						timeout: 5000,
						followRedirect: false
						}, 
					function(error, response, data) {
						require("../bot.js").maidbot.webClient.doSay(data,channelName);
					});
				}
			}else{
				request({
					uri: "https://dev.finlaydag33k.nl/maidbot/?clienttoken=" + clienttoken + "&method=rep&username=" + parameters[2] + "",
					method: "GET",
					timeout: 5000,
					followRedirect: false
				}, 
				function(error, response, data) {
					require("../bot.js").maidbot.webClient.doSay(data,channelName);
				});							
			}	
		}
	}
};