import {isExistRepo} from "./utils";

const logger = (require("log4js")).getLogger("Backend");
const stats = require('./stats');
const conf = require('./config');
const utils = require('./utils');
const app = require('express')();

logger.level = "debug";
app.set('views',__dirname + '/views');
app.set("view engine","ejs");

app.get('/', function(req: any, res: any) {
    res.render('index');
})

app.get('/user/:user/', async function (req: any, res: any) {
  res.type('svg');

  try {
    const username = req.params['user'];
    if (await utils.isAuthenticated(username)) throw new Error();
    res.render('user', await stats.getAccount(username, req.query['theme'] === 'dark'));
  } catch (e) {
    res.render('error', {dark: req.query['theme'] === 'dark'});
  }
});

app.get('/repo/:user/:repo/', async function (req: any, res: any) {
  res.type('svg');
  try {
    const username = req.params['user'], repo = req.params['repo'];

    if (await isExistRepo(username, repo)) throw new Error();
    res.render('repo', await stats.getRepository(username, repo, req.query['theme'] === 'dark'))
  } catch (e) {
    res.render('error', {dark: req.query['theme'] === 'dark'});
  }
});


app.listen(conf.port, () =>
  logger.info(`Starting deployment server at http://127.0.0.1:${conf.port}/.`));
