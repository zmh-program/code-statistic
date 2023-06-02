package main

import (
	"fmt"
)

func GetUser(username string) (data map[string]interface{}, err error) {
	err = Get(fmt.Sprintf("users/%s", username), &data)
	return data, err
}

func GetRepos(username string) (data []interface{}, err error) {
	err = Get(fmt.Sprintf("users/%s/repos", username), &data)
	return data, err
}

func GetRepo(username string, repo string) (data map[string]interface{}, err error) {
	err = Get(fmt.Sprintf("repos/%s/%s", username, repo), &data)
	return data, err
}

func GetLanguages(username string, repo string) (data map[string]interface{}, err error) {
	err = Get(fmt.Sprintf("repos/%s/%s/languages", username, repo), &data)
	return data, err
}

func GetUserExist(username string) bool {
	res, err := GetUser(username)
	if err != nil {
		return false
	}
	val, ok := res["message"]
	return !(ok && val == "Not Found")
}

func GetRepoExist(username string, repo string) bool {
	res, err := GetRepo(username, repo)
	if err != nil {
		return false
	}
	val, ok := res["message"]
	return !(ok && val == "Not Found")
}

func CollectLanguages(username string, repos []string) (data map[string]int, err error) {
	data = make(map[string]int)
	for _, repo := range repos {
		languages, err := GetLanguages(username, repo)
		if err != nil {
			return data, err
		}
		for k, v := range languages {
			data[k] += v.(int)
		}
	}
	return data, nil
}
