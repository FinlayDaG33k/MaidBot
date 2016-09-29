'use strict';

var EventEmitter =  require('events').EventEmitter,
    inherits     =  require('util').inherits,
    co           =  require('co'),
    request      =  require('co-request');

module.exports = GameClient;

function GameClient(config) {
    EventEmitter.call(this);

    this.config = config;

    this.socket = require('socket.io-client')(config.GAMESERVER);
    this.socket.on('error', this.onError.bind(this));
    this.socket.on('err', this.onErr.bind(this));
    this.socket.on('connect', this.onConnect.bind(this));
    this.socket.on('disconnect', this.onDisconnect.bind(this));
    this.socket.on('game_crash', this.onGameCrash.bind(this));

    //this.socket.on('join', this.onJoin.bind(this));
}

inherits(GameClient, EventEmitter);

GameClient.prototype.onError = function(err) {
    console.error('onError: ', err);
};

GameClient.prototype.onErr = function(err) {
    console.error('onErr: ', err);
};

GameClient.prototype.onConnect = function(data) {
    console.log("Connected to GameServer");

    var self = this,
        ott = getOtt(self.config);

    var info = ott ? { ott: "" + ott } : {};
    self.socket.emit('join', info, function(err, data) {
        if (err)
            console.error('[ERROR] onConnect:', err);
        else
            self.onJoin(data);
    });
};

GameClient.prototype.onDisconnect = function(data) {
    console.log('Disconnected from Game server |', data, '|', typeof data);
    this.emit('disconnect');
};

GameClient.prototype.onJoin = function(data) {
    this.emit('join', data);
};

GameClient.prototype.onGameCrash = function(data) {
    this.emit('game_crash', data);
};

// Get a one time token from the server to join the game.
function getOtt(config) {
    if (!config.SESSION) return null;

    var cookie = request.cookie('id=' + config.SESSION),
        url    = config.WEBSERVER + '/ott',
        jar    = request.jar();

    jar.setCookie(cookie, url);

    var res = request.post({url:url, jar:jar});
    return res.body;
}