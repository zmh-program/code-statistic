const express = require('express');
const logger = (require("log4js")).getLogger("Backend");
const stats = require('./stats');
const conf = require('./config');
const app = express();

logger.level = "debug";
app.set('views',__dirname + '/views');
app.set("view engine","ejs");


const isAvailableUser = (username) => {
    return ( !! username ) && ( conf.requires.includes("*") || conf.requires.includes(username));
}

app.get('/', function(req, res) {
    res.render('index');
})

app.get('/user/:user/', async function (req, res) {
    const username = req.params['user'];
    if ( ! isAvailableUser(username) ) {
        res.send('permission denied');
        return;
    }
    res.type('svg');
    res.render('user', await stats.getAccount(username));
});

app.get('/repo/:user/:repo/', async function (req, res) {
    const username = req.params['user'], repo = req.params['repo'];
    if ( (! isAvailableUser(username)) || (! repo)) {
        res.send('permission denied');
        return;
    }
    res.type('svg');
    res.render('repo', await stats.getRepository(username, repo));
});


app.listen(conf.port, () =>
  logger.info(`Starting deployment server at http://127.0.0.1:${conf.port}/.`));
