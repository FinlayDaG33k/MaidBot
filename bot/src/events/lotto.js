module.exports = {
    name: "lotto",
    exec: function(data){
        if(typeof data === "undefined") return;
        
        if (typeof data.initialize !== "undefined" && data.initialize) {
            require("../bot.js").lotto = {
                basePot: 100,
                pot: 100,
                cashout: getRandomInt(1, 15),
                users: [],
                canDraw: true,
                mustDraw: false,
                winner: false,
                timer: setInterval(function(){
                    if(require("../bot.js").lotto.users.length>0 && ((new Date()).getUTCMinutes() === 0 && require("../bot.js").lotto.canDraw || require("../bot.js").lotto.mustDraw)){
                        require("../bot.js").lotto.canDraw = false;
                        
                        if(!require("../bot.js").lotto.winner){
                            var rand = getRandomInt(0, require("../bot.js").lotto.users.length-1);
                            var username = require("../bot.js").lotto.users[rand];
                            console.log("[Lotto] User "+username+" (#"+rand+") won the lotto! Waiting for his claim...");
                            require("../bot.js").maidbot.webClient.doSay("[Lotto] @"+username+" won a prize! Type '!claim' in chat to claim your prize! (You have 120 seconds)", 'spam');
                            
                            require("../bot.js").lotto.winner = {
                                username: username,
                                canClaim: true,
                                timeout: setTimeout(function() {
                                    require("../bot.js").lotto.winner.canClaim = false;
                                    require("../bot.js").maidbot.webClient.doSay("[Lotto] @"+require("../bot.js").lotto.winner.username+" didn't claim in time and missed the lotto.", 'spam');
                                    require("../bot.js").lotto.users = [];
                                    require("../bot.js").lotto.cashout = getRandomInt(1, 15);
                                    require("../bot.js").lotto.pot += 10;
                                    require("../bot.js").maidbot.webClient.doSay("[Lotto] To participate to the next lotto, you must now cashout over: "+require("../bot.js").lotto.cashout+".00x  (Type '!lotto' in chat for more details)", 'spam');
                                    console.log("[Lotto] "+require("../bot.js").lotto.winner.username+" missed the lotto -> new pot: "+require("../bot.js").lotto.pot+" Bits | new Cashout: "+require("../bot.js").lotto.cashout+"x");
                                    clearTimeout(require("../bot.js").lotto.winner.timeout);
                                    require("../bot.js").lotto.winner = false;
                                }, 120000)
                            };
                        }
                        
                    }
                    
                    if((new Date()).getUTCMinutes() > 1){
                        require("../bot.js").lotto.canDraw = true;
                    }
                }, 1000)
            };
            console.log("[Lotto] Starting the lotto -> Pot: "+require("../bot.js").lotto.pot+" Bits | Cashout: "+require("../bot.js").lotto.cashout+".00x");
        }else{
            if(typeof data.cashouts !== "undefined"){
                var cashouts = data.cashouts;
                
                _.forEach(cashouts, function(stopped_at, user) {
                    var username = user,
                        cashout = stopped_at/100;
                    
                    if(cashout > require("../bot.js").lotto.cashout){
                        if(require("../bot.js").lotto.users.indexOf(username) <= -1){
                            require("../bot.js").lotto.users.push(username);
                            console.log("[Lotto] Users in lotto: "+require("../bot.js").lotto.users.length);
                        }
                    }
                });
            }
        }
    }
};

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}