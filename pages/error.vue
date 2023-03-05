<style scoped>
.header {
  font: 600 18px 'Segoe UI', Ubuntu, Sans-Serif;
  animation: fadeInAnimation 0.8s ease-in-out forwards;
}
@supports(appearance: auto) {
  .header { font-size: 15px; }
}
#rect-mask rect{
  animation: fadeInAnimation 1s ease-in-out forwards;
}

@keyframes fadeInAnimation {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
</style>
<template>
  <svg width="450" height="195" viewBox="0 0 660 216" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="descId">
    <title id="titleId">Error</title>
    <desc id="descId">Code Statistic</desc>
    <rect data-testid="card-bg" x="0.5" y="0.5" rx="4.5" height="99%" stroke="#e4e2e2" width="659" :fill="bg" stroke-opacity="1"/>
    <g data-testid="card-title" transform="translate(200, 108)"><g transform="translate(0, 0)"><text x="0" y="0" class="header" :fill="header" data-testid="header">Sorry, there is something wrong...</text></g></g>
  </svg>
</template>

<script setup lang="ts">
const dark: boolean = useRoute().query.theme === "dark";
const bg: string = dark ? '#000' : '#fffefe', header: string = dark ? "#fff" : "#434d58";
</script>
