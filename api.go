package main

import (
	"encoding/json"
	"github.com/sirupsen/logrus"
	"io"
	"net/http"
	"os"
	"strings"
)

func GetToken() []string {
	data := os.Getenv("TOKEN")
	return strings.Split(data, "|")
}

func Get(uri string, token string) (res map[string]interface{}, err error) {
	req, err := http.NewRequest("GET", "https://api.github.com/"+uri, nil)
	if err != nil {
		return nil, err
	}

	req.Header.Set("Accept", "application/json")
	req.Header.Set("Authorization", "Bearer "+token)

	client := &http.Client{}
	resp, err := client.Do(req)
	if err != nil {
		return nil, err
	}

	defer func(Body io.ReadCloser) {
		err := Body.Close()
		if err != nil {
			logrus.Infoln()
		}
	}(resp.Body)

	var data map[string]interface{}
	err = json.NewDecoder(resp.Body).Decode(&data)
	if err != nil {
		return nil, err
	}
	return data, nil
}
