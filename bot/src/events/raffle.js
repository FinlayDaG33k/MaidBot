module.exports = {
    name: "raffle",
    exec: function(data){
        if(typeof data === "undefined") return;
        
        if (typeof data.initialize !== "undefined" && data.initialize) {
            var jsonf = require('json-file');
            var uuid = require('node-uuid');
            var https = require("https");
            var Config = require("../Config");
            var ticketsFile = jsonf.read('db/raffle.json');
            var request = require("request");
            var lastRaffleTickets = 0;
            
            require("../bot.js").raffle = {
                minPot: 20,
        		price: 100,
        		canRoll: true,
        		mustRoll: false,
        		games: ticketsFile.data.game,
                tickets: ticketsFile.data.tickets,
                timer: setInterval(function() {
                    
                    if((secUntilMidnight() >= 0 && secUntilMidnight() <= 6 && require("../bot.js").raffle.canRoll) || require("../bot.js").raffle.mustRoll){ // lottery roll !
                        if(require("../bot.js").raffle.tickets.length > 20){
                            require("../bot.js").raffle.canRoll = false;
                            require("../bot.js").raffle.mustRoll = false;
                            var rand = getRandomInt(0, require("../bot.js").raffle.tickets.length-1);
                            var winner = require("../bot.js").raffle.tickets[rand].username;
                            
                            require("../bot.js").maidbot.webClient.doSay(" @"+winner+" just won the Raffle! | He or She won: "+require("../bot.js").raffle.pot()+" Bits!", 'spam');
                            request({
								uri: "https://maidbot.finlaydag33k.nl/report.php?clienttoken=" + Config.CLIENT_TOKEN + "&method=raffle&username=" + winner + "&pot=" + require("../bot.js").raffle.pot(),
								method: "GET",
								timeout: 5000,
								followRedirect: false
							}, 
							function(error, response, data) {
								if (!error && response.statusCode == 200) {
									console.log("Something went wrong while trying to push last winner to database");
								}else{
									console.log("Successfully updated raffle winner database!");
								}
							});
							
                            try{
                                // Build the post string from an object
                              var post_data = encodeURI("amount="+parseFloat(require("../bot.js").raffle.pot())+
                                  "&to-user="+winner+
                                  "&password="+Config.PASSWORD+
                                  "&transfer-id="+uuid.v4());
                              // An object of options to indicate where to post to
                              var post_options = {
                                  host: 'www.bustabit.com',
                                  port: '443',
                                  path: '/transfer-request',
                                  method: 'POST',
                                  headers: {
                                      'Content-Type': 'application/x-www-form-urlencoded',
                                      'Content-Length': post_data.length,
                                      'Access-Control-Allow-Credentials': true,
                                      'Cookie': "id="+Config.SESSION
                                  }
                              };
                            
                              // Set up the request
                              var post_req = https.request(post_options, function(res) {
                                  res.setEncoding('utf8');
                                  res.on('data', function (chunk) {
                                      //console.log('Response: ' + chunk);
                                  });
                              });
                            
                              // post the data
                              post_req.write(post_data);
                              post_req.end();
                            }catch(e){
                                console.log("error Lottery.timer: "+e.message);
                            }
                            
                            require("../bot.js").raffle.games = {
                                "lastWinner": winner,
                                "lastPot": String(parseFloat(require("../bot.js").raffle.pot()))
                            };
                            
                            
                            require("../bot.js").raffle.tickets = [];
                            ticketsFile.set('game', require("../bot.js").raffle.games);
                            ticketsFile.set('tickets', require("../bot.js").raffle.tickets);
                            ticketsFile.writeSync();
                        }else{ // no ticket bought :(
                            require("../bot.js").raffle.canRoll = false;
                            require("../bot.js").maidbot.webClient.doSay("There are not enough tickets bought for today's raffle. So there is no winner today. Please consider buying tickets for tomorrows draw. (" + require("../bot.js").raffle.tickets.length + "/20 tickets)", 'spam');
                        }
                    }
                    
                    if(secUntilMidnight() > 6){
                        require("../bot.js").raffle.canRoll = true;
                    }
                    
                    if(lastRaffleTickets != require("../bot.js").raffle.tickets.length){
                        lastRaffleTickets = require("../bot.js").raffle.tickets.length;
                        console.log(lastRaffleTickets+" Tickets bought for lottery");
                    }
                }, 1000) // 1 sec
            };
            
            require("../bot.js").raffle.pot = function(){ // 1.5% fees
                var pot = (0.985 * (require("../bot.js").raffle.price * require("../bot.js").raffle.tickets.length));
                pot = (pot<require("../bot.js").raffle.minPot?require("../bot.js").raffle.minPot:pot);
                return pot.toFixed(2);
            };
            
            require("../bot.js").raffle.updateTickets = function(){
                
                var response = "";
                try{
                    var options = {
                        hostname: 'www.bustabit.com',
                        port: '443',
                        path: '/transfer.json',
                        method: 'GET',
                        headers: {
                            'Access-Control-Allow-Credentials': true,
                            'Cookie': "id="+Config.SESSION
                        }
                    };
                    
                    var req = https.request(options, function(res) {
                        
                        res.on('data', function(d) {
                            response += d.toString();
                        });
                        
                        res.on('end', function() {
                            response = JSON.parse(response);
                            
                            //console.log(response[0]);
                            
                            for(var i=0;i<response.length; i++){
                                var now = new Date();
                                if( (new Date(response[i].created)).setHours(0,0,0,0) === (new Date(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(),  now.getUTCHours(), now.getUTCMinutes(), now.getUTCSeconds())).setHours(0,0,0,0) ){
                                    if(response[i].to_username != "MaidBot") return;
                                    
                                    var ticketFound = false;
                                    for(var j=0;j<require("../bot.js").raffle.tickets.length; j++){
                                        if(require("../bot.js").raffle.tickets[j].id == response[i].id){
                                            ticketFound = true;
                                            break;
                                        }
                                    }
                                    
                                    if(!ticketFound){
                                        var amount = Math.floor((response[i].amount/100)/require("../bot.js").raffle.price);
                                        for(var k=0; k<amount; k++){
                                            require("../bot.js").raffle.tickets.push({
                                                id: response[i].id,
                                                username: response[i].from_username
                                            });
                                        }
                                        
                                        console.log("[RAFFLE] "+response[i].from_username+" bought "+amount+" tickets!");
                                        
                                        ticketsFile.set('tickets', require("../bot.js").raffle.tickets);
                                        ticketsFile.writeSync();
                                    }
                                }
                            }
                        });
                    });
                    req.end();
                    
                    req.on('error', function(e) {
                        console.error('error updateTickets 2: '+e);
                    });
                }catch(e){
                    console.log('error updateTickets: '+e.message);
                }
            };
            
            require("../bot.js").raffle.updateTickets();
            
            setInterval(function() {
                if(secUntilMidnight() > 6){
                    require("../bot.js").raffle.updateTickets();
                    console.log("[Raffle] Updated tickets.");
                }
            }, 300000);
            
        }
    }
};

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

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