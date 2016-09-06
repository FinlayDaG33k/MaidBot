console.log("MaidBot v2016.09.06.11 Initialized");
var clienttoken = "";
var call_toggle = false;

// Check if jQuery is in the page, and if not, inject it in the page
if(typeof jQuery === "undefined"){
	// Yes, you really need jQuery for this script to work
	var script = document.createElement('script'); 
	script.src = 'https://code.jquery.com/jquery-3.0.0.min.js'; // the URL to the jQuery library
	document.documentElement.firstChild.appendChild(script) // now append the script into HEAD, it will fetch and be executed
}

function display_lookup_user(suspicion,username,profilelink){
	if(suspicion == "trustworthy"){
		engine.chat("I was able to find "+ username +" on Cointrust, and he or she seems to be quite Trustworthy. You can read his or her full profile at: " + profilelink);
	}else if(suspicion == "delayed_loan"){
		engine.chat("I was able to find "+ username +" on Cointrust, but he or she seems to be pay his loans quite late. You can read his or her full profile at: " + profilelink);
	}else if(suspicion == "beggar"){
		engine.chat("I was able to find "+ username +" on Cointrust, but he or she seems to beg a lot. You can read his or her full profile at: " + profilelink);
	}else if(suspicion == "hacker"){
		engine.chat("I was able to find "+ username +" on Cointrust, but he or she seems to be a hacker. Be careful with him or her Master. You can read his or her full profile at: " + profilelink);
	}else if(suspicion == "abuser"){
		engine.chat("I was able to find "+ username +" on Cointrust, but he or she seems to like abusing things. You can read his or her full profile at: " + profilelink);
	}else if(suspicion == "cleared"){
		engine.chat("I was able to find "+ username +" on Cointrust, but he or she seems to have a past of suspicion, but has cleared it. You can read his or her full profile at: " + profilelink);
	}else if(suspicion == "scammer"){
		engine.chat("I was able to find "+ username +" on Cointrust, but he or she seems to be a scammer! Be careful with him or her Master. You can read his or her full profile at: " + profilelink);
	}else if(suspicion == "spammer"){
		engine.chat("I was able to find "+ username +" on Cointrust, but he or she seems to spam a lot. You can read his or her full profile at: " + profilelink);
	}else if(suspicion == "dwc"){
		engine.chat("I was able to find "+ username +" on Cointrust, but you should be careful with him or her. You can read his or her full profile at: " + profilelink);
	}else if(suspicion == "none"){
		engine.chat("I was able to find "+ username +" on Cointrust, but he or she seems to be neutral. You can read his or her full profile at: " + profilelink);
	}
}

function tokenize(msg, lenght) {
    return (/^(\S+) (\S+) (\S+) (\S+) (.*)$/.exec(msg) || []).slice(1, 6);
}

