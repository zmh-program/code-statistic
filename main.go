package main

import (
	"fmt"
	"github.com/sirupsen/logrus"
)

func main() {
	logger.SetLevel(logrus.DebugLevel)
	logger.SetFormatter(&Formatter{})

	tokenList = GetTokenFromEnv()
	ValidateToken()
	fmt.Println(GetRepos("zmh-program"))
}
