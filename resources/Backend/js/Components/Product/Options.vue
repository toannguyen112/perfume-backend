<template>
  <div class="space-y-4 card">
    <label class="label">Thuộc tính</label>
    <div class="space-y-4">
      <div v-for="(option, index) in options" v-if="allOptions.length > 0" class="space-y-2">
        <Fieldset
          v-model="option.id"
          :field="{
            type: 'select_source',
            labelBy: 'name',
            options: allOptions,
            keyBy: 'id',
            multiple: false,
            taggable: true,
          }"
        />
        <Fieldset
          :modelValue="option.nodes"
          @update:modelValue="option.nodes = cloneItem($event)"
          :field="{
            type: 'select_source',
            labelBy: 'name',
            options: getChildNodes(option),
            taggable: true,
            multiple: true,
          }"
        />
      </div>

      <Button label="Thêm mới" variant="outline-secondary" @click="addOption" />
    </div>
  </div>
</template>
<script>
import cloneDeep from 'lodash/cloneDeep';
export default {
  props: ['options', 'allOptions'],
  emits: ['update:options'],
  methods: {
    addOption() {
      this.options.push({ id: null, nodes: [] });
      this.$emit('update:options', this.options);
    },

    cloneItem(item) {
      return cloneDeep(item);
    },

    getChildNodes(parent) {
      if (!parent) return [];

      const parentId = parent.id;
      const option = this.allOptions.find((x) => x.id === parentId);
      return option ? option.nodes : [];
    },
  },
};
</script>
