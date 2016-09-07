module.exports = {
    name: "raffle",
    exec: function(data){
        if(typeof data === "undefined") return;
        var username = data.username,
            channelName = data.channelName,
            parameters = data.parameters;
        
        if(parameters.length === 0){
            require("../bot.js").maidbot.webClient.doSay("The current pot contains "+require("../bot.js").raffle.pot()+" Bits and a ticket costs "+require("../bot.js").raffle.price+" Bits | (Type '!maidbot raffle help' for more details)", channelName);
        }else if(parameters.length === 1){
            if(parameters[0] == "pot"){
                require("../bot.js").maidbot.webClient.doSay("The current pot contains "+require("../bot.js").raffle.pot()+" Bits (1.5% fees)", channelName);
            }else if(parameters[0] == "help"){
                require("../bot.js").maidbot.webClient.doSay("This is a raffle. This means players put some bits in the pot and a winner is selected randomly once every day at Midnight (UTC). The winner wins the whole pot minus some fees (1.5%) | Options: [help|pot|buy|last|next|mytickets] (Example: !maidbot raffle mytickets)", channelName);
            }else if(parameters[0] == "lastwinner" || parameters[0] == "last"){
                require("../bot.js").maidbot.webClient.doSay("Last times pot winner was "+require("../bot.js").raffle.games.lastWinner+" who won "+require("../bot.js").raffle.games.lastPot+" Bits!", channelName);
            }else if(parameters[0] == "howto" || parameters[0] == "how" || parameters[0] == "buy"){
                require("../bot.js").maidbot.webClient.doSay("If you want to buy tickets, please send 10 Bits per ticket to this account 'MaidBot' (using https://www.bustabit.com/transfer)", channelName);
            }else if(parameters[0] == "next"){
                require("../bot.js").maidbot.webClient.doSay("I will draw the next lucky winner in "+secUntilMidnight()+" seconds!", channelName);
            }else if(parameters[0] == "mytickets" || parameters[0] == "tickets"){
                var amount = 0;
				for(var i=0;i<require("../bot.js").raffle.tickets.length; i++){
					if(require("../bot.js").raffle.tickets[i].username == username){
						amount++;
					}
				}
                require("../bot.js").maidbot.webClient.doSay(" @"+username+" currently has "+amount+" tickets!", channelName);
            }else if(parameters[0] == "forcedraw" && username == "finlaydag33k"){
                require("../bot.js").lotto.mustDraw = true;
            }else{
                require("../bot.js").maidbot.webClient.doSay("I do not fully understand your command Master, Type '!maidbot raffle help' if you wish more details about the raffle.", channelName);
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