<template>
  <client-only>
    <svg-vue :icon="name" :class="$attrs.class"></svg-vue>
  </client-only>
</template>

<script>
export default {
  props: ["name"],
};
</script>
