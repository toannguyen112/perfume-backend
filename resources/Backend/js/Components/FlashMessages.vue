<template>
  <div v-if="flash.success" class="flex items-center justify-between mb-6 text-white bg-green-500 rounded">
    <div class="flex items-center">
      <Icon name="check" />
      <div class="py-4 text-sm font-medium">
        {{ flash.success }}
      </div>
    </div>
    <button type="button" class="p-2 mr-2 group" @click="this.$page.props.flash.success = null">
      <Icon name="close" />
    </button>
  </div>

  <ul
    v-if="!flash.error && hasErrors"
    class="px-6 py-4 mb-6 text-sm text-white list-disc list-inside bg-red-400 rounded"
  >
    <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
  </ul>
  <details v-else-if="flash.error && !hasErrors" class="py-4 mb-6 text-sm text-white bg-red-400 rounded">
    <summary class="items-center justify-between px-3 font-medium flex-inline">
      <span class="cursor-pointer">
        {{ flash.error }}
      </span>
      <button
        type="button"
        class="float-right p-2"
        @click="(this.$page.props.flash.error = null), (this.$page.props.errors = [])"
      >
        <Icon name="close" />
      </button>
    </summary>
    <ul class="px-6 mt-2 list-disc list-inside" v-if="hasErrors">
      <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
    </ul>
  </details>
</template>

<script>
export default {
  computed: {
    flash() {
      return this.$page.props.flash;
    },
    errors() {
      return this.$page.props.errors;
    },
    hasErrors() {
      return Object.keys(this.errors).length > 0;
    },
  },
};
</script>
