const logger = (require("log4js")).getLogger("Cache");
const axios = require("axios");

logger.level = "debug";
axios.defaults.baseURL = "https://api.github.com";

class LightCache {
  constructor() {
      this.caches = {};
  }
  setCache(key, value, expiration) {
    this.caches[key] = {
      value: value,
      expiration: expiration + (new Date().getTime() / 1000),
    };
    return value;
  }
  getCache(key) {
    if (!(key in this.caches)) {
      return undefined;
    }
    const memory = this.caches[key];
    if (memory.expiration < new Date().getTime() / 1000) {
      return undefined;
    }
    return memory.value;
  }
  getOrSet(key, sync, expiration) {
    const cache = this.getCache(key);
    return cache === undefined ? this.setCache(key, sync(), expiration) : cache;
  }
  async asyncGetOrSet(key, async, expiration) {
    const cache = this.getCache(key);
    return cache === undefined ? this.setCache(key, await async(), expiration) : cache;
  }
}

class ApiCache extends LightCache {
  constructor(token) {
    super();
    this.token = token;
  }
  async syncAxios(url) {
    logger.debug("Request GitHub API address:", url);
    return (await axios.get(url, {
      headers: {
        Accept: "application/json",
        Authorization: `Bearer ${this.token}`,
      }
    })).data;
  }

  async requestWithCache(url, expiration=3600) {
    // 类似于 Service Worker 缓存机制
    return this.asyncGetOrSet(
      url,
      async () => (await this.syncAxios(url)),
      expiration,
    );
  }
}

module.exports = {
  LightCache: LightCache,
  ApiCache: ApiCache,
}