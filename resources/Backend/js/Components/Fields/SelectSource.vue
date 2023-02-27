<template>
  <div>
    <label v-if="label" class="label">{{ label }}</label>

    <VueMultiselect
      :placeholder="placeholder ?? 'Vui lòng chọn'"
      :label="selectLabelField"
      track-by="id"
      :options="options"
      :model-value="selectedOptions"
      @update:model-value="selectChange"
      :loading="loading"
      :close-on-select="!isMultiple"
      :clearOnSelect="false"
      :preselect-first="false"
      :searchable="true"
      :multiple="isMultiple"
    />
  </div>
</template>

<script>
import VueMultiselect from 'vue-multiselect';
export default {
  components: {
    VueMultiselect,
  },
  props: ['modelValue', 'source', 'placeholder', 'config', 'multiple', 'label'],
  emits: ['update:modelValue'],

  computed: {
    selectLabelField() {
      return this.source.label && this.source.label ? this.source.label : 'label';
    },
    isMultiple() {
      return this.multiple !== undefined ? this.multiple : true;
    },
    selectedOptions() {
      if (!this.isMultiple) {
        return this.options.find((x) => x.id === this.modelValue);
      }

      if (this.modelValue === null || this.modelValue === undefined) return [];

      let defaultValue = [];

      if (this.modelValue.length > 0 && typeof this.modelValue[0] === 'object') {
        defaultValue = this.modelValue.map((x) => x.id.toString());
      } else {
        defaultValue = this.modelValue.map((x) => x.toString());
      }

      return this.options.filter((x) => defaultValue.includes(x.id.toString()));
    },
  },

  data() {
    return {
      loading: false,
      options: [],
    };
  },

  created() {
    this.loading = true;
    this.fetchSource();
  },

  methods: {
    fetchSource() {
      axios.post(route('helper.model-data'), this.source).then((res) => {
        this.options = res.data;
        this.loading = false;
      });
    },
    selectChange(selected) {
      if (!this.isMultiple) {
        this.$emit('update:modelValue', selected.id);
      } else {
        this.$emit('update:modelValue', selected);
      }
    },
  },
};
</script>
