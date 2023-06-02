package main

import (
	"fmt"
	"math/rand"
	"os"
	"strings"
	"time"
)

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
