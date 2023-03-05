const conf = require("./config");
const logger = (require("log4js")).getLogger("Cache");

logger.level = "debug";


class Cache {
  protected caches: object;
  public expiration: number;
  constructor(expiration: number) {
    this.caches = {};
    this.expiration = expiration;
    this.uptime();
  }

  get(key: string): undefined | any {
    const value = this.caches[key];
    if (this.exist(key)) {
      return JSON.parse(value.value);
    }
  }

  set(key: string, value: any): void {
    this.caches[key] = {
      value: JSON.stringify(value),
      expiration: (new Date().getTime() / 1000) + this.expiration,
    }
  }

  exist(key: string): boolean {
    const value = this.caches[key];
    return (!!value) && (value.expiration > (new Date().getTime() / 1000));
  }

  remove(key: string): boolean {
    return delete this.caches[key];
  }

  uptime(): void {
    const _this = this;
    setInterval(function (){
      let n: number = 0;
      for (const key in _this.caches) {
        if (_this.caches[key].expiration < (new Date().getTime() / 1000)) {
          _this.remove(key); n++;
        }
        if (n > 0) logger.debug(`Clean ${n} Caches`);
      }
    }, 1800);
  }

  wrap(func: (...params: any[]) => Promise<any>): (...params: any[]) => Promise<any> {
    /**
     * Async Function Cache.
     */

    const _this = this;
    return async function (...params : any[]) {
      const key: string = func.name + JSON.stringify(params);
      if (_this.exist(key)) {
        logger.debug(`Hit Cache <${func.name}>`)
        return _this.get(key);
      } else {
        logger.info(`Cache Response ${func.name}`); // @ts-ignore
        const response: any = await func(...params);
        _this.set(key, response);
        return response;
      }
    }
  }
}

export const cache = new Cache(conf.expiration);
