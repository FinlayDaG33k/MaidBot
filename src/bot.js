/* MODULES
-----------------*/
var request = require("request");

/* EXTRA FUNCTIONS
-----------------*/
function tokenizer(msg) {
    return (/^(\S+) (\S+) (\S+) (\S+) (.*)$/.exec(msg) || []).slice(2, 6);
}

/* TEMP. VARS
-----------------*/
module.exports.spamList = [];
module.exports.lotto;
module.exports.raffle;

/* EVENTS
-----------------*/
require("./events/raffle.js").exec({initialize: true});

/* BOT
-----------------*/
function DexonBot(){
	console.log("Starting MaidBot v"+process.env.npm_package_version);
	
    var self = this
    self.Config = require('./Config');
	
    //console.log(JSON.stringify(self.Config)); // uncomment to see your configs
    var GameClient = require('./GameClient'),
        WebClient = require('./WebClient');
    
	var call_toggle = false;
	
    // Set bot's session cookie for connections
    require('socket.io-client-cookie').setCookies('id=' + self.Config.SESSION);
    
    // Connect to the game server.
    self.gameClient = new GameClient(self.Config);
    
    // Player cashed out
    self.gameClient.on('game_crash', function(data){
        //require("./events/lotto.js").exec(data);
    });
	
    // Connect to the web server.
    self.webClient = new WebClient(self.Config);
	
    // New message in chat.
    self.webClient.on('msg', function(msg) {
        if(msg.message != null && msg.message != "" && msg.message.indexOf("!maidbot") == 0){ // User calling a bot command
            request({
				uri: "https://dev.finlaydag33k.nl/maidbot/?clienttoken=" + self.Config.CLIENT_TOKEN + "&method=log&username=" + msg.username + "&message=" + msg.message,
				method: "GET",
				timeout: 5000,
				followRedirect: false
			});
			
			try{
				var cmd = msg.message.split(" ")[1].replace("!maidbot",""),
					username = msg.username,
					channelName = msg.channelName,
					parameters = [];
			}catch(e){
				var username = msg.username,
				channelName = msg.channelName;
				require("./cmds/no_command.js").exec(channelName, call_toggle);
				if(call_toggle == false){
					call_toggle = true;
				}else{
					call_toggle = false;
				}
			}
			console.log(username + " issued command: \"" + cmd + "\" in Channel \"" + channelName + "\"");
            for(var i=1; i<msg.message.split(" ").length; i++){
                var parameter = msg.message.split(" ")[i];
                parameters.push(parameter);
            }
			if(parameters.length > 4){
				parameters = tokenizer(msg.message);
			}

			//console.log(parameters);
            self.onCmd(cmd, {
                username: username,
                channelName: channelName,
                msg: msg,
                parameters: parameters
            });
        }
        
        require("./events/spamCheck.js").exec(msg);
    });
    
    self.onCmd = function(cmd, data){
        try{
            switch(cmd.toLowerCase()) {
                case "lookup":
                    require("./cmds/lookup.js").exec(data);
                    break;
				case "help":
					require("./cmds/help.js").exec(data);
					break;
				case "donate":
					require("./cmds/donate.js").exec(data);
					break;
				case "rep":
					console.log("hi");
					require("./cmds/rep.js").exec(data,self.Config.CLIENT_TOKEN);
					break;
				case "raffle":
                    require("./cmds/raffle.js").exec(data);
                    break;
				default:
					require("./cmds/unknown.js").exec(data);
					break;
            }
        }catch(e){
            console.error("[onCMD Error] ", e.message);
        }
    }
}
module.exports.maidbot = new DexonBot();

/* UNCAUGHT EXCEPTIONS
-----------------*/
process.on('uncaughtException', function(err) {
    console.error((new Date).toUTCString() + ' uncaughtException:', err.message);
    console.error(err.stack);
    process.exit(1);
});
