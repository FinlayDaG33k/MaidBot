module.exports = {
    name: "spamCheck",
    exec: function(data){
        if(typeof data === "undefined") return;
        
        var msg = data,
            spamList = require("../bot.js").spamList;
        
        var excludes = ["DexonBot", "Shiba"];
        if(excludes.indexOf(msg.username)>-1) return;
        
        if(spamList.indexOf(msg.username)<=-1){
            spamList.push(msg.username);
            spamList[msg.username] = {},
            spamList[msg.username].muted = false,
            spamList[msg.username].entries = [];
        }else if(typeof spamList[msg.username].muted === "undefined"){
            spamList[msg.username] = {},
            spamList[msg.username].muted = false,
            spamList[msg.username].entries = [];
        }
        
        if(!spamList[msg.username].muted){
            spamList[msg.username].entries.push({
                time: Date.parse(msg.date),
                message: msg.message,
                timeout: setTimeout(function() {
                    spamList[msg.username].entries.splice(0, 1);
                }, 6000)
            });
        }
        
        var spamStreak = 0;
        for(var i=0; i<spamList[msg.username].entries.length; i++){
            if(!spamList[msg.username].muted && spamList[msg.username].entries.length > 2){
                if(i < spamList[msg.username].entries.length-1 && spamList[msg.username].entries[i].message == spamList[msg.username].entries[i+1].message){
                    if(spamList[msg.username].entries[i+1].time - spamList[msg.username].entries[i].time <= 5000){
                        spamStreak++;
                        if(spamStreak == 3){
                            spamList[msg.username].muted = true;
                            require("../bot.js").maidbot.webClient.doSay("/mute "+msg.username+" 3m", msg.channelName);
                            require("../bot.js").maidbot.webClient.doSay("@"+msg.username+" don't spam please.", msg.channelName);
                            console.log("Muted "+msg.username+" for Spamming channel '"+msg.channelName+"'");
                            
                            setTimeout(function() {
                                spamList[msg.username] = {};
                            }, 60000);
                            break;
                        }
                    }
                }else if(i < spamList[msg.username].entries.length-1 && spamList[msg.username].entries[i].message != spamList[msg.username].entries[i+1].message){
                    if(spamList[msg.username].entries[i+1].time - spamList[msg.username].entries[i].time <= 500){
                        spamStreak++;
                        if(spamStreak == 3){
                            spamList[msg.username].muted = true;
                            require("../bot.js").maidbot.webClient.doSay("/mute "+msg.username+" 3m", msg.channelName);
                            require("../bot.js").maidbot.webClient.doSay("@"+msg.username+" don't spam please.", msg.channelName);
                            console.log("Muted "+msg.username+" for Spamming channel '"+msg.channelName+"'");
                            
                            setTimeout(function() {
                                spamList[msg.username] = {};
                            }, 60000);
                            break;
                        }
                    }
                }
            }else{
                break;
            }
        }
    }
}