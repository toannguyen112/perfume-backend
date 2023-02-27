<template>
  <VueMultiselect
    class="select-multiple"
    :placeholder="field.placeholder ?? 'Vui lòng chọn'"
    :label="selectLabelField"
    :track-by="selectTrackByField"
    :options="field.options"
    :model-value="selectedOptions"
    @update:model-value="selectChange"
    :loading="field.loading"
    :close-on-select="false"
    :clearOnSelect="false"
    :preselect-first="false"
    :searchable="true"
    :multiple="true"
  />
</template>

<script>
import VueMultiselect from 'vue-multiselect';
export default {
  components: {
    VueMultiselect,
  },
  props: ['value', 'field'],
  emits: ['change'],

  computed: {
    selectLabelField() {
      return this.field.config && this.field.config.label ? this.field.config.label : 'label';
    },

    selectTrackByField() {
      return this.field.config && this.field.config.trackBy ? this.field.config.trackBy : 'id';
    },

    selectedOptions() {
      if (this.value === null || this.value === undefined) return [];
      const defaultValue = this.value.map((x) => x.toString());

      return this.field.options.filter((x) => defaultValue.includes(x[this.selectTrackByField].toString()));
    },
  },

  methods: {
    selectChange(items) {
      if (this.field.config && this.field.config.getObject) {
        this.$emit('change', items);
      } else {
        let selectedOptions = items.map((x) => x[this.selectTrackByField]);
        this.$emit('change', selectedOptions);
      }
    },
  },
};
</script>
<style lang="scss">
.select-full .multiselect__tags-wrap {
  @apply flex flex-col;
}
</style>
