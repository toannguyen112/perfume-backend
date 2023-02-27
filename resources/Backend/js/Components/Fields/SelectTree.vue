<template>
  <Dropdown width="full" align="left">
    <template #trigger>
      <div class="relative flex">
        <input type="text" class="w-full input" :value="field.placeholder ?? field.label" readonly />
        <div
          class="absolute flex items-center justify-center w-6 h-6 text-xs font-medium text-white transform -translate-y-1/2 bg-blue-500 rounded-full right-2 top-1/2"
        >
          {{ value.length }}
        </div>
      </div>
    </template>
    <template #content>
      <TreeViewItemV2 v-model="value" :field="fieldConfig" />
    </template>
  </Dropdown>
</template>

<script>
import TreeViewItemV2 from '@/Components/TreeViewItemV2';
export default {
  components: {
    TreeViewItemV2,
  },
  props: ['value', 'field'],
  emits: ['change'],

  computed: {
    fieldConfig() {
      return {
        maxLevel: 10,
        selectable: true,
        multiple: true,
        ...this.field,
      };
    },
    keyBy() {
      return this.field.keyBy || 'id';
    },
    labelBy() {
      return this.field.labelBy || 'label';
    },
    childrenBy() {
      return this.field.childrenBy || 'children';
    },
  },

  mounted() {
    this.$bus.on('treeSelectedItem', (item) => {
      this.toggleItem(item);
    });
  },
  methods: {
    toggleItem(item) {
      let value = this.value;
      const itemIndex = this.value.findIndex((x) => x[this.keyBy] === item[this.keyBy]);
      const childIds = this.pluck(item[this.childrenBy], this.keyBy);

      if (itemIndex > -1) {
        value.splice(itemIndex, 1);
        value = value.filter((x) => childIds.indexOf(x[this.keyBy]) == -1);
      } else {
        value.push(item);
        value = value.concat(...item[this.childrenBy]);
      }

      this.$emit('change', value);
    },
  },
};
</script>
