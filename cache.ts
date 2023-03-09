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

  get(key: string): undefined | any { /** @ts-ignore **/
    const value = this.caches[key];
    if (this.exist(key)) {  //@ts-ignore
      return JSON.parse(value.value);
    }
  }

  set(key: string, value: any): void {  //@ts-ignore
    this.caches[key] = {
      value: JSON.stringify(value),
      expiration: (new Date().getTime() / 1000) + this.expiration,
    }
  }

  exist(key: string): boolean {  //@ts-ignore
    const value = this.caches[key];
    return (!!value) && (value.expiration > (new Date().getTime() / 1000));
  }

  remove(key: string): boolean {  //@ts-ignore
    return delete this.caches[key];
  }

  uptime(): void {
    const _this = this;
    setInterval(function (){
      let n: number = 0;
      for (const key in _this.caches) {  //@ts-ignore
        if (_this.caches[key].expiration < (new Date().getTime() / 1000)) {
          _this.remove(key); n++;
        }
        if (n > 0) logger.debug(`Clean ${n} Caches`);
      }
    }, this.expiration / 2);
  }

  wrap(func: (...params: any[]) => Promise<any>): (...params: any[]) => Promise<any> {
    /**
     * Async Function Cache.
     */

    const _this: Cache = this;
    const name: string = func.name[0] === "_" ? func.name.slice(1) : func.name;
    return async function (...params : any[]) {
      const key: string = name + params.toString();
      if (_this.exist(key)) {
        return _this.get(key);
      } else {
        /** @ts-ignore **/
        const response: any = await func(...params);
        _this.set(key, response);
        return response;
      }
    }
  }
}

export const cache = new Cache(conf.expiration);
