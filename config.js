module.exports = {
  token: process.env.CODE_STATISTIC, // GitHub Access Token (Minimum permissions). Increase QPS of GitHub APIs.
  expiration: 3600,  // expiration second
  requires: ["*"], // CODE STATISTIC can only be parsed for allowed users. ( * indicates that all users are allowed )
  port: 8000, // server port
  host: "localhost",
}
