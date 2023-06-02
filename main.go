package main

import (
	"fmt"
	"github.com/sirupsen/logrus"
)

func main() {
	logger := logrus.New()
	logger.SetLevel(logrus.DebugLevel)
	logger.SetFormatter(&Formatter{})

	tokens := GetToken()
	fmt.Println(tokens)
}
