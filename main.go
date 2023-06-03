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
		repo, err := iterRepos(username)
		if err != nil {
			ThrowError(ctx, iris.StatusInternalServerError, err.Error())
			return
		}
		ctx.JSON(iris.Map{
			"username": username,
			"location": res["location"],
			"org":      res["type"] != "User",
			"repos":    res["public_repos"],
			"follower": ScaleConvert(res["followers"].(float64), true),
			"stars":    ScaleConvert(Sum(repo, "stargazers_count"), true),
			"forks":    ScaleConvert(Sum(repo, "forks_count"), true),
			"issues":   ScaleConvert(Sum(repo, "open_issues_count"), true),
			"watchers": ScaleConvert(Sum(repo, "watchers_count"), true),
		})
		return
	}
	ThrowError(ctx, iris.StatusNotFound, "User not found.")
}

func AnalyseRepo(ctx iris.Context) {
	return
}
