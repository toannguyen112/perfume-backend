<template>
  <VueMultiselect
    placeholder="Vui lòng chọn"
    :label="selectLabelField"
    :track-by="selectTrackByField"
    :options="field.config.options"
    :model-value="selectedOptions"
    @update:model-value="selectChange"
    :close-on-select="true"
    :show-labels="false"
    :searchable="true"
    :preselect-first="preselectFirst"
    :loading="field.isLoading"
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

  data() {
    return {
      defaultConfig: this.$page.props.cms_site.config.fields.select.config,
    };
  },

  computed: {
    isSelectSourceSimple() {
      return this.field.config.source !== undefined && this.field.config.source.type === 'simple';
    },
    isSelectSourceDirectory() {
      return this.field.config.source !== undefined && this.field.config.source.type === 'directory';
    },
    selectLabelField() {
      let selectLabelField = '';

      if (this.field.config.label) {
        return this.field.config.label;
      }
      const hasOptions = this.field.config.options.length > 0;
      const firstOption = this.field.config.options[0];

      if (hasOptions && typeof firstOption === 'object') {
        let labels = ['title', 'name', 'label'];

        for (let index = 0; index < labels.length; index++) {
          const label = labels[index];

          if (firstOption[label] !== undefined || index === labels.length - 1) {
            selectLabelField = label;
            break;
          }
        }
      }

      return selectLabelField;
    },
    selectTrackByField() {
      let selectTrackByField = '';

      if (this.field.config.trackBy) {
        return this.field.config.trackBy;
      }

      const hasOptions = this.field.config.options.length > 0;
      const firstOption = this.field.config.options[0];
      if (hasOptions && typeof firstOption === 'object') {
        selectTrackByField = 'cmscode';
      }

      return selectTrackByField;
    },
    preselectFirst() {
      return !!this.field.config.preselectFirst;
    },
    selectedOptions() {
      if (this.value === null) return null;
      if (this.field.config.getObject || this.isSelectSourceSimple) return this.value;
      if (this.field.config.options === undefined) return [];

      return this.field.config.options.find((x) => x[this.selectTrackByField] === this.value);
    },
  },

  methods: {
    selectChange(item) {
      if (this.field.config.getObject) {
        this.$emit('change', item);
      } else if (this.isSelectSourceSimple) {
        this.$emit('change', item);
      } else {
        const selectedOption = item ? item[this.selectTrackByField] : item;
        this.$emit('change', selectedOption);
      }
    },
  },
};
</script>
