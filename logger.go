package main

import (
	"fmt"
	"github.com/sirupsen/logrus"
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

func SetupLogger() {
	logger.SetLevel(logrus.DebugLevel)
	logger.SetFormatter(&Formatter{})
}
