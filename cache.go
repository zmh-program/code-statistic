package main

import (
	"context"
	"fmt"
	"github.com/go-redis/redis/v8"
	"github.com/kataras/iris/v12"
)

var cache *redis.Client

func SetupCache() {
	cache = redis.NewClient(&redis.Options{
		Addr:     fmt.Sprintf("%s:%d", conf.Redis.Host, conf.Redis.Port),
		Password: conf.Redis.Password,
		DB:       conf.Redis.DB,
	})
	_, err := cache.Ping(context.Background()).Result()
	if err != nil {
		logger.Fatal("Failed to connect to Redis server: ", err)
	} else {
		logger.Debug("Connected to Redis server successfully.")
	}
}

func SetCache(ctx iris.Context, key string, value interface{}) error {
	return cache.Set(ctx.Request().Context(), key, value, conf.Redis.Expiration).Err()
}

func GetCache(ctx iris.Context, key string) (string, error) {
	return cache.Get(ctx.Request().Context(), key).Result()
}
