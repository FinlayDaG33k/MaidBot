console.log("MaidBot v2016.07.17.14 Initialized");


// Check if jQuery is in the page, and if not, inject it in the page
if(typeof jQuery === "undefined"){
	// Yes, you really need jQuery for this script to work
	var script = document.createElement('script'); 
	script.src = 'https://code.jquery.com/jquery-3.0.0.min.js'; // the URL to the jQuery library
	document.documentElement.firstChild.appendChild(script) // now append the script into HEAD, it will fetch and be executed
}


engine.on('msg', function(data) {
	message = data.message;
	if(message.indexOf("!maidbot") == 0) {
		tokens = message.split(" ");
		if(typeof tokens[1] == "undefined"){
			engine.chat("What do you want me to do Master?");
		}else{
			if(tokens[1].toLowerCase() == "help"){
				engine.chat("You can view my wiki here: https://github.com/FinlayDaG33k/TrustyBot/wiki/Commands/");
			}else if(tokens[1].toLowerCase() == "donate"){
				engine.chat("You can send donations in BTC to: 1BRoDCbnJ7kTS5dvVhjLdQnyqSWWjWC6SS");
			}else if(tokens[1].toLowerCase() == "lookup"){
				if(typeof tokens[2] == "undefined"){
					$.ajax({
						dataType: "json",
						url: "https://cointrust.pw/wp-json/wp/v2/profile?slug=" + data.username,
						data1: data,
						success: function (data1){
							array = data1[0];
							if(typeof array !== "undefined"){
								if(array.suspicion == "trustworthy"){
									engine.chat("I was able to find "+ array.uname +" on Cointrust, and he or she seems to be quite Trustworthy. You can read his or her full profile at: " + array.link);
								}else if(array.suspicion == "delayed_loan"){
									engine.chat("I was able to find "+ array.uname +" on Cointrust, but he or she seems to be pay his loans quite late. You can read his or her full profile at: " + array.link);
								}else if(array.suspicion == "beggar"){
									engine.chat("I was able to find "+ array.uname +" on Cointrust, but he or she seems to beg a lot. You can read his or her full profile at: " + array.link);
								}else if(array.suspicion == "abuser"){
									engine.chat("I was able to find "+ array.uname +" on Cointrust, but he or she seems to like abusing things. You can read his or her full profile at: " + array.link);
								}else if(array.suspicion == "cleared"){
									engine.chat("I was able to find "+ array.uname +" on Cointrust, but he or she seems to have a past of suspicion, but has cleared it. You can read his or her full profile at: " + array.link);
								}else if(array.suspicion == "scammer"){
									engine.chat("I was able to find "+ array.uname +" on Cointrust, but he or she seems to be a scammer! Please do not trust him Master! You can read his or her full profile at: " + array.link);
								}else if(array.suspicion == "spammer"){
									engine.chat("I was able to find "+ array.uname +" on Cointrust, but he or she seems to spam a lot. You can read his or her full profile at: " + array.link);
								}else if(array.suspicion == "none"){
									engine.chat("I was able to find "+ array.uname +" on Cointrust, but he or she seems to be neutral. You can read his or her full profile at: " + array.link);
								}
							}else{	
								engine.chat("I'm sorry, but I could not find the user you requested, but you can try to lookup the user here: https://www.cointrust.pw/?s=" + tokens[2]);
							}
						}
					});
				}else{
					$.ajax({
						dataType: "json",
						url: "https://cointrust.pw/wp-json/wp/v2/profile?slug=" + tokens[2],
						data1: data,
						success: function (data1){
							array = data1[0];
							if(typeof array !== "undefined"){
								if(array.suspicion == "trustworthy"){
									engine.chat("I was able to find "+ array.uname +" on Cointrust, and he or she seems to be quite Trustworthy. You can read his or her full profile at: " + array.link);
								}else if(array.suspicion == "delayed_loan"){
									engine.chat("I was able to find "+ array.uname +" on Cointrust, but he or she seems to be pay his loans quite late. You can read his or her full profile at: " + array.link);
								}else if(array.suspicion == "beggar"){
									engine.chat("I was able to find "+ array.uname +" on Cointrust, but he or she seems to beg a lot. You can read his or her full profile at: " + array.link);
								}else if(array.suspicion == "abuser"){
									engine.chat("I was able to find "+ array.uname +" on Cointrust, but he or she seems to like abusing things. You can read his or her full profile at: " + array.link);
								}else if(array.suspicion == "cleared"){
									engine.chat("I was able to find "+ array.uname +" on Cointrust, but he or she seems to have a past of suspicion, but has cleared it. You can read his or her full profile at: " + array.link);
								}else if(array.suspicion == "scammer"){
									engine.chat("I was able to find "+ array.uname +" on Cointrust, but he or she seems to be a scammer! Please do not trust him Master! You can read his or her full profile at: " + array.link);
								}else if(array.suspicion == "spammer"){
									engine.chat("I was able to find "+ array.uname +" on Cointrust, but he or she seems to spam a lot. You can read his or her full profile at: " + array.link);
								}else if(array.suspicion == "none"){
									engine.chat("I was able to find "+ array.uname +" on Cointrust, but he or she seems to be neutral. You can read his or her full profile at: " + array.link);
								}
							}else{	
								engine.chat("I'm sorry, but I could not find the user you requested, but you can try to lookup the user here: https://www.cointrust.pw/?s=" + tokens[2]);
							}
						}
					});
				}
			}else if(tokens[1].toLowerCase() == "worth"){
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
			}else{
				engine.chat("I do not understand your command Master, please try again.");
			}
		}
	}else if(message.indexOf("!rep") == 0) {
		tokens = message.split(" ");
		if(tokens[1].toLowerCase() == "maidbot" && tokens[2] == "+"){
			engine.chat("I am glad to hear that you like my service Master.");
		}else if(tokens[1].toLowerCase() == "maidbot" && tokens[2] == "-"){
			engine.chat("I am sad to hear that you don't like my service Master");
		}
	}
});
