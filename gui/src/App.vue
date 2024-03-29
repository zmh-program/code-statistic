<script setup lang="ts">
import {reactive, ref} from "vue";

const link = ref('');
const tab = ref('user');
const theme = ref('light');
const loading = ref(true);

const user = reactive({
  username: '',
});
const repo = reactive({
  username: '',
  repo: '',
});
const contributor = reactive({
  username: '',
  repo: '',
  column: 6,
})
const release = reactive({
  username: '',
  repo: '',
  tag: 'latest'
})
const issue = reactive({
  username: '',
  repo: '',
  id: 1,
})
const pull = reactive({
  username: '',
  repo: '',
  id: 1,
})

function validate(map: Record<string, any>) {
  for (const key in map) {
    if (!map[key]) {
      return false;
    }
  }
  loading.value = true;
  return true;
}

function tabChanged(value: string) {
  tab.value = value;
}

function join(src: string) {
  return `https://stats.deeptrain.net` + src;
}

function generate() {
  switch (tab.value) {
    case 'user':
      if (!validate(user)) return;
      link.value = join(`/user/${user.username}?theme=${theme.value}`);
      break;
    case 'repo':
      if (!validate(repo)) return;
      link.value = join(`/repo/${repo.username}/${repo.repo}/?theme=${theme.value}`);
      break;
    case 'contributor':
      if (!validate(contributor)) return;
      link.value = join(`/contributor/${contributor.username}/${contributor.repo}/?column=${contributor.column}&theme=${theme.value}`);
      break;
    case 'release':
      if (!validate(release)) return;
      link.value = join(`/release/${release.username}/${release.repo}/?tag=${release.tag}&theme=${theme.value}`);
      break;
    case 'issue':
      if (!validate(issue)) return;
      link.value = join(`/issue/${issue.username}/${issue.repo}/${issue.id}?theme=${theme.value}`);
      break;
    case 'pull':
      if (!validate(pull)) return;
      link.value = join(`/pull/${pull.username}/${pull.repo}/${pull.id}?theme=${theme.value}`);
      break;
  }
}
</script>
<template>
  <t-card bordered class="card">
    <t-form class="form">
      <t-tabs default-value="user" class="tab" :onChange="tabChanged">
        <t-tab-panel value="user" label="User"><br>
          <t-form-item label="User">
            <t-input-adornment prepend="github.com/">
              <t-input placeholder="username" v-model="user.username" />
            </t-input-adornment>
          </t-form-item>
        </t-tab-panel>
        <t-tab-panel value="repo" label="Repo"><br>
          <t-form-item label="User">
            <t-input-adornment prepend="github.com/">
              <t-input placeholder="username" v-model="repo.username" />
            </t-input-adornment>
          </t-form-item>
          <t-form-item label="Repo">
            <t-input placeholder="repository" v-model="repo.repo" />
          </t-form-item>
        </t-tab-panel>
        <t-tab-panel value="contributor" label="Contributor"><br>
          <t-form-item label="User">
            <t-input-adornment prepend="github.com/">
              <t-input placeholder="username" v-model="contributor.username" />
            </t-input-adornment>
          </t-form-item>
          <t-form-item label="Repo">
            <t-input placeholder="repository" v-model="contributor.repo" />
          </t-form-item>
          <t-form-item label="Column">
            <t-input placeholder="column" v-model="contributor.column" />
          </t-form-item>
        </t-tab-panel>
        <t-tab-panel value="release" label="Release"><br>
          <t-form-item label="User">
            <t-input-adornment prepend="github.com/">
              <t-input placeholder="username" v-model="release.username" />
            </t-input-adornment>
          </t-form-item>
          <t-form-item label="Repo">
            <t-input placeholder="repository" v-model="release.repo" />
          </t-form-item>
          <t-form-item label="Tag">
            <t-input placeholder="tag" v-model="release.tag" />
          </t-form-item>
        </t-tab-panel>
        <t-tab-panel value="issue" label="Issue"><br>
          <t-form-item label="User">
            <t-input-adornment prepend="github.com/">
              <t-input placeholder="username" v-model="issue.username" />
            </t-input-adornment>
          </t-form-item>
          <t-form-item label="Repo">
            <t-input placeholder="repository" v-model="issue.repo" />
          </t-form-item>
          <t-form-item label="Id">
            <t-input placeholder="id" v-model="issue.id" />
          </t-form-item>
        </t-tab-panel>
        <t-tab-panel value="pull" label="Pull Request"><br>
          <t-form-item label="User">
            <t-input-adornment prepend="github.com/">
              <t-input placeholder="username" v-model="pull.username" />
            </t-input-adornment>
          </t-form-item>
          <t-form-item label="Repo">
            <t-input placeholder="repository" v-model="pull.repo" />
          </t-form-item>
          <t-form-item label="Id">
            <t-input placeholder="id" v-model="pull.id" />
          </t-form-item>
        </t-tab-panel>
      </t-tabs>
      <t-form-item label="Theme" name="theme" initial-data="dark">
        <t-select defaultValue="light" class="select" v-model="theme">
          <t-option key="light" label="light" value="light" />
          <t-option key="dark" label="dark" value="dark" />
        </t-select>
      </t-form-item>
    </t-form>
    <t-button class="generate" @click="generate">Generate</t-button>
    <a class="quote" href="https://github.com/zmh-program/code-statistic">
      <svg role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>GitHub</title><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
    </a>
    <t-card class="response">
      <t-loading class="loading" v-if="loading" />
      <t-input v-else v-model="link" readonly />
      <img :src="link" alt="" @load="loading = false">
    </t-card>
  </t-card>
</template>

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

* {
  font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

#app {
  position: absolute;
  top: 8px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin: 30px;
  width: 80%;
  height: max-content;
  min-width: 220px;
  max-width: 550px;
}
.t-input-adornment {
  width: 100%;
}

.card {
  width: max-content;
  padding-bottom: 12px;
}
.card .t-card__body {
  display: grid;
  grid-gap: 46px;
}

.form {
  width: 100%;
}

.tab {
  width: 100%;
  margin: 20px 0;
}

.generate {
  margin: 0 auto;
}

.response {
  width: 100%;
  min-height: 120px;
  height: max-content;
}

.response .t-card__body {
  grid-gap: 2px;
}
.response .loading {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

@keyframes fadeInAnimation {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

.response img {
  margin-top: 12px;
  width: 100%;
  animation: fadeInAnimation ease .25s;
}

.quote {
  position: absolute;
  width: 20px;
  top: 24px;
  right: 24px;
  color: black !important;
  text-decoration: none;
  transform: translate(5px, -5px);
}

.select * {
  font-family: 'Open Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

@media (max-width: 960px) {
  #app {
    scale: .65;
    width: 200%;
  }
}
</style>
