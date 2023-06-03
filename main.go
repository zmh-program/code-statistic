package main

import (
	"fmt"
	"github.com/kataras/iris/v12"
)

func main() {
	app := iris.Default()
	{
		app.Get("/user/{username:string}", UserAPI)
		app.Get("/repo/{username:string}/{repo:string}", RepoAPI)
	}

	SetupLogger()
	ReadConfig()
	ReadToken()
	SetupCache()

	app.Listen(fmt.Sprintf(":%d", conf.Server.Port))
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
