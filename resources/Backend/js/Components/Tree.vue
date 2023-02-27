<template>
  <Draggable
    tag="transition-group"
    :component-data="{
      tag: 'ul',
      type: 'transition-group',
    }"
    :modelValue="value"
    @update:modelValue="$emit('change', $event)"
    item-key="id"
    handle=".handle"
    :animation="200"
    group="category"
    :disabled="false"
    ghostClass="ghost"
    class="space-y-2"
  >
    <template #item="{ element }">
      <li class="flex-col space-y-2 select-none">
        <div
          class="flex p-2 border rounded-sm cursor-pointer bg-gray-50 handle"
          @click="$parent.$emit('selectedItem', $event)"
        >
          <span class="flex-1">
            {{ element.name }}
          </span>
        </div>
        <TreeViewItemV2
          v-if="currentLevel < 3"
          :level="currentLevel + 1"
          class="ml-8"
          v-model="element.items"
          @change="$emit('change', $event)"
        />
      </li>
    </template>
  </Draggable>
</template>
<script>
import Draggable from 'vuedraggable';

export default {
  props: ['value', 'field', 'level'],
  emits: ['change'],
  components: {
    Draggable,
  },
  computed: {
    currentLevel() {
      return this.level ?? 1;
    },
  },
};
</script>
<style lang="scss">
.ghost {
  @apply opacity-50 border-t;
}
</style>
