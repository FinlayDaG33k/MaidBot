module.exports = {
    name: "raffle",
    exec: function(data){
        if(typeof data === "undefined") return;
        var username = data.username,
            channelName = data.channelName,
            parameters = data.parameters;
        
        if(parameters.length === 0){
            require("../bot.js").maidbot.webClient.doSay("[Raffle] Current pot: "+require("../bot.js").raffle.pot()+" Bits | Ticket Price: "+require("../bot.js").raffle.price+" Bits | (Type '!raffle help' for more details)", channelName);
        }else if(parameters.length === 1){
            if(parameters[0] == "pot"){
                require("../bot.js").maidbot.webClient.doSay("[Raffle] Current Pot: "+require("../bot.js").raffle.pot()+" Bits (1.5% fees)", channelName);
            }else if(parameters[0] == "help"){
                require("../bot.js").maidbot.webClient.doSay("[Raffle] This is a raffle. Means every one put some bits in the pot and a winner is sorted randomly once every day at Midnight (UTC). The winner wins the whole pot less some fees (1.5%) | Options: [help|pot|buy|last|next|mytickets] (Example: !raffle mytickets)", channelName);
            }else if(parameters[0] == "lastwinner" || parameters[0] == "last"){
                require("../bot.js").maidbot.webClient.doSay("[Raffle] Last pot winner: "+require("../bot.js").raffle.games.lastWinner+" ("+require("../bot.js").raffle.games.lastPot+" Bits)", channelName);
            }else if(parameters[0] == "howto" || parameters[0] == "how" || parameters[0] == "buy"){
                require("../bot.js").maidbot.webClient.doSay("[Raffle] To buy tickets, send bits to this account: 'DexonBot' (using https://www.bustabit.com/transfer)", channelName);
            }else if(parameters[0] == "next"){
                require("../bot.js").maidbot.webClient.doSay("[Raffle] Next draw in "+secUntilMidnight()+" seconds", channelName);
            }else if(parameters[0] == "mytickets" || parameters[0] == "tickets"){
                var amount = 0;
				for(var i=0;i<require("../bot.js").raffle.tickets.length; i++){
					if(require("../bot.js").raffle.tickets[i].username == username){
						amount++;
					}
				}
                require("../bot.js").maidbot.webClient.doSay("[Raffle] @"+username+" currently has "+amount+" tickets!", channelName);
            }else if(parameters[0] == "forcedraw" && username == "Dexon"){
                require("../bot.js").lotto.mustDraw = true;
            }else{
                require("../bot.js").maidbot.webClient.doSay("[Raffle] Unknow command. Type '!raffle help' for more details", channelName);
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