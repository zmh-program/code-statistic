package main

import (
	"context"
	"fmt"
	"github.com/go-redis/redis/v8"
)

var client *redis.Client

func SetupCache() {
	client = redis.NewClient(&redis.Options{
		Addr:     fmt.Sprintf("%s:%d", conf.Redis.Host, conf.Redis.Port),
		Password: conf.Redis.Password,
		DB:       conf.Redis.DB,
	})
	_, err := client.Ping(context.Background()).Result()
	if err != nil {
		logger.Fatal("Failed to connect to Redis server: ", err)
	} else {
		logger.Debug("Connected to Redis server successfully.")
	}
}
