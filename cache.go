package main

import (
	"context"
	"encoding/json"
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

func SetCache(ctx iris.Context, key string, value string) error {
	return cache.Set(ctx.Request().Context(), key, value, conf.Redis.Expiration).Err()
}

func GetCache(ctx iris.Context, key string) (val string, ok bool) {
	val, err := cache.Get(ctx.Request().Context(), key).Result()
	if err == redis.Nil {
		return "", false
	} else if err != nil {
		return "", false
	} else if val == "" {
		return "", false
	}
	return val, true
}

func SetJSONCache(ctx iris.Context, key string, value interface{}) error {
	val, err := json.Marshal(value)
	if err != nil {
		return err
	}
	return SetCache(ctx, key, string(val))
}

func GetJSONCache(ctx iris.Context, key string) (value iris.Map, ok bool) {
	val, ok := GetCache(ctx, key)
	if !ok {
		return nil, false
	}
	err := json.Unmarshal([]byte(val), &value)
	if err != nil {
		return nil, false
	}
	return value, true
}

func GenerateKey(ctx iris.Context, keys ...string) string {
	param := ""
	for _, key := range keys {
		value := ctx.Params().GetString(key)
		param += fmt.Sprintf("%s=%s;", key, value)
	}
	return fmt.Sprintf("%s?%s", ctx.Path(), param)
}

// CachedHandler is a decorator for handlers to enable caching their response.
func CachedHandler(h iris.Handler, params ...string) iris.Handler {
	return func(ctx iris.Context) {
		key := GenerateKey(ctx, params...)

		result, ok := GetJSONCache(ctx, key)
		if ok {
			ctx.JSON(result)
			return
		}

		h(ctx)
	}
}

func EndBody(ctx iris.Context, value iris.Map, params ...string) {
	err := SetJSONCache(ctx, GenerateKey(ctx, params...), value)
	if err != nil {
		logger.Errorf("Failed to set cache: %s", err.Error())
		ThrowError(ctx, err.Error(), iris.StatusInternalServerError)
		return
	}
	ctx.JSON(value)
}
