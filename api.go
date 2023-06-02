package main

import "fmt"

func GetUser(username string) (res []interface{}, err error) {
	return Get(fmt.Sprintf("users/%s", username))
}

func GetRepos(username string) (res []interface{}, err error) {
	return Get(fmt.Sprintf("users/%s/repos", username))
}

func GetRepo(username string, repo string) (res []interface{}, err error) {
	return Get(fmt.Sprintf("repos/%s/%s", username, repo))
}

func GetLanguages(username string, repo string) (res []interface{}, err error) {
	return Get(fmt.Sprintf("repos/%s/%s/languages", username, repo))
}
