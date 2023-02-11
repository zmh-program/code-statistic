// 类似于 Service Worker 缓存机制

const axios = require("axios");
const token = process.env.CODE_STATISTIC || "";

class ApiCache {
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
    if (! key in this.caches) {
      return undefined;
    }
    const memory = this.caches[key];
    if (memory.expiration > new Date().getTime() / 1000) {
      return undefined;
    }
    return memory.value;
  }
  
  async syncAxios(url) {
    return (await axios.get(url, {
      headers: {
        Accept: "application/json",
        Authorization: `Bearer ${token}`,
      }
    })).data;
  }

  async requestWithCache(url, expiration=3600) {
    const cache = this.getCache(url);
    if ( ! cache === undefined ) {
      const resp = await this.syncAxios(url);
      return this.setCache(url, resp, expiration);
    }
    return cache;
  }
}

module.exports = [
  token,
  ApiCache
]