import { expiration } from "./config";
import { getLogger } from "log4js";
const logger = getLogger("Cache");

logger.level = "debug";


class Cache {
  protected caches: Record<string, Record<string, any>>;
  public expiration: number;
  constructor() {
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
    }, this.expiration / 2);
  }

  cache(name: string, func: (...params: any[]) => Promise<any>): (...params: any[]) => Promise<any> {
    /**
     * Async Function Cache.
     */

    const _this: Cache = this;
    return async function (...params : any[]) {
      const key: string = name + params.toString();
      if (_this.exist(key)) {
        return _this.get(key);
      } else {
        const response: any = await func(...params);
        _this.set(key, response);
        return response;
      }
    }
  }
}

export const cache = new Cache();
