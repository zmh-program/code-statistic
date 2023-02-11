const express = require('express');
const axios = require('axios');
const token = process.env.CODE_STATISTIC || "";
const app = express();

async function syncAxios(url) {
    return (await axios.get(url, {
        headers: {
            Accept: "application/json",
            Authorization: `Bearer ${token}`,
        }
    })).data;
}

async function getLanguage(user, repo) {
    return await syncAxios(`https://api.github.com/repos/${user}/${repo}/languages`);
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
    const response = await syncAxios(`https://api.github.com/users/${username}/repos`);
    const result = await langStatistics(Object.values(response).map(async (resp) => {
        return await getLanguage(username, resp['name']);
    }));
    res.send(result);
})

app.get('/repo/:user/:repo/', function (req, res) {
    res.send(getLanguage(req.params['user'], req.params['repo']));
})

app.listen(3000);