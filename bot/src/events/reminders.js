module.exports = {
    name: "reminders",
    exec: function(data){
        if(typeof data === "undefined") return;
        
        if (typeof data.initialize !== "undefined" && data.initialize) {
            
            var reminders = [
				"Please don't forget to buy some tickets for tonights Raffle!",
				"Don't forget to take a break from Bustabit now and then!",
				"Please do not bet bits you can't afford to lose!",
				"If you lend out bits, please be careful as there always is a chance to get scammed!",
				"Know who you're dealing with in the CrypoCommunity, use `!maidbot lookup <username>` before lending somebody bits!"
            ];
            
            setTimeout(function(){
                var rand = getRandomInt(0, reminders.length-1);
                doRemind(reminders[rand]);
            }, 10000);
            
            
            setInterval(function(){
                var rand = getRandomInt(0, reminders.length-1);
                doRemind(reminders[rand]);
            }, 600000);
            
        }
        
        
    }
};

function doRemind(string){
    require("../bot.js").maidbot.webClient.doSay(string, 'spam');
}

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
