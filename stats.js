const cache = new (require('./cache').ApiCache)();

const units = ["b", "KiB", "MiB", "GiB", "TiB", "PiB"]
const len_units = units.length - 1;

function storeConvert(size, idx=0) {
  if (size <= 0) {
    return "0";
  }

  while (idx < len_units && size > 1024) {
    size /= 1024;
    idx ++;
  }
  return `${size.toFixed(1)} ${units[idx]}`;
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

async function getAccount(username) {
  const response = await cache.requestWithCache(`/users/${username}`);
  return {
    username: username,
    followers: response['followers'],
    repos: response['public_repos'],
    langs: await langStatistics(
      Object.values(await cache.requestWithCache(`/users/${username}/repos`)
      ).map(async (resp) => {
        return await getLanguage(username, resp['name']);
      })),
  };
}

async function getRepository(username, repo) {
  // get releases (700ms): (await cache.requestWithCache(`/repos/${username}/${repo}/releases`)).length
  const info = await cache.requestWithCache(`/repos/${username}/${repo}`);
  return {
    username: username,
    repo: repo,
    size: storeConvert(info['size'], 1),
    forks: info['forks'],
    stars: info['stargazers_count'],
    watchers: info['watchers_count'],
    license: info['license']['spdx_id'],
    langs: await getLanguage(username, repo),
  };
}

module.exports = {
  getAccount: getAccount,
  getRepository: getRepository,
}
