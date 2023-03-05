// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    pages: true,
    modules: ['@element-plus/nuxt'],
    routeRules: {
        "/": {
            ssr: false,
        },
        "/**": {
            cors: true,
            headers: {
                "Content-Type": "image/svg+xml",
            }
        }
    },
    runtimeConfig: {
        token: process.env.CODE_STATISTIC,
        expiration: 3600,
    },
    app: {
        head: {
            charset: "utf-8",
            title: "Code Statistic",
            meta: [
                {
                    name: "description",
                    content: "🔎 Dynamically analysis the code for each language in the repository/user and generate the results for your github account and repo README. ",
                }
            ],
        }
    }
})
