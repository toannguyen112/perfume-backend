<template>
  <Draggable
    tag="transition-group"
    :component-data="{
      tag: 'ul',
      type: 'transition-group',
    }"
    :modelValue="modelValue"
    @update:modelValue="$emit('update:modelValue', $event)"
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
          @click="$emit('loadCategoryForm', element)"
        >
          <span class="flex-1">
            {{ element.name }}
          </span>
          <span
            class="inline-flex px-2 py-1 mx-2 text-xs font-bold text-green-700 uppercase bg-green-200 rounded-full"
            v-if="element.is_featured === true || element.is_featured === 1"
          >
            Nổi bật
          </span>
          <span
            class="inline-flex px-2 py-1 mx-2 text-xs font-bold text-yellow-700 uppercase bg-yellow-200 rounded-full"
            v-if="element.is_favoured === true || element.is_favoured === 1"
          >
            Yêu thích
          </span>
        </div>
        <TreeViewItem
          v-if="level < 3"
          :level="level + 1"
          class="ml-8"
          v-model="element.items"
          @load-category-form="$emit('loadCategoryForm', $event)"
        />
      </li>
    </template>
  </Draggable>
</template>
<script>
import Draggable from 'vuedraggable';
import Fieldset from '@/Components/Fields/Fieldset';
import Button from '@/Components/Button';

export default {
  props: {
    modelValue: {
      type: Array,
    },
    level: {
      type: Number,
      default: 1,
    },
  },
  emits: ['update:modelValue', 'loadCategoryForm'],
  components: {
    Draggable,
    Fieldset,
    Button,
  },
};
</script>
<style lang="scss">
.ghost {
  @apply opacity-50 border-t;
}
</style>
