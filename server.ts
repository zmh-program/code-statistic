import { getLogger } from "log4js";
import { isAuthenticated, isExistRepo } from "./utils";
import { analyseRepo, analyseUser } from "./stats";
import { port } from "./config";


const express = require('express');
const logger = getLogger("Backend");
logger.level = "debug";


export function createServer(): void {
    const app = express();

    app.set('views', __dirname + '/views');
    app.set("view engine", "ejs");

    app.use(express.static('public'));


    app.get('/user/:user/', async function (req: any, res: any) {
        res.type('svg');

        const dark: boolean = req.query['theme'] === 'dark',
            username: string = req.params['user'];

        try {
            if (! await isAuthenticated(username)) {
                res.render('error', { dark: dark });
            } else {
                const resp = await analyseUser(username);
                resp['dark'] = dark;
                res.render('user', resp);
            }
        } catch {
            res.render('error', {dark: dark});
        }
    });

    app.get('/api/user/:user/', async function (req: any, res: any) {
        const username: string = req.params['user'];

        try {
            if (! await isAuthenticated(username)) {
                res.send({'status': false});
            } else {
                const resp = await analyseUser(username);
                resp['status'] = true;
                res.send(resp);
            }
        } catch {
            res.send({'status': false});
        }
    })

    app.get('/repo/:user/:repo/', async function (req: any, res: any) {
        res.type('svg');

        const dark: boolean = req.query['theme'] === 'dark',
            username = req.params['user'],
            repo = req.params['repo'];

        try {
            if (! await isExistRepo(username, repo)) {
                res.render('error', {dark: dark});
            } else {
                const resp = await analyseRepo(username, repo);
                resp['dark'] = dark;
                res.render('repo', resp);
            }
        } catch {
            res.render('error', {dark: dark});
        }
    });

    app.get('/api/repo/:user/:repo/', async function (req: any, res: any) {
        const username = req.params['user'],
            repo = req.params['repo'];

        try {
            if (! await isExistRepo(username, repo)) {
                res.send({'status': false});
            } else {
                const resp = await analyseRepo(username, repo);
                resp['status'] = true;
                res.send(resp);
            }
        } catch {
            res.send({'status': false});
        }
    });

    app.listen(port, () =>
        logger.info(`Starting deployment server at http://127.0.0.1:${port}/.`));
}
