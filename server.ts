const logger = (require("log4js")).getLogger("Backend");
const stats = require('./stats');
const conf = require('./config');
const utils = require('./utils');
const express = require('express');

logger.level = "debug";


function renderError(res: any, dark: boolean = false): void {
    return res.render('error', {dark: dark});
}

async function renderUser(res: any, username: string, dark: boolean = false): Promise<void> {
    if (! await utils.isAuthenticated(username)) {
        renderError(res, dark);
    } else {
        const resp = await stats.analyseUser(username);
        resp['dark'] = dark;
        res.render('user', resp);
    }
}

async function renderRepo(res: any, username: string, repo: string, dark: boolean = false): Promise<void> {
    if (! await utils.isExistRepo(username, repo)) {
        renderError(res, dark);
    } else {
        const resp = await stats.analyseRepo(username, repo);
        resp['dark'] = dark;
        res.render('repo', resp);
    }
}
export function createServer(): void {
    const app = express();

    app.set('views', __dirname + '/views');
    app.set("view engine", "ejs");

    app.use(express.static('public'));


    app.get('/user/:user/', async function (req: any, res: any) {
        res.type('svg');

        const dark: boolean = req.query['theme'] === 'dark',
            username: string = req.params['user'];

        try { await renderUser(res, username, dark) } catch { renderError(res, dark) }
    });

    app.get('/repo/:user/:repo/', async function (req: any, res: any) {
        res.type('svg');

        const dark: boolean = req.query['theme'] === 'dark',
            username = req.params['user'],
            repo = req.params['repo'];

        try { await renderRepo(res, username, repo, dark) } catch { renderError(res, dark) }
    });

    app.listen(conf.port, () =>
        logger.info(`Starting deployment server at http://127.0.0.1:${conf.port}/.`));
}
