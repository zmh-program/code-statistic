package main

import (
	"github.com/kataras/iris/v12"
)

func UserAPI(ctx iris.Context) {
	username := ctx.Params().Get("username")
	data := AnalysisUser(username)
	EndBodyWithCache(ctx, data)
}

func RepoAPI(ctx iris.Context) {
	username, repo := ctx.Params().Get("username"), ctx.Params().Get("repo")
	data := AnalysisRepo(username, repo)
	EndBodyWithCache(ctx, data)
}

func ContributorAPI(ctx iris.Context) {
	username, repo := ctx.Params().Get("username"), ctx.Params().Get("repo")
	data := AnalysisContributor(username, repo)
	EndBodyWithCache(ctx, data)
}
