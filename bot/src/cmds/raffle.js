module.exports = {
    name: "raffle",
    exec: function(data){
        if(typeof data === "undefined") return;
        var username = data.username,
            channelName = data.channelName,
            parameters = data.parameters;
        
        if(parameters.length === 1){
            require("../bot.js").maidbot.webClient.doSay("The current pot contains "+require("../bot.js").raffle.pot()+" Bits and a ticket costs "+require("../bot.js").raffle.price+" Bits | (Type '!maidbot raffle help' for more details)", channelName);
        }else if(parameters.length === 2){
            if(parameters[1] == "pot"){
                require("../bot.js").maidbot.webClient.doSay("The current pot contains "+require("../bot.js").raffle.pot()+" Bits (1.5% fees)", channelName);
            }else if(parameters[1] == "help"){
                require("../bot.js").maidbot.webClient.doSay("This is a raffle. This means players put some bits in the pot and a winner is selected randomly once every day at Midnight (UTC) when atleast 2000 tickets have been sold. The winner wins the whole pot minus some fees (1.5%) | Options: [help|pot|buy|last|next|left|mytickets] (Example: !maidbot raffle mytickets)", channelName);
            }else if(parameters[1] == "lastwinner" || parameters[1] == "last"){
                require("../bot.js").maidbot.webClient.doSay("Last times pot winner was "+require("../bot.js").raffle.games.lastWinner+" who won "+require("../bot.js").raffle.games.lastPot+" Bits!", channelName);
            }else if(parameters[1] == "howto" || parameters[1] == "how" || parameters[1] == "buy"){
                require("../bot.js").maidbot.webClient.doSay("If you want to buy tickets, please send 100 Bits per ticket to this account 'MaidBot' (using https://www.bustabit.com/transfer)", channelName);
            }else if(parameters[1] == "next"){
                require("../bot.js").maidbot.webClient.doSay("I will draw the next lucky winner in "+secUntilMidnight()+" seconds if we sell " + (2000 - require("../bot.js").raffle.tickets.length) + " more tickets!", channelName);
			}else if(parameters[1] == "left"){
				require("../bot.js").maidbot.webClient.doSay("There need to be " + (2000 - require("../bot.js").raffle.tickets.length) + " more tickets sold before the raffle will start!", channelName);
            }else if(parameters[1] == "mytickets" || parameters[1] == "tickets"){
                var amount = 0;
				for(var i=0;i<require("../bot.js").raffle.tickets.length; i++){
					if(require("../bot.js").raffle.tickets[i].username == username){
						amount++;
					}
				}
                require("../bot.js").maidbot.webClient.doSay(" @"+username+" currently has "+amount+" tickets! ("+amount+ "/" + require("../bot.js").raffle.tickets.length + ")", channelName);
            }else if(parameters[1] == "forcedraw" && username.toLowerCase() == "finlaydag33k"){
				// do not use this command... seriously...
				
                require("../bot.js").raffle.mustRoll = true;
            }else{
                require("../bot.js").maidbot.webClient.doSay("I see you are interested in the Raffle Master? Please type '!maidbot raffle help' if you wish for more details about the Raffle.", channelName);
            }
        }
    }
};

function secUntilMidnight() {
    var now = new Date();
    var night = new Date(
        now.getFullYear(),
        now.getMonth(),
        now.getDate() + 1, // the next day, ...
        0, 0, 0 // ...at 00:00:00 hours
    );
    return Math.round((night.getTime() - now.getTime())/1000);
}