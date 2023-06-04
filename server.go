package main

import (
	"fmt"
	"github.com/kataras/iris/v12"
)

func RunServer() {
	app := iris.Default()
	{
		app.Get("/user/{username:string}", CachedHandler(UserAPI, "username"))
		app.Get("/repo/{username:string}/{repo:string}", CachedHandler(RepoAPI, "username", "password"))
	}
	app.Listen(fmt.Sprintf(":%d", conf.Server.Port))
}

func UserAPI(ctx iris.Context) {
	username := ctx.Params().Get("username")
	data, err, code := AnalysisUser(username)
	if err != nil {
		ThrowError(ctx, err.Error(), code)
	} else {
		EndBody(ctx, data, "username")
	}
}

func RepoAPI(ctx iris.Context) {
	username, repo := ctx.Params().Get("username"), ctx.Params().Get("repo")
	data, err, code := AnalysisRepo(username, repo)
	if err != nil {
		ThrowError(ctx, err.Error(), code)
	} else {
		EndBody(ctx, data, "username", "repo")
	}
}
