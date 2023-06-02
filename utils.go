package main

import (
	"encoding/json"
	"fmt"
	"github.com/sirupsen/logrus"
	"io"
	"net/http"
)

func Sum(array []int) int {
	total := 0
	for _, v := range array {
		total += v
	}
	return total
}

var sizeUnits = []string{"B", "KiB", "MiB", "GiB", "TiB", "PiB"}

func SizeConvert(size float64, idx int) string {
	if size <= 0 {
		return "0"
	}
	for idx < len(sizeUnits)-1 && size > 1024 {
		size /= 1024
		idx++
	}
	return fmt.Sprintf("%.1f %s", size, sizeUnits[idx])
}

var scaleUnits = []string{"", "k", "m"}

func ScaleConvert(n float64, useSmallScale bool) string {
	idx := 0
	condition := 100.0
	if !useSmallScale {
		condition = 1000.0
	}
	for idx < len(scaleUnits)-1 && n > condition {
		n /= 1000
		idx++
	}
	if idx == 0 {
		return fmt.Sprintf("%.0f", n)
	}
	return fmt.Sprintf("%.1f%s", n, scaleUnits[idx])
}

func Get(uri string, ptr interface{}) (err error) {
	req, err := http.NewRequest("GET", "https://api.github.com/"+uri, nil)
	if err != nil {
		return err
	}

	req.Header.Set("Accept", "application/json")
	req.Header.Set("Authorization", "Bearer "+GetToken())

	client := &http.Client{}
	resp, err := client.Do(req)
	if err != nil {
		return err
	}

	defer func(Body io.ReadCloser) {
		if err := Body.Close(); err != nil {
			logrus.Infoln(err)
		}
	}(resp.Body)

	if err = json.NewDecoder(resp.Body).Decode(ptr); err != nil {
		return err
	}
	return nil
}
