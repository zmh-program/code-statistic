<style scoped>

</style>
<template>
{{ username }}
  {{ repo }}
</template>

<script setup lang="ts">
const params = useRoute().params,
    username: string | string[] = params.username,
    repo: string | string[] = params.repo;
</script>
