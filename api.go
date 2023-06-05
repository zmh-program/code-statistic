package main

import (
	"fmt"
	"github.com/kataras/iris/v12"
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

func GetLanguages(username string, repo string) (data map[string]float64, err error) {
	err = Get(fmt.Sprintf("repos/%s/%s/languages", username, repo), &data)
	return data, err
}

func getLicense(license interface{}) string {
	if license != nil {
		return license.(map[string]interface{})["spdx_id"].(string)
	}
	return "Empty"
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

func iterRepos(username string) (data []interface{}, err error) {
	repos, err := GetRepos(username)
	if err != nil {
		logger.Error(err)
		return nil, err
	}

	var res []interface{}
	for _, repo := range repos {
		repo := repo.(map[string]interface{})
		if !repo["fork"].(bool) {
			res = append(res, repo)
		}
	}
	return res, nil
}

func CollectLanguages(username string, repos []interface{}) (data map[string]float64, err error) {
	data = make(map[string]float64)
	channel := make(chan map[string]float64, len(repos))

	for _, repo := range repos {
		go func(username string, repo string) {
			languages, err := GetLanguages(username, repo)
			if err != nil {
				return
			}
			channel <- languages
		}(username, repo.(map[string]interface{})["name"].(string))
	}

	for range repos {
		select {
		case languages := <-channel:
			for k, v := range languages {
				data[k] += v
			}
		}
	}

	return data, nil
}

type AnalysisData struct {
	Data iris.Map
	Err  string
	Code int
}

func AnalysisUser(username string) AnalysisData {
	if GetUserExist(username) {
		res, err := GetUser(username)
		if err != nil {
			return AnalysisData{nil, err.Error(), iris.StatusInternalServerError}
		}
		repos, err := iterRepos(username)
		if err != nil {
			return AnalysisData{nil, err.Error(), iris.StatusInternalServerError}
		}
		languages, err := CollectLanguages(username, repos)
		if err != nil {
			return AnalysisData{nil, err.Error(), iris.StatusInternalServerError}
		}
		return AnalysisData{
			iris.Map{
				"username":  username,
				"location":  res["location"],
				"org":       res["type"] != "User",
				"repos":     res["public_repos"],
				"follower":  ScaleConvert(res["followers"].(float64), true),
				"stars":     ScaleConvert(Sum(repos, "stargazers_count"), true),
				"forks":     ScaleConvert(Sum(repos, "forks_count"), true),
				"issues":    ScaleConvert(Sum(repos, "open_issues_count"), true),
				"watchers":  ScaleConvert(Sum(repos, "watchers_count"), true),
				"languages": CountLanguages(languages),
			}, "", iris.StatusOK,
		}
	}
	return AnalysisData{nil, "user not found", iris.StatusNotFound}
}

func AnalysisRepo(username string, repo string) AnalysisData {
	if GetRepoExist(username, repo) {
		res, err := GetRepo(username, repo)
		if err != nil {
			return AnalysisData{nil, err.Error(), iris.StatusInternalServerError}
		}
		languages, err := GetLanguages(username, repo)
		if err != nil {
			return AnalysisData{nil, err.Error(), iris.StatusInternalServerError}
		}
		return AnalysisData{
			iris.Map{
				"username":  username,
				"repo":      repo,
				"size":      SizeConvert(res["size"].(float64), 1),
				"stars":     ScaleConvert(res["stargazers_count"].(float64), true),
				"forks":     ScaleConvert(res["forks_count"].(float64), true),
				"watchers":  ScaleConvert(res["watchers_count"].(float64), true),
				"issues":    ScaleConvert(res["open_issues_count"].(float64), false),
				"color":     GetColor(res["language"]),
				"license":   getLicense(res["license"]),
				"languages": CountLanguages(languages),
			}, "", iris.StatusOK,
		}
	}
	return AnalysisData{nil, "repo not found", iris.StatusNotFound}
}
