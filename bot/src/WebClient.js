'use strict';

var EventEmitter =  require('events').EventEmitter,
    inherits     =  require('util').inherits;

module.exports = WebClient;
module.exports = WebClient;

function WebClient(config) {
    EventEmitter.call(this);

    this.config = config;

    this.socket = require('socket.io-client')(config.WEBSERVER);
    this.socket.on('error', this.onError.bind(this));
    this.socket.on('err', this.onErr.bind(this));
    this.socket.on('connect', this.onConnect.bind(this));
    this.socket.on('disconnect', this.onDisconnect.bind(this));
    this.socket.on('msg', this.onMsg.bind(this));

    //this.socket.on('join', this.onJoin.bind(this));
}

inherits(WebClient, EventEmitter);

WebClient.prototype.onMsg = function(msg) {
    this.emit('msg', msg);
};

WebClient.prototype.onError = function(err) {
    console.error('('+(new Date()).getTime()+') webclient onError: ', err);
};

WebClient.prototype.onErr = function(err) {
    console.error('webclient onErr: ', err);
};

WebClient.prototype.onConnect = function(data) {
    this.socket.emit('join', 'all', this.onJoin.bind(this));
};

WebClient.prototype.onJoin = function(err, data) { //{ data.username, data.moderator, data.channels }
    console.log('Connected to WebServer');

    var allChanData = {
        history: data.channels.all,
        username: data.username,
        channel: 'all'
    };

    this.emit('join', allChanData);
};

WebClient.prototype.doSay = function(line, channelName) {
    this.socket.emit('say', line, channelName, true, function(err) {
        if(err) console.error('[doSay Error] ', err);
    });
};

WebClient.prototype.onDisconnect = function(data) {
    console.log('Disconnected from Web server |', data, '|', typeof data);
    this.emit('disconnect');
};