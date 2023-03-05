<style>
html, body {
  padding: 0;
  margin: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  height: 100%;
  overflow: auto;
  background: #fafcf1;
}

#__nuxt {
  position: absolute;
  top: 8px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin: 30px;
  width: 80%;
  min-width: 220px;
  max-width: 500px;
}

#generate {
  margin: 0 auto;
  transform: translateY(20px);
}

#preview {
  width: 100%;
}

.more {
  width: 20px;
  color: black !important;
  float: right;
  text-decoration: none;
  transform: translate(5px, -5px);
}
* {
  font-family: Consolas, Nunito, monospace, Serif;
}
</style>
<template>
  <el-card>
    <el-form label-width="auto">
      <el-form-item label="User"><el-input v-model="form.username"></el-input></el-form-item>
      <el-form-item label="Repo" v-if="isRepo"><el-input v-model="form.repo"></el-input></el-form-item>
      <el-form-item label="Type"><el-radio-group v-model="form.type"><el-radio-button border label="User" /><el-radio-button border label="Repo" /></el-radio-group></el-form-item>
      <el-form-item label="Dark"><el-switch v-model="form.dark"></el-switch></el-form-item>
      <el-form-item><el-button type="primary" @click="generate" id="generate">Generate</el-button></el-form-item>
    </el-form>
    <a class="more" href="https://github.com/zmh-program/code-statistic"><svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>GitHub</title><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg></a>
  </el-card>
  <el-card style="display: grid;">
    <template v-if="!link"><el-empty image-size=80></el-empty></template>
    <template v-else>
      <el-radio-group class="type" v-model="type"><el-radio-button border label="URL" /><el-radio-button border label="Markdown" /><el-radio-button border label="HTML" /></el-radio-group>
      <el-input readonly v-model="link"></el-input>
      <img id="preview" :src="link" alt>
    </template>
  </el-card>
</template>
<script setup lang="ts">
import { ElMessage } from 'element-plus';
const form = ref({
  username: "",
  repo: "",
  type: "User",
  dark: false,
})
const link = ref("");
const type = ref("url");
const isRepo = computed((): boolean => form.value.type !== "User");
const unexpected = (msg: string): any => ElMessage({
  grouping: true,
  message: msg,
  type: "error",
})

function generate(): void {
  const username = form.value.username.trim(),
      repo = form.value.repo.trim(),
      dark = !! form.value.dark;

  switch (true) {
    case (! username):
      unexpected("Oops, username is empty.");
      break;
    case (isRepo.value && ! repo):
      unexpected("Oops, repo is empty.");
      break;
    default:
      link.value = location.origin +
          ((!isRepo.value) ? `/user/${username}/` : `/repo/${username}/${repo}`) +
          (dark ? "?theme=dark" : "");
      break;
  }
}
</script>