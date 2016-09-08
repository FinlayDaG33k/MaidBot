module.exports = {
    name: "reminders",
    exec: function(data){
        if(typeof data === "undefined") return;
        
        if (typeof data.initialize !== "undefined" && data.initialize) {
            
            var reminders = [
                "May I remind you that if your BustaBit password is not unique on this site, I recommend you to change it now. Password re-use *will* lead to you getting hacked.",
				""
            ];
            
            setTimeout(function(){
                var rand = getRandomInt(0, reminders.length-1);
                doRemind(reminders[rand]);
            }, 10000);
            
            
            setInterval(function(){
                var rand = getRandomInt(0, reminders.length-1);
                doRemind(reminders[rand]);
            }, (1000*60)*70);
            
        }
        
        
    }
};

function doRemind(string){
    require("../bot.js").maidbot.webClient.doSay(string, 'english');
}

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}
