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
	timestamp := time.Now().Format("15:04")
	level := strings.ToUpper(entry.Level.String())
	message := entry.Message

	return []byte(fmt.Sprintf("[%s] %s - %s\n", level, timestamp, message)), nil
}

func SetupLogger() {
	logger.SetLevel(logrus.DebugLevel)
	logger.SetFormatter(&Formatter{})
}
