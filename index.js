const express = require('express');
const cache = new (require('./cache').ApiCache)(process.env.CODE_STATISTIC || "");
const app = express();

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

app.get('/user/:user/', async function (req, res) {
    const username = req.params.user;
    const response = await cache.requestWithCache(`/users/${username}/repos`);
    const result = await langStatistics(Object.values(response).map(async (resp) => {
        return await getLanguage(username, resp['name']);
    }));
    res.send(result);
})

app.get('/repo/:user/:repo/', function (req, res) {
    res.send(getLanguage(req.params['user'], req.params['repo']));
})

app.listen(3000);