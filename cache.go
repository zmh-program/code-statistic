package main

import (
	"context"
	"encoding/json"
	"fmt"
	"github.com/go-redis/redis/v8"
	"github.com/kataras/iris/v12"
	"time"
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
		logger.Debug("Connected to Redis server successfully")
	}
	if conf.Debug {
		cache.FlushAll(context.Background())
		logger.Debug("Flushed all cache")
	}
}

func SetCache(ctx iris.Context, key string, value string) error {
	return cache.Set(ctx.Request().Context(), key, value, conf.Redis.Expiration*time.Second).Err()
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

func GetJSONCache(ctx iris.Context, key string) (value AnalysisData, ok bool) {
	val, ok := GetCache(ctx, key)
	if !ok {
		return AnalysisData{}, false
	}
	err := json.Unmarshal([]byte(val), &value)
	if err != nil {
		return AnalysisData{}, false
	}
	return value, true
}

// CachedHandler is a decorator for handlers to enable caching their response.
func CachedHandler(h iris.Handler) iris.Handler {
	return func(ctx iris.Context) {
		path := ctx.Path()

		data, ok := GetJSONCache(ctx, path)
		if ok {
			if conf.Debug {
				logger.Debugf("Hit cache of %s", path)
			}
			EndBody(ctx, data)
		} else {
			h(ctx)
		}
	}
}

func EndBody(ctx iris.Context, data AnalysisData) {
	if data.Err != "" {
		ThrowError(ctx, data.Err, data.Code)
	} else {
		err := ctx.JSON(data.Data)
		if err != nil {
			return
		}
	}
}

func EndBodyWithCache(ctx iris.Context, data AnalysisData) {
	err := SetJSONCache(ctx, ctx.Path(), data)
	if conf.Debug {
		logger.Debugf("Set cache of %s", ctx.Path())
	}
	if err != nil {
		logger.Errorf("Failed to set cache: %s", err.Error())
		ThrowError(ctx, err.Error(), iris.StatusInternalServerError)
		return
	}
	EndBody(ctx, data)
}
