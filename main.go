package main

import (
	"fmt"
	"github.com/kataras/iris/v12"
	"github.com/sirupsen/logrus"
)

func main() {
	app := iris.New()
	route := app.Party("/api")
	{
		route.Use(iris.Compression)
		route.Get("/user/{username:string}", AnalyseUser)
		route.Get("/repo/{username:string}/{repo:string}", AnalyseRepo)
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
			ctx.StatusCode(iris.StatusInternalServerError)
			ctx.JSON(iris.Map{
				"message": err.Error(),
			})
			return
		}
		fmt.Println(res)
		ctx.JSON(iris.Map{})
	}
	ctx.StatusCode(iris.StatusNotFound)
	ctx.JSON(iris.Map{
		"message": "User not found.",
	})
}

func AnalyseRepo(ctx iris.Context) {
	return
}
