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
		if(tokens[1] == "help"){
			engine.chat("YOu can view my wiki here: https://github.com/FinlayDaG33k/TrustyBot/wiki/Commands/");
		}else if(tokens[1] == "donate"){
			engine.chat("You can send donations in BTC to: 1BRoDCbnJ7kTS5dvVhjLdQnyqSWWjWC6SS");
		}else if(tokens[1] == "lookup"){
			if(typeof tokens[2] == "undefined"){
				engine.chat("Usage: !maidbot lookup <username>");
			}else{
				$.ajax({
					dataType: "json",
					url: "https://cointrust.pw/wp-json/wp/v2/profile?slug=" + tokens[2],
					data1: data,
					success: function (data1){
						console.log(data1);
						array = data1[0];
						if(typeof array !== "undefined"){
							if(array.suspicion == "trustworthy"){
								engine.chat("I found "+ array.uname +" on Cointrust, he or she seems to be quite Trustworthy. You can read his or her full profile at: " + array.link);
							}else if(array.suspicion == "delayed_loan"){
								engine.chat("I found "+ array.uname +" on Cointrust, he or she seems to be pay his loans quite late. You can read his or her full profile at: " + array.link);
							}else if(array.suspicion == "beggar"){
								engine.chat("I found "+ array.uname +" on Cointrust, he or she seems to beg a lot. You can read his or her full profile at: " + array.link);
							}else if(array.suspicion == "abuser"){
								engine.chat("I found "+ array.uname +" on Cointrust, he or she seems to like abusing things. You can read his or her full profile at: " + array.link);
							}else if(array.suspicion == "cleared"){
								engine.chat("I found "+ array.uname +" on Cointrust, he or she seems to have a past of suspicion, but has cleared it. You can read his or her full profile at: " + array.link);
							}else if(array.suspicion == "scammer"){
								engine.chat("I found "+ array.uname +" on Cointrust, he or she seems to be a scammer! Please do not trust him Master! You can read his or her full profile at: " + array.link);
							}else if(array.suspicion == "spammer"){
								engine.chat("I found "+ array.uname +" on Cointrust, he or she seems to spam a lot. You can read his or her full profile at: " + array.link);
							}
						}else{	
							engine.chat("I could not find the user you requested, you can try to register it on cointrust.pw");
						}
					}
				});
			}
		}else{
			if(typeof tokens[1] == "undefined"){
				engine.chat("What do you want me to do Master?");
			}else{
				engine.chat("I do not understand your command Master, please try again.");
			}
			
		}
	}else if(message.indexOf("!rep") == 0) {
		tokens = message.split(" ");
		if(tokens[1].toLowerCase == "maidbot" && tokens[2] == "+"){
			engine.chat("I am glad that you like my service Master.");
		}
	}
});
