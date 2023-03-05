const logger = (require("log4js")).getLogger("Cache");
const axios = require("axios");
const conf = require("./config");

import "axios";

logger.level = "debug";
axios.defaults.baseURL = "https://api.github.com";

export class LightCache {
    protected caches: object;
    constructor() {
        this.caches = {};
    }
    setCache(key: any, value: any, expiration: number) {
        this.caches[key] = {
            value: value,
            expiration: expiration + (new Date().getTime() / 1000),
        };
        return value;
    }
    getCache(key: any) {
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

export class ApiCache extends LightCache {
    protected token: string;
    protected expiration: number;
    constructor() {
        super();
        this.token = conf.token;
        this.expiration = conf.expiration;
    }
    async syncAxios(url: string) {
        return (await axios.get(url, {
            headers: {
                Accept: "application/json",
                Authorization: `Bearer ${this.token}`,
            }
        })).data;
    }

    async requestWithCache(url: string) {
        const cache = this.getCache(url);
        if (cache === undefined) {
            logger.info("Request API: ", url);
            const response = await this.syncAxios(url);
            this.setCache(url, response, this.expiration);
            return response;
        } else {
            logger.debug("Hit Cache: ", url);
            return cache;
        }
    }
}