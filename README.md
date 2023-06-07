<div align="center"> 

[<img src="docs/icon.png" alt="Code Statistic" width="64" height="64" style="transform: translateY(50px);">](https://stats.deeptrain.net)
# [Code Statistic](https://stats.deeptrain.net)

#### Dynamically generate your GitHub statistic card!

![License](https://img.shields.io/github/license/zmh-program/code-statistic?style=flat-square)
![GitHub release](https://img.shields.io/github/v/release/zmh-program/code-statistic?style=flat-square)
![GitHub stars](https://img.shields.io/github/stars/zmh-program/code-statistic?style=flat-square)
<br>
</div>

## 🍊 User Card
Hey, want to generate quickly? Have a look at our [website](https://stats.deeptrain.net/)!

Use in your Github homepage! use it in your website! use it anywhere you want!
```markdown
[![zmh-program's Github Stats](https://stats.deeptrain.net/user/zmh-program/)](https://github.com/zmh-program/code-statistic)
```
[![zmh-program's Github Stats](https://stats.deeptrain.net/user/zmh-program/)](https://github.com/zmh-program/code-statistic)

> **Note**
> Currently only your own repositories as statistics, do not support the repositories **contributed** to others, do not support **private** repositories, excluding **fork** repositories.
>
> We will count all repository data such as stars, forks, open issues and output the statistic.


## 🍉 Repository Card
Use in repository introduction! use in pull request! use in issue! use it anywhere you want to introduce the project!

```markdown
[![Deeptrain's Github Stats](https://stats.deeptrain.net/repo/zmh-program/Deeptrain)](https://github.com/zmh-program/code-statistic)
```
[![Deeptrain's Github Stats](https://stats.deeptrain.net/repo/zmh-program/Deeptrain)](https://github.com/zmh-program/code-statistic)

> **Warning**
> By default, we can't get the data of private repo, please use your own [token](https://github.com/settings/tokens/new) to deploy. Don't forget to check the box to access your private repositories!

## 🥝 Dark Theme
Very easy, just add `?theme=dark` after it in any kind of card!
```markdown
[![web-chatgpt-qq-bot's Github Stats](https://stats.deeptrain.net/repo/zmh-program/web-chatgpt-qq-bot/?theme=dark)](https://github.com/zmh-program/code-statistic)
```
[![web-chatgpt-qq-bot's Github Stats](https://stats.deeptrain.net/repo/zmh-program/web-chatgpt-qq-bot/?theme=dark)](https://github.com/zmh-program/code-statistic)


## 👨‍💻 API
1. `GET` `https://stats.deeptrain.net/api/user/{user}`

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

2. `GET` `https://stats.deeptrain.net/api/repo/{user}/{repo}`
    
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