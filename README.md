<div align="center"> 

[<img src="docs/icon.png" alt="Code Statistic" width="64" height="64" style="transform: translateY(50px);">](https://stats.deeptrain.net)
# [Code Statistic](https://stats.deeptrain.net)

#### ⚡ Dynamically generate your github statistic card!

![License](https://img.shields.io/github/license/zmh-program/code-statistic?style=flat-square)
![GitHub release](https://img.shields.io/github/v/release/zmh-program/code-statistic?style=flat-square)
![GitHub stars](https://img.shields.io/github/stars/zmh-program/code-statistic?style=flat-square)
<br>
</div>

## 🍊 User Card
Hey, want to generate quickly? Have a look at our [website](https://stats.deeptrain.net/)!

The user card is used to count the languages of all projects under the user and generate statistics, calculate total stars earned, forks acquired, followers, watchers, number of open issues, etc.
```markdown
[![zmh-program's Github Stats](https://stats.deeptrain.net/user/zmh-program/)](https://github.com/zmh-program/code-statistic)
```
[![zmh-program's Github Stats](https://stats.deeptrain.net/user/zmh-program/)](https://github.com/zmh-program/code-statistic)

> **Note**
> currently only your own repositories as statistics, do not support the repositories **contributed** to others, do not support **private** repositories, excluding **fork** repositories.
>
> We will count all repository data such as stars, forks, open issues and output the statistic.


## 🍉 Repository Card
The repository card are used to show the language analysis of the project, stars, forks, license, repository size, etc.

```markdown
[![Deeptrain's Github Stats](https://stats.deeptrain.net/repo/zmh-program/chatnio)](https://github.com/zmh-program/code-statistic)
```
[![Deeptrain's Github Stats](https://stats.deeptrain.net/repo/zmh-program/chatnio)](https://github.com/zmh-program/code-statistic)

> **Warning**
> by default, we can't get the data of private repo, please use your own [token](https://github.com/settings/tokens/new) to deploy. Don't forget to check the box to access your private repositories!

## 🍎 Contributor Card
The contributor card is used to count the contributors in a project and will be ranked by the number of `commits`.
```markdown
[![Web ChatGPT QQ Bot's Contributors](https://stats.deeptrain.net/contributor/zmh-program/web-chatgpt-qq-bot)](https://github.com/zmh-program/code-statistic)
```
[![Web ChatGPT QQ Bot's Contributors](https://stats.deeptrain.net/contributor/zmh-program/web-chatgpt-qq-bot)](https://github.com/zmh-program/code-statistic)


The param `column` is the number of contributors in each row. The minimum is **4** and the default is **6**.
Add `&column=` to customize the number of columns.

## 🍇 Release Card
The release card is used to display information about the release, such as branch, tag name, time and description, etc.
```markdown
[![Deeptrain's Latest Release](https://stats.deeptrain.net/release/zmh-program/code-statistic)](https://github.com/zmh-program/code-statistic)
```
[![Deeptrain's Latest Release](https://stats.deeptrain.net/release/zmh-program/code-statistic)](https://github.com/zmh-program/code-statistic)

Show previous release versions by setting the parameter `tag`. Customize the release tag by adding `&tag=`. The default is **latest**.

## 🍋 Issue Card
The issue card is used to show the status of the issue, title, time, communication, etc.
```markdown
[![Vue's Issue #13013](https://stats.deeptrain.net/issue/vuejs/vue/13013)](https://github.com/zmh-program/code-statistic)
```
[![Vue's Issue #13013](https://stats.deeptrain.net/issue/vuejs/vue/13013)](https://github.com/zmh-program/code-statistic)

## 🍓 Pull Request Card
The pull request card is used to show the status of the pull request, branches, title, time, migrations, etc.
```markdown
[![Code Statistic's PR #1](https://stats.deeptrain.net/pull/zmh-program/code-statistic/1)](https://github.com/zmh-program/code-statistic)
```
[![Code Statistic's PR #1](https://stats.deeptrain.net/pull/zmh-program/code-statistic/1)](https://github.com/zmh-program/code-statistic)

## 🥝 Dark Theme
Very easy, just add `?theme=dark` after it in any kind of card!
```markdown
[![web-chatgpt-qq-bot's Github Stats](https://stats.deeptrain.net/repo/zmh-program/web-chatgpt-qq-bot/?theme=dark)](https://github.com/zmh-program/code-statistic)
```
[![web-chatgpt-qq-bot's Github Stats](https://stats.deeptrain.net/repo/zmh-program/web-chatgpt-qq-bot/?theme=dark)](https://github.com/zmh-program/code-statistic)


## 👨‍💻 API
1. User
    > `GET` `https://stats.deeptrain.net/api/user/{user}`
    > 
    > Example response:
    > ```json
    > {
    >   "username": "zmh-program",
    >   "org": false,
    >   "location": "Shandong, China",
    >   "repos": 24,
    >   "stars": "0.3k",
    >   "watchers": "0.3k",
    >   "followers": "45",
    >   "forks": "10",
    >   "issues": "2",
    >   "languages": [
    >     {
    >       "color": "#3572A5",
    >       "lang": "Python",
    >       "percent": 35.30345154490841,
    >       "text": "Python 35% (525.1k)",
    >       "value": 525070
    >     }, 
    >     ...
    >   ]
    > }
    > ```
    > Error response:
    > ```json
    > {
    >  "message": "user not found"
    > }
    > ```                  
    <br>

2. Repo 
    >  `GET` `https://stats.deeptrain.net/api/repo/{user}/{repo}`
    > 
    > Example response:
    > ```json
    > {
    >   "username": "zmh-program",
    >   "license": "MIT",
    >   "repo": "code-statistic",
    >   "stars": "26",
    >   "watchers": "26",
    >   "color": "#a91e50",
    >   "forks": "1",
    >   "issues": "0",
    >   "size": "1.0 MiB",
    >   "languages": [
    >     {
    >       "color": "#3178c6",
    >       "lang": "TypeScript",
    >       "percent": 42.76333789329686,
    >       "text": "TypeScript 43% (21.9k)",
    >       "value": 21882
    >     }, 
    >     ...
    >   ]
    > }
    > ```
    > Error response:
    > ```json
    > {
    >   "message": "repo not found"
    > }
    > ```

3. Contributor
   > `GET` `https://stats.deeptrain.net/api/contributor/{user}/{repo}`
   >
   > Example response:
   > ```json
   > {
   >   "color": "#d2cece",
   >    "contributors": [{
   >        "avatar": "https://avatars.githubusercontent.com/u/112773885?v=4",
   >        "commits": 18,
   >        "image": "iVBORw0KGgoAAAANSUhEUgAA…uxEC+HwAAAAAElFTkSuQmCC",
   >        "username": "zmh-program"
   >    }],
   >    "repo": "zmh-program",
   >    "username": "zmh-program"
   > }
   > ```
   > Error response:
   > ```json
    > {
    >   "message": "repo not found"
    > }
    > ```

4. Release
   > `GET` `https://stats.deeptrain.net/api/release/{user}/{repo}/{tag|latest}`
   >
   > Example response:
   > ```json
   > {
   >   "assets": [
   >     {
   >       "name": "install.sh",
   >       "size": "1.6k",
   >       "type": "application/x-sh"
   >     },
   >     {
   >       "name": "package.zip",
   >       "size": "791.5k",
   >       "type": "application/zip"
   >     }
   >   ],
   >   "author": {
   >     "avatar": "https://avatars.githubusercontent.com/u/112773885?v=4",
   >     "image": "iVBORw0KGgoAAAANSUhEUgAA…uxEC+HwAAAAAElFTkSuQmCC",
   >     "type": "User",
   >     "username": "zmh-program"
   >   },
   >   "branch": "main",
   >   "color": "#41b883",
   >   "date": "2023-05-23T15:17:52Z",
   >   "draft": false,
   >   "name": "1.6",
   >   "prerelease": false,
   >   "repo": "web-chatgpt-qq-bot",
   >   "tag": "1.6",
   >   "text": "here is release description...",
   >   "username": "zmh-program"
   > }
   > ```
   > Error response:
   > ```json
    > {
    >   "message": "release not found"
    > }
    > ```

5. Issue
   > `GET` `https://stats.deeptrain.net/api/issue/{user}/{repo}/{id}`
   >
   > Example response:
   > ```json
   > {
   >   "color": "#f1e05a",
   >   "comments": 1,
   >   "date": "2022-01-18T21:10:09Z",
   >   "description": "...",
   >   "id": 12,
   >   "labels": [],
   >   "opener": {
   >     "avatar": "https://avatars.githubusercontent.com/u/63081316?v=4",
   >     "image": "iVBORw0KGgoAAAANSUhEUgAA…0cg9hQAAAAASUVORK5CYII=",
   >     "type": "User",
   >     "username": "mollthecoder"
   >   },
   >   "reactions": 0,
   >   "repo": "extensions",
   >   "state": "closed",
   >   "title": "Create dictionaries.js",
   >   "username": "turbowarp"
   > }
   > ```
   > Error response:
   > ```json
    > {
    >   "message": "issue not found"
    > }
    > ```

6. Pull Request
   > `GET` `https://stats.deeptrain.net/api/pull/{user}/{repo}/{id}`
   >
   > Example response:
   > ```json
   > {
   >   "additions": "72",
   >   "changed_files": 1,
   >   "color": "#f1e05a",
   >   "comments": 1,
   >   "commits": 1,
   >   "creator": {
   >     "avatar": "https://avatars.githubusercontent.com/u/63081316?v=4",
   >     "image": "iVBORw0KGgoAAAANSUhEUgAA…0cg9hQAAAAASUVORK5CYII=",
   >     "type": "User",
   >     "username": "mollthecoder"
   >   },
   >   "date": "2022-01-18T21:10:09Z",
   >   "deletions": "0",
   >   "description": "...",
   >   "id": 12,
   >   "labels": [],
   >   "migration": {
   >     "base": "TurboWarp:master",
   >     "head": "mollthecoder:patch-1"
   >   },
   >   "repo": "extensions",
   >   "state": "closed",
   >   "title": "Create dictionaries.js",
   >   "username": "turbowarp"
   > }
   > ```
   > Error response:
   > ```json
    > {
    >   "message": "pull requst not found"
    > }
    > ```


## Changelog
- v1 `Express + EJS`
- v2 `Nuxt + Element` 
- v3 `Golang + PHP + Vue3 + TDesign`
