package main

import (
	"github.com/kataras/iris/v12"
	"github.com/sirupsen/logrus"
)

func main() {
	app := iris.Default()
	{
		app.Get("/user/{username:string}", UserAPI)
		app.Get("/repo/{username:string}/{repo:string}", RepoAPI)
	}

	logger.SetLevel(logrus.DebugLevel)
	logger.SetFormatter(&Formatter{})

	tokenList = GetTokenFromEnv()
	ValidateToken()

	app.Listen(":8080")
}

func UserAPI(ctx iris.Context) {
	username := ctx.Params().Get("username")
	data, err, code := AnalysisUser(username)
	if err != nil {
		ThrowError(ctx, err.Error(), code)
	} else {
		ctx.JSON(data)
	}
}

func RepoAPI(ctx iris.Context) {
	username, repo := ctx.Params().Get("username"), ctx.Params().Get("repo")
	data, err, code := AnalysisRepo(username, repo)
	if err != nil {
		ThrowError(ctx, err.Error(), code)
	} else {
		ctx.JSON(data)
	}
}
