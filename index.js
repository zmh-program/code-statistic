const express = require('express');
const cache = new (require('./cache').ApiCache)();
const utils = require('./utils');
const conf = require('./config');
const app = express();


const isAvailableUser = (username) => {
    return ( !! username ) && ( conf.requires.includes("*") || conf.requires.includes(username));
}

async function getLanguage(user, repo) {
    return await cache.requestWithCache(`/repos/${user}/${repo}/languages`);
}

async function langStatistics(queue) {
    const res = {};
    for (const idx in queue) {
        const task = queue[idx];
        if (task instanceof Promise) {
            const task_response = await task;
            for (const lang in task_response) {
                lang in res ?
                  res[lang] += task_response[lang] :
                  res[lang] = task_response[lang];
            }
        }
    }
    return res;
}

app.get('/:user/', async function (req, res) {
    const username = req.params['user'];
    if ( ! isAvailableUser(username) ) {
        res.send('permission denied');
        return;
    }
    const response = await cache.requestWithCache(`/users/${username}/repos`);
    const result = await langStatistics(Object.values(response).map(async (resp) => {
        return await getLanguage(username, resp['name']);
    }));
    res.send(result);
});

app.get('/:user/:repo/', async function (req, res) {
    const username = req.params['user'], repo = req.params['repo'];
    if ( ! isAvailableUser(username) ) {
        res.send('permission denied');
        return;
    }
    const info = await cache.requestWithCache(`/repos/${username}/${repo}`);

    res.send({
        size: utils.storeConvert(info['size'], 1),
        forks: info['forks'],
        stars: info['stargazers_count'],
        watchers: info['watchers_count'],
        license: info['license']['spdx_id'],
        langs: await getLanguage(username, repo),
        releases: (await cache.requestWithCache(`/repos/${username}/${repo}/releases`)).length,
    });
});


app.listen(conf.port);
