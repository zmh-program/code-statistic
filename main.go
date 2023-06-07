package main

func main() {
	SetupLogger()
	ReadConfig()
	ReadToken()
	SetupCache()
	RunServer()
}
