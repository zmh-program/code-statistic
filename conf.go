package main

import (
	"fmt"
	"github.com/sirupsen/logrus"
	"github.com/spf13/viper"
	"math/rand"
	"os"
	"strings"
	"time"
)

type Config struct {
	Server struct {
		Port int
	}
	Redis struct {
		Host       string
		Port       int
		Password   string
		DB         int
		Expiration int
	}
}

var conf Config

var tokenList []string

func GetToken() string {
	source := rand.NewSource(time.Now().UnixNano())
	idx := rand.New(source).Intn(len(tokenList))
	return tokenList[idx]
}

func GetTokenFromEnv() []string {
	data := strings.TrimSpace(os.Getenv("TOKEN"))
	tokenList = strings.Split(data, "|")
	result := make([]string, 0)
	for _, token := range tokenList {
		if strings.HasPrefix(token, "ghp_") {
			result = append(result, token)
		}
	}
	return result
}

func ValidateToken() {
	if len(tokenList) == 0 {
		logger.Fatal("No token found! Please set TOKEN environment variable.")
	}
	logger.Debug(fmt.Sprintf("Found %d available token(s).", len(tokenList)))
}

func ReadToken() {
	tokenList = GetTokenFromEnv()
	ValidateToken()
}

func ReadConfig() {
	_, err := os.Stat("config.yaml")
	if err != nil && os.IsNotExist(err) {
		logger.Fatal("Config file (config.yaml) not found!")
	}

	viper.SetConfigFile("config.yaml")
	if err := viper.ReadInConfig(); err != nil {
		logrus.Fatalf("Failed to read config file: %v", err)
	}
	if err := viper.Unmarshal(&conf); err != nil {
		logrus.Fatalf("Failed to unmarshal config file: %v", err)
	}
}
