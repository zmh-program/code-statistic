package main

import (
	"github.com/sirupsen/logrus"
)

func main() {
	logger.SetLevel(logrus.DebugLevel)
	logger.SetFormatter(&Formatter{})

	tokenList = GetTokenFromEnv()
	ValidateToken()

}
