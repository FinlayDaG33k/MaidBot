var sensitive = require('../sensitivedata/data.js');
module.exports = {
    GAMESERVER: "https://gs.bustabit.com",
    WEBSERVER: "https://www.bustabit.com",
	MAIDBOTSERVER: "http://localhost",
    SESSION: process.env.BOT_SESSION || sensitive.BOT_SESSION || "",
    PASSWORD: process.env.BOT_PASSWORD || sensitive.BOT_PASSWORD || "",
    USERNAME: process.env.BOT_USERNAME || sensitive.BOT_USERNAME || "",
	CLIENT_TOKEN: sensitive.BOT_CLIENT_TOKEN || "",
	BOT_DEBUG: "false"
};
/*
module.exports = {
    GAMESERVER: "https://gs.bustabit.com",
    WEBSERVER: "https://www.bustabit.com",
    SESSION: process.env.BOT_SESSION || sensitive.BOT_SESSION || "",
    PASSWORD: process.env.BOT_PASSWORD || sensitive.BOT_PASSWORD || ""
};
*/
