<script setup lang="ts">
</script>
<template>
  <t-card bordered class="card">
    <t-form class="form">
      <t-tabs :default-value="1" class="tab">
        <t-tab-panel :value="1" label="User"><br>
          <t-form-item label="User">
            <t-input-adornment prepend="github.com/">
              <t-input placeholder="username" />
            </t-input-adornment>
          </t-form-item>
        </t-tab-panel>
        <t-tab-panel :value="2" label="Repo"><br>
          <t-form-item label="User">
            <t-input-adornment prepend="github.com/">
              <t-input placeholder="username" />
            </t-input-adornment>
          </t-form-item>
          <t-form-item label="Repo">
            <t-input placeholder="repository" />
          </t-form-item>
        </t-tab-panel>
        <t-tab-panel :value="3" label="Release">
        </t-tab-panel>
      </t-tabs>
      <t-form-item label="Theme" name="theme" initial-data="dark">
        <t-select defaultValue="light">
          <t-option key="light" label="light" value="light" />
          <t-option key="dark" label="dark" value="dark" />
        </t-select>
      </t-form-item>
    </t-form>
    <t-button id="generate">Generate</t-button>
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
  min-width: 220px;
  max-width: 550px;
}

.card {
  width: 100%;
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

#generate {
  margin: 0 auto;
}

#preview {
  width: 100%;
}

.quote {
  width: 20px;
  color: black !important;
  float: right;
  text-decoration: none;
  transform: translate(5px, -5px);
}
</style>
