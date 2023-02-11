const express = require('express');
const axios = require('axios');
const app = express();

async function syncAxios(url) {
    return (await axios.get(url)).data;
}

async function getLanguage(user, repo) {
    return await syncAxios(`https://api.github.com/repos/${user}/${repo}/languages`);
}
app.get('/user/:user/', async function (req, res) {
    const username = req.params.user;
    const response = await syncAxios(`https://api.github.com/users/${username}/repos`);
    const statistic = Object.values(response).map(async (resp) => {
        return await getLanguage(username, resp['name']);
    });
    res.send(statistic);
})

app.get('/repo/:user/:repo/', function (req, res) {
    res.send(getLanguage(req.params['user'], req.params['repo']));
})

app.listen(3000);