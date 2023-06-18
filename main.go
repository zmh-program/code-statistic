package main

import (
	"fmt"
	"github.com/kataras/iris/v12"
	"github.com/sirupsen/logrus"
	"github.com/spf13/viper"
	"os"
	"strings"
	"time"
)

var logger = logrus.New()

type Formatter struct {
}

func (f *Formatter) Format(entry *logrus.Entry) ([]byte, error) {
	timestamp := time.Now().Format("2006/01/02 15:04")
	level := strings.ToUpper(entry.Level.String())
	message := entry.Message

	var color string
	switch entry.Level {
	case logrus.DebugLevel:
		color = "\u001B[33m"
	case logrus.InfoLevel:
		color = "\033[32m"
	case logrus.WarnLevel:
		color = "\033[33m"
	case logrus.ErrorLevel, logrus.FatalLevel, logrus.PanicLevel:
		color = "\033[31m"
	default:
		color = "\033[0m"
	}

	return []byte(fmt.Sprintf("%s[%s]\u001B[0m %s - %s\n", color, level, timestamp, message)), nil
}

func main() {
	logger.SetLevel(logrus.DebugLevel)
	logger.SetFormatter(&Formatter{})

	_, err := os.Stat("config.yaml")
	if err != nil && os.IsNotExist(err) {
		logger.Fatal("Config file (config.yaml) not found!")
	}

	viper.SetConfigFile("config.yaml")
	if err := viper.ReadInConfig(); err != nil {
		logger.Fatalf("Failed to read config file: %v", err)
	}
	if err := viper.Unmarshal(&conf); err != nil {
		logger.Fatalf("Failed to unmarshal config file: %v", err)
	}

	tokenList = DetectToken()
	SetupCache()

	app := iris.Default()
	{
		app.Get("/api/user/{username:string}", CachedHandler(UserAPI))
		app.Get("/api/repo/{username:string}/{repo:string}", CachedHandler(RepoAPI))
		app.Get("/api/contributor/{username:string}/{repo:string}", CachedHandler(ContributorAPI))
	}
	app.Listen(fmt.Sprintf(":%d", conf.Server.Port))
}

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
