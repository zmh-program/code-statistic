package main

import (
	"github.com/kataras/iris/v12"
	"github.com/sirupsen/logrus"
)

func main() {
	app := iris.Default()
	{
		app.Get("/user/{username:string}", AnalyseUser)
		app.Get("/repo/{username:string}/{repo:string}", AnalyseRepo)
	}

	logger.SetLevel(logrus.DebugLevel)
	logger.SetFormatter(&Formatter{})

	tokenList = GetTokenFromEnv()
	ValidateToken()

	app.Listen(":8080")
}

func AnalyseUser(ctx iris.Context) {
	username := ctx.Params().Get("username")
	if GetUserExist(username) {
		res, err := GetUser(username)
		if err != nil {
			ThrowError(ctx, iris.StatusInternalServerError, err.Error())
			return
		}
		repos, err := iterRepos(username)
		if err != nil {
			ThrowError(ctx, iris.StatusInternalServerError, err.Error())
			return
		}
		langs, err := CollectLanguages(username, repos)
		if err != nil {
			ThrowError(ctx, iris.StatusInternalServerError, err.Error())
			return
		}
		ctx.JSON(iris.Map{
			"username":  username,
			"location":  res["location"],
			"org":       res["type"] != "User",
			"repos":     res["public_repos"],
			"follower":  ScaleConvert(res["followers"].(float64), true),
			"stars":     ScaleConvert(Sum(repos, "stargazers_count"), true),
			"forks":     ScaleConvert(Sum(repos, "forks_count"), true),
			"issues":    ScaleConvert(Sum(repos, "open_issues_count"), true),
			"watchers":  ScaleConvert(Sum(repos, "watchers_count"), true),
			"languages": langs,
		})
		return
	}
	ThrowError(ctx, iris.StatusNotFound, "User not found.")
}

func AnalyseRepo(ctx iris.Context) {
	return
}
