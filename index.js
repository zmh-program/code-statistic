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

    res.type('svg');
    isAvailableUser(username) ?
      res.render('user', await stats.getAccount(username, req.query['theme'] === 'dark')):
      res.render('error', {dark: req.query['theme'] === 'dark'});
});

app.get('/repo/:user/:repo/', async function (req, res) {
    const username = req.params['user'], repo = req.params['repo'];

    res.type('svg');
    isAvailableUser(username) && repo.length ?
      res.render('repo', await stats.getRepository(username, repo, req.query['theme'] === 'dark'))
      : res.render('error', {dark: req.query['theme'] === 'dark'});
});


app.listen(conf.port, () =>
  logger.info(`Starting deployment server at http://127.0.0.1:${conf.port}/.`));
