const axios = require("axios");
const conf = require("./config");
const cache = require("./cache").cache;

axios.defaults.baseURL = "https://api.github.com";

export function sort(arr: number[]): number[] {
    const len = arr.length - 1;
    for (let i = 0; i <= len; i++) {for (let j = 0; j < len - i; j++) {if (arr[j] > arr[j + 1]) {[arr[j], arr[j + 1]] = [arr[j + 1], arr[j]]}}}
    return arr
}

const store_units = ["B", "KiB", "MiB", "GiB", "TiB", "PiB"];
export function storeConvert(size: number, idx: number = 0): string {
    if (size <= 0) {
        return "0";
    }
    while (idx < (store_units.length - 1) && size > 1024) {
        size /= 1024;
        idx ++;
    }
    return `${size.toFixed(1)} ${store_units[idx]}`;
}

const dec_units = ["", "k", "m"];
export function decConvert(n: number, allowed_pre: boolean = true): string {
    let idx = 0;
    let condition = allowed_pre ? 100 : 1000;
    while (idx < (dec_units.length - 1) && n > condition) { n /= 1000 ; idx ++ }
    return idx === 0 ? n.toString() : n.toFixed(1) + dec_units[idx];
}

export function sum(arr: number[]): number {
    switch (arr.length) {
        case 0 : return 0;
        case 1: return arr[0];
        default: return arr.reduce((a, b) => a + b);
    }
}

export async function request(url: string): Promise<any> {
    const response = await axios.get(url, {
        headers: {
            Accept: "application/json",
            Authorization: `Bearer ${conf.token}`,
        },
    });
    return response.data;
}

export async function requestUser(user: string): Promise<any> {
    return await request(`/users/${user}`);
}

export async function listRepos(user: string): Promise<any> {
    return Object.values(await request(`/users/${user}/repos`)).filter((repo: any): boolean => !repo['fork']);
}

export async function requestRepo(user: string, repo: string): Promise<any> {
    return await request(`/repos/${user}/${repo}`);
}

export async function requestLanguage(user: string, repo: string): Promise<any> {
    return await request(`/repos/${user}/${repo}/languages`);
}

export function getLicense(license: any): string {
    return license ? license['spdx_id'] : "Empty";
}

async function _isAuthenticated(user: string): Promise<boolean> {
    user = user.trim();
    try {
        return (!!user.length) &&
            (conf.requires.includes("*") || conf.requires.includes(user)) &&
            ((await requestUser(user))['message'] !== "Not Found");
    } catch {
        return false;
    }
}
export const isAuthenticated = cache.wrap(_isAuthenticated);

async function _isExistRepo(user: string, repo: string): Promise<boolean> {
    user = user.trim(); repo = repo.trim();
    try {
        return (await isAuthenticated(user)) &&
            (!!repo.length) &&
            ((await requestRepo(user, repo))['message'] !== "Not Found");
    } catch {
        return false;
    }
}
export const isExistRepo = cache.wrap(_isExistRepo);
