module.exports = {
    name: "rep",
    exec: function(data,clienttoken){
		var request = require("request");
        if(typeof data === "undefined") return;
        var username = data.username,
            channelName = data.channelName,
            parameters = data.parameters;
			console.log(parameters);
			if(typeof parameters[1] !== "undefined"){
				if(parameters[1].toLowerCase() == "maidbot" && parameters[2] == "+"){
					require("../bot.js").maidbot.webClient.doSay("I am glad to hear that you like my service Master.",channelName);
				}else if(parameters[1].toLowerCase() == "maidbot" && parameters[2] == "-"){
					require("../bot.js").maidbot.webClient.doSay("I am sad to hear that you don't like my service Master",channelName);
				}
				if(typeof parameters[2] !== "undefined"){
					if(typeof parameters[3] !== "undefined"){
						request({
							uri: "https://maidbot.finlaydag33k.nl/report.php?clienttoken=" + clienttoken + "&method=rep&username=" + parameters[1] + "&rep=" + encodeURIComponent(parameters[2]) + "&message=" + parameters[3] + "&issuer=" + data.username,
							method: "GET",
							timeout: 5000,
							followRedirect: false
						}, 
						function(error, response, data) {
							require("../bot.js").maidbot.webClient.doSay(data,channelName);
						});
					}else{
						request({
							uri: "https://maidbot.finlaydag33k.nl/report.php?clienttoken=" + clienttoken + "&method=rep&username=" + parameters[1] + "&rep=" + encodeURIComponent(parameters[2]) + "&message=&issuer=" + data.username,
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
						uri: "https://maidbot.finlaydag33k.nl/report.php?clienttoken=" + clienttoken + "&method=rep&username=" + parameters[1] + "&rep=ls&message=&issuer=" + username,
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
					uri: "https://maidbot.finlaydag33k.nl/report.php?clienttoken=" + clienttoken + "&method=rep&username=" + username + "&rep=ls&issuer=" + username,
					method: "GET",
					timeout: 5000,
					followRedirect: false
				}, 
				function(error, response, data) {
					require("../bot.js").maidbot.webClient.doSay(data,channelName);
				});		
			}	
	}
};