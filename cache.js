const logger = (require("log4js")).getLogger("Cache");
const axios = require("axios");
const conf = require("./config");

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
}

class ApiCache extends LightCache {
  constructor() {
    super();
    this.token = conf.token;
    this.expiration = conf.expiration;
  }
  async syncAxios(url) {
    return (await axios.get(url, {
      headers: {
        Accept: "application/json",
        Authorization: `Bearer ${this.token}`,
      }
    })).data;
  }

  async requestWithCache(url) {
    // 类似于 Service Worker 缓存机制
    const cache = this.getCache(url);
    if (cache === undefined) {
      logger.info("Request GitHub API address: ", url);
      const response = await this.syncAxios(url);
      this.setCache(url, response, this.expiration);
      return response;
    } else {
      logger.debug("Cached GitHub API address: ", url);
      return cache;
    }
  }
}

module.exports = {
  LightCache: LightCache,
  ApiCache: ApiCache,
}