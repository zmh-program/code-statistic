export const token: string | undefined = process.env.CODE_STATISTIC; // GitHub Access Token (Minimum permissions). Increase QPS of GitHub APIs.
export const expiration: number = 3600;  // expiration second
export const requires: string[] = ["*"]; // CODE STATISTIC can only be parsed for allowed users. ( * indicates that all users are allowed )
export const port: number =  8000; // server port