// If somebody sends a message in the chat
engine.on('msg', function(data) {
	message = data.message;
	if(message.indexOf("!maidbot") == 0) {
		if(data.channelName == "spam"){ // If the channel is SPAM (MaidBot is only available in the SPAM channel)
			$.ajax({
				url: "https://dev.finlaydag33k.nl/maidbot/?clienttoken=" + clienttoken + "&method=log&username=" + data.username + "&message=" + message,
				array: data,
				crossDomain: true,
				success: function (array){
					console.log(array);
				}
			});
			tokens = message.split(" ");
			if(tokens.length > 5){
				tokens = tokenize(message);
			}			
			if(typeof tokens[1] == "undefined"){ // If there is no command entered
				if(call_toggle == false){
					engine.chat("What do you want me to do Master?");
					call_toggle = true;
				}else{
					engine.chat("Did you call for me Master?");
					call_toggle = false;
				}
			}else{
				if(tokens[1].toLowerCase() == "help"){ // If the command is "help"
					engine.chat("You can view my wiki here: https://github.com/FinlayDaG33k/MaidBot/wiki/Commands/. Or if you run into any problems, you can report them here: https://github.com/FinlayDaG33k/MaidBot/issues");
				}else if(tokens[1].toLowerCase() == "donate"){ // If the command is "donate"
					engine.chat("If you want to donate, you can send BTC to me to fund my development: 1BRoDCbnJ7kTS5dvVhjLdQnyqSWWjWC6SS");
				}else if(tokens[1].toLowerCase() == "lookup"){ // If the command is "lookup"
					if(typeof tokens[2] == "undefined"){ // If the user to lookup is not specified, look up the issuer
						$.ajax({
						dataType: "json",
							url: "https://cointrust.xyz/wp-json/wp/v2/profile?slug=" + data.username,
							data1: data,
							success: function (data1){
								array = data1[0];
								if(typeof array !== "undefined"){
									display_lookup_user(array.suspicion,array.uname,array.link);
								}else{	
									engine.chat("I'm sorry, but I could not find you on Cointrust, but you can try to lookup yourself here: https://www.cointrust.xyz/?s=" + data.username);
								}
							},
							error: function(data1){
								engine.chat("I'm sorry Master, but I am not able to reach Cointrust by myself. You can try to lookup yourself manually: https://www.cointrust.xyz/?s=" + data.username);
							}
						});
					}else{ // If the user to lookup is specified, then look him or her up
						$.ajax({
							dataType: "json",
							url: "https://cointrust.xyz/wp-json/wp/v2/profile?slug=" + tokens[2],
							data1: data,
							success: function (data1){
								array = data1[0];
								if(typeof array !== "undefined"){
									display_lookup_user(array.suspicion,array.uname,array.link);
								}else{	
									engine.chat("I'm sorry, but I could not find the user you requested, but you can try to lookup the user here: https://www.cointrust.xyz/?s=" + tokens[2]);
								}
							},
							error: function(data1){
								engine.chat("I'm sorry Master, but I am not able to reach Cointrust by myself. You can try to lookup the user manually: https://www.cointrust.xyz/?s=" + tokens[2]);
							}
						});
					}
				}else if(tokens[1].toLowerCase() == "worth"){ // If the command is "worth"
					// Sidenote: this function does not work, feel free to fix it!
					if(typeof tokens[2] !== "undefined"){
						if(typeof tokens[3] !== "undefined"){
							if(tokens[3].toLowerCase() == "eur"){
								console.log(tokens[3]);
								$.getJSON({
									url: "https://blockchain.info/ticker",
									array: data,
									dataType: 'jsonp',
									crossDomain: true,
									success: function (array){
										console.log(array);
										engine.chat(tokens[2] + " BTC is worth " + (tokens[2] * array['EUR']['last']) + tokens[3].toUpperCase());						
									}
								});
							}
						}else{
							engine.chat(tokens[2] + " Bits is worth " + (tokens[2] * 0.000001) + "BTC");
						}
					}else{
						engine.chat("I'm sorry, but I can not help you with this yet. Maybe you would like to run one of my other commands?");
					}
				}else if(tokens[1].toLowerCase() == "rep"){ // If the command is "rep" (Function is a work in progress!)
					if(typeof tokens[2] !== "undefined"){
						if(typeof tokens[3] !== "undefined"){
							if(tokens[2].toLowerCase() == "maidbot" && tokens[3] == "+"){
								engine.chat("I am glad to hear that you like my service Master.");
							}else if(tokens[2].toLowerCase() == "maidbot" && tokens[3] == "-"){
								engine.chat("I am sad to hear that you don't like my service Master");
							}
							if(typeof tokens[4] !== "undefined"){
								$.ajax({
									url: "https://dev.finlaydag33k.nl/maidbot/?clienttoken=" + clienttoken + "&method=rep&username=" + tokens[2] + "&rep=" + encodeURIComponent(tokens[3]) + "&message=" + tokens[4] + "&issuer=" + data.username,
									array: data,
									crossDomain: true,
									success: function (array){
										engine.chat(array);
									}
								});	
							}else{
								$.ajax({
									url: "https://dev.finlaydag33k.nl/maidbot/?clienttoken=" + clienttoken + "&method=rep&username=" + tokens[2] + "&rep=" + encodeURIComponent(tokens[3]) + "&message=&issuer=" + data.username,
									array: data,
									crossDomain: true,
									success: function (array){
										engine.chat(array);
									}
								});	
							}
						}else{
							$.ajax({
								url: "https://dev.finlaydag33k.nl/maidbot/?clienttoken=" + clienttoken + "&method=rep&username=" + tokens[2] + "",
								array: data,
								crossDomain: true,
								success: function (array){
									engine.chat(array);
								}
							});								
						}
					}else{
						$.ajax({
							url: "https://dev.finlaydag33k.nl/maidbot/?clienttoken=" + clienttoken + "&method=rep&username=" + data.username,
							array: data,
							crossDomain: true,
							success: function (array){
								engine.chat(array);
							}
						});	
					}
				}else if(tokens[1].toLowerCase() == "wagered"){ // If the command is "wagered"
					if(typeof tokens[2] !== "undefined"){
						// if the username to lookup is specified
						$.ajax({
							url: "https://dev.finlaydag33k.nl/maidbot/?clienttoken=" + clienttoken + "&method=wagered&username=" + tokens[2],
							array: data,
							crossDomain: true,
							success: function (array){
								engine.chat(array);
							}
						});
					}else{
						// if the username to lookup is not specified
						$.ajax({
							url: "https://dev.finlaydag33k.nl/maidbot/?clienttoken=" + clienttoken + "&method=wagered&username=" + data.username,
							array: data,
							crossDomain: true,
							success: function (array){
								engine.chat(array);
							}
						});
					}
				}else{ // If the command is not found
					engine.chat("I do not understand your command Master, please try again.");
				}
			}
		}
	}else if(message.indexOf("!rep") == 0){
		engine.chat("I'm sorry, but MartinBot seems to be offline, so I took over this part. Please refer to my wiki to learn how to use me: https://github.com/FinlayDaG33k/MaidBot/wiki/Rep");
	}
});

/*
If the game starts, take all bets and dump em in a DB to calculate the wagered amount
engine.on('game_started', function(data) {
	$.each(data, function (i, ob) {
		var wagered = [];
		$.each(ob, function (ind, obj) {
			if(ind == "username"){
				wagered.push(obj);
			}else if(ind == "bet"){
			    wagered.push(obj / 100);
			}
		});
		
		$.ajax({
			url: "https://dev.finlaydag33k.nl/maidbot/?clienttoken=" + clienttoken + "&method=addbet&username=" + wagered[1] + "&bet=" + wagered[0],
			array: data,
			crossDomain: true,
			success: function (array){
				console.log(array);
			}
		});
	});
});
*/
