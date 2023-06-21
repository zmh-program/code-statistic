package main

import (
	"errors"
	"fmt"
	"github.com/kataras/iris/v12"
	"sort"
	"strconv"
)

func getRateLimit(token string) (data map[string]interface{}, err error) {
	err = NativeGet("rate_limit", token, &data)
	return data, err
}

func getUser(username string) (data map[string]interface{}, err error) {
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

func getRepo(username string, repo string) (data map[string]interface{}, err error) {
	err = Get(fmt.Sprintf("repos/%s/%s", username, repo), &data)
	if err == nil && data["message"] == "Not Found" {
		return nil, errors.New("repo not found")
	}
	return data, err
}

func getLanguages(username string, repo string) (data map[string]float64, err error) {
	err = Get(fmt.Sprintf("repos/%s/%s/languages", username, repo), &data)
	return data, err
}

func getContributors(username string, repo string) (data []interface{}, err error) {
	err = Get(fmt.Sprintf("repos/%s/%s/contributors", username, repo), &data)
	return data, err
}

func getRelease(username string, repo string, tag string) (data map[string]interface{}, err error) {
	if tag == "latest" {
		err = Get(fmt.Sprintf("repos/%s/%s/releases/latest", username, repo), &data)
	} else {
		err = Get(fmt.Sprintf("repos/%s/%s/releases/tags/%s", username, repo, tag), &data)
	}
	if err == nil && data["message"] == "Not Found" {
		return nil, errors.New("release not found")
	}
	return data, err
}

func getIssue(username string, repo string, id int) (data map[string]interface{}, err error) {
	err = Get(fmt.Sprintf("repos/%s/%s/issues/%d", username, repo, id), &data)
	if err == nil && data["message"] == "Not Found" {
		return nil, errors.New("issue not found")
	}
	return data, err
}

func getPullRequest(username string, repo string, id int) (data map[string]interface{}, err error) {
	err = Get(fmt.Sprintf("repos/%s/%s/pulls/%d", username, repo, id), &data)
	if err == nil && data["message"] == "Not Found" {
		return nil, errors.New("pull request not found")
	}
	return data, err
}

func formatLabels(labels []interface{}) (data []interface{}) {
	result := make([]interface{}, len(labels))
	for i, v := range labels {
		m := v.(map[string]interface{})
		result[i] = map[string]any{
			"name":        m["name"],
			"color":       "#" + m["color"].(string),
			"description": m["description"],
			"default":     m["default"],
		}
	}
	return result
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

func collectLanguages(username string, repos []interface{}) (data map[string]float64, err error) {
	data = make(map[string]float64)
	channel := make(chan map[string]float64, len(repos))

	for _, repo := range repos {
		go func(username string, repo string) {
			languages, err := getLanguages(username, repo)
			if err != nil { // fix DMCA Takedown Bug
				channel <- map[string]float64{}
			} else {
				channel <- languages
			}
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

func countAssets(assets []interface{}) (data []interface{}) {
	data = []interface{}{}
	for _, asset := range assets {
		asset := asset.(map[string]interface{})
		data = append(data, map[string]interface{}{
			"name": asset["name"],
			"size": ScaleConvert(asset["size"].(float64), false),
			"type": asset["content_type"],
		})
	}
	return data
}

func countLanguages(languages map[string]float64) []map[string]any {
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
	res, err := getUser(username)
	if err != nil {
		return AnalysisData{nil, err.Error(), iris.StatusNotFound}
	}
	repos, err := iterRepos(username)
	if err != nil {
		return AnalysisData{nil, err.Error(), iris.StatusInternalServerError}
	}
	languages, err := collectLanguages(username, repos)
	if err != nil {
		return AnalysisData{nil, err.Error(), iris.StatusInternalServerError}
	}
	return AnalysisData{
		Data: iris.Map{
			"username":  username,
			"location":  getDefault(res["location"], "unknown"),
			"org":       res["type"] != "User",
			"repos":     res["public_repos"],
			"followers": ScaleConvert(res["followers"].(float64), true),
			"stars":     ScaleConvert(Sum(repos, "stargazers_count"), true),
			"forks":     ScaleConvert(Sum(repos, "forks_count"), true),
			"issues":    ScaleConvert(Sum(repos, "open_issues_count"), true),
			"watchers":  ScaleConvert(Sum(repos, "watchers_count"), true),
			"languages": countLanguages(languages),
		}, Code: iris.StatusOK,
	}
}

func AnalysisRepo(username string, repo string) AnalysisData {
	res, err := getRepo(username, repo)
	if err != nil {
		return AnalysisData{nil, err.Error(), iris.StatusNotFound}
	}
	languages, err := getLanguages(username, repo)
	if err != nil {
		return AnalysisData{nil, err.Error(), iris.StatusInternalServerError}
	}
	return AnalysisData{
		Data: iris.Map{
			"username":  username,
			"repo":      repo,
			"size":      SizeConvert(res["size"].(float64), 1),
			"stars":     ScaleConvert(res["stargazers_count"].(float64), true),
			"forks":     ScaleConvert(res["forks_count"].(float64), true),
			"watchers":  ScaleConvert(res["watchers_count"].(float64), true),
			"issues":    ScaleConvert(res["open_issues_count"].(float64), false),
			"color":     GetColor(res["language"]),
			"license":   getLicense(res["license"]),
			"languages": countLanguages(languages),
		}, Code: iris.StatusOK,
	}
}

func AnalysisContributor(username string, repo string) AnalysisData {
	res, err := getContributors(username, repo)
	if err != nil {
		return AnalysisData{nil, err.Error(), iris.StatusNotFound}
	}
	info, err := getRepo(username, repo)
	if err != nil {
		return AnalysisData{nil, err.Error(), iris.StatusNotFound}
	}

	type result struct {
		index       int
		contributor map[string]any
	}
	contributors := make([]map[string]any, len(res))
	channel := make(chan struct {
		index       int
		contributor map[string]any
	}, len(res))

	for i, v := range res {
		go func(i int, v interface{}) {
			m := v.(map[string]interface{})
			avatar := m["avatar_url"].(string)
			channel <- result{
				index: i,
				contributor: map[string]any{
					"username": m["login"],
					"avatar":   avatar,
					"image":    GetImage(avatar),
					"commits":  m["contributions"],
				},
			}
		}(i, v)
	}

	for range res {
		result := <-channel
		contributors[result.index] = result.contributor
	}

	return AnalysisData{
		Data: iris.Map{
			"username":     username,
			"repo":         repo,
			"color":        GetColor(info["language"]),
			"contributors": contributors,
		}, Code: iris.StatusOK,
	}
}

func AnalysisRelease(username string, repo string, tag string) AnalysisData {
	res, err := getRelease(username, repo, tag)
	if err != nil {
		return AnalysisData{nil, err.Error(), iris.StatusNotFound}
	}
	info, err := getRepo(username, repo)
	if err != nil {
		return AnalysisData{nil, err.Error(), iris.StatusNotFound}
	}
	author := res["author"].(map[string]interface{})
	return AnalysisData{
		Data: iris.Map{
			"username":   username,
			"repo":       repo,
			"tag":        res["tag_name"],
			"name":       res["name"],
			"branch":     res["target_commitish"],
			"date":       res["published_at"],
			"draft":      res["draft"],
			"prerelease": res["prerelease"],
			"color":      GetColor(info["language"]),
			"author": map[string]interface{}{
				"username": author["login"],
				"avatar":   author["avatar_url"],
				"image":    GetImage(author["avatar_url"].(string)),
				"type":     author["type"],
			},
			"description": MarkdownConvert(res["body"]),
			"assets":      countAssets(res["assets"].([]interface{})),
		}, Code: iris.StatusOK,
	}
}

func AnalysisIssue(username string, repo string, _id string) AnalysisData {
	id, err := strconv.Atoi(_id)
	if err != nil {
		return AnalysisData{nil, err.Error(), iris.StatusBadRequest}
	}
	res, err := getIssue(username, repo, id)
	if err != nil {
		return AnalysisData{nil, err.Error(), iris.StatusNotFound}
	}
	info, err := getRepo(username, repo)
	if err != nil {
		return AnalysisData{nil, err.Error(), iris.StatusNotFound}
	}
	opener := res["user"].(map[string]interface{})
	state := res["state"].(string)
	if res["state_reason"] != nil && res["state_reason"].(string) == "completed" {
		state = "completed"
	}
	return AnalysisData{
		Data: iris.Map{
			"username":  username,
			"repo":      repo,
			"id":        res["number"],
			"title":     res["title"],
			"state":     state,
			"date":      res["created_at"],
			"color":     GetColor(info["language"]),
			"labels":    formatLabels(res["labels"].([]interface{})),
			"comments":  res["comments"],
			"reactions": res["reactions"].(map[string]interface{})["total_count"],
			"opener": map[string]interface{}{
				"username": opener["login"],
				"avatar":   opener["avatar_url"],
				"image":    GetImage(opener["avatar_url"].(string)),
				"type":     opener["type"],
			},
			"description": MarkdownConvert(res["body"]),
		}, Code: iris.StatusOK,
	}
}

func AnalysisPullRequest(username string, repo string, _id string) AnalysisData {
	id, err := strconv.Atoi(_id)
	if err != nil {
		return AnalysisData{nil, err.Error(), iris.StatusBadRequest}
	}
	res, err := getPullRequest(username, repo, id)
	if err != nil {
		return AnalysisData{nil, err.Error(), iris.StatusNotFound}
	}
	info, err := getRepo(username, repo)
	if err != nil {
		return AnalysisData{nil, err.Error(), iris.StatusNotFound}
	}
	creator := res["user"].(map[string]interface{})
	state := res["state"].(string)
	if res["merged"].(bool) {
		state = "merged"
	}
	return AnalysisData{
		Data: iris.Map{
			"username":      username,
			"repo":          repo,
			"id":            res["number"],
			"title":         res["title"],
			"state":         state,
			"date":          res["created_at"],
			"color":         GetColor(info["language"]),
			"labels":        formatLabels(res["labels"].([]interface{})),
			"commits":       res["commits"],
			"additions":     ScaleConvert(res["additions"].(float64), false),
			"deletions":     ScaleConvert(res["deletions"].(float64), false),
			"changed_files": res["changed_files"],
			"comments":      res["comments"],
			"creator": map[string]interface{}{
				"username": creator["login"],
				"avatar":   creator["avatar_url"],
				"image":    GetImage(creator["avatar_url"].(string)),
				"type":     creator["type"],
			},
			"description": MarkdownConvert(res["body"]),
			"migration": map[string]interface{}{
				"base": res["base"].(map[string]interface{})["label"],
				"head": res["head"].(map[string]interface{})["label"],
			},
		}, Code: iris.StatusOK,
	}
}
