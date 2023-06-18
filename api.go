package main

import (
	"errors"
	"fmt"
	"github.com/kataras/iris/v12"
	"sort"
)

func getRateLimit(token string) (data map[string]interface{}, err error) {
	err = NativeGet("rate_limit", token, &data)
	return data, err
}

func GetUser(username string) (data map[string]interface{}, err error) {
	err = Get(fmt.Sprintf("users/%s", username), &data)
	if err == nil && data["message"] == "Not Found" {
		return nil, errors.New("user not found")
	}
	return data, err
}

func GetRepos(username string) (data []interface{}, err error) {
	err = Get(fmt.Sprintf("users/%s/repos", username), &data)
	return data, err
}

func GetRepo(username string, repo string) (data map[string]interface{}, err error) {
	err = Get(fmt.Sprintf("repos/%s/%s", username, repo), &data)
	if err == nil && data["message"] == "Not Found" {
		return nil, errors.New("repo not found")
	}
	return data, err
}

func GetLanguages(username string, repo string) (data map[string]float64, err error) {
	err = Get(fmt.Sprintf("repos/%s/%s/languages", username, repo), &data)
	return data, err
}

func getRelease(username string, repo string, tag string) (data map[string]float64, err error) {
	err = Get(fmt.Sprintf("repos/%s/%s/releases/tags/%s", username, repo, tag), &data)
	return data, err
}

func getLatestRelease(username string, repo string) (data map[string]float64, err error) {
	err = Get(fmt.Sprintf("repos/%s/%s/releases/latest", username, repo), &data)
	return data, err
}

func getContributors(username string, repo string) (data []interface{}, err error) {
	err = Get(fmt.Sprintf("repos/%s/%s/contributors", username, repo), &data)
	return data, err
}

func getLicense(license interface{}) string {
	if license != nil {
		return license.(map[string]interface{})["spdx_id"].(string)
	}
	return "Empty"
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

func CountLanguages(languages map[string]float64) []map[string]any {
	total := 0.

	var res []map[string]any
	for _, v := range languages {
		total += v
	}

	for k, v := range languages {
		res = append(res, map[string]any{
			"lang":    k,
			"value":   v,
			"percent": v / total * 100,
			"color":   GetColor(k),
			"text":    fmt.Sprintf("%s %.0f%% (%s)", k, v/total*100, ScaleConvert(v, false)),
		})
	}

	sort.Slice(res, func(i, j int) bool {
		return res[i]["value"].(float64) > res[j]["value"].(float64)
	})

	return res
}

type AnalysisData struct {
	Data iris.Map
	Err  string
	Code int
}

func AnalysisUser(username string) AnalysisData {
	res, err := GetUser(username)
	if err != nil {
		return AnalysisData{nil, err.Error(), iris.StatusNotFound}
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
			"location":  getDefault(res["location"], "unknown"),
			"org":       res["type"] != "User",
			"repos":     res["public_repos"],
			"followers": ScaleConvert(res["followers"].(float64), true),
			"stars":     ScaleConvert(Sum(repos, "stargazers_count"), true),
			"forks":     ScaleConvert(Sum(repos, "forks_count"), true),
			"issues":    ScaleConvert(Sum(repos, "open_issues_count"), true),
			"watchers":  ScaleConvert(Sum(repos, "watchers_count"), true),
			"languages": CountLanguages(languages),
		}, "", iris.StatusOK,
	}
}

func AnalysisRepo(username string, repo string) AnalysisData {
	res, err := GetRepo(username, repo)
	if err != nil {
		return AnalysisData{nil, err.Error(), iris.StatusNotFound}
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

func AnalysisContributor(username string, repo string) AnalysisData {
	res, err := getContributors(username, repo)
	if err != nil {
		return AnalysisData{nil, err.Error(), iris.StatusNotFound}
	}
	var contributors []map[string]any
	for _, v := range res {
		v := v.(map[string]interface{})
		contributors = append(contributors, map[string]any{
			"username": v["login"],
			"avatar":   v["avatar_url"],
			"commits":  v["contributions"],
		})
	}
	return AnalysisData{
		iris.Map{
			"username":     username,
			"repo":         repo,
			"contributors": contributors,
		}, "", iris.StatusOK,
	}
}
