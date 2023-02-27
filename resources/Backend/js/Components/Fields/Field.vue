<template>
  <template
    v-if="
      field.type === undefined ||
      field.type === 'text' ||
      field.type === 'password' ||
      field.type === 'number' ||
      field.type === 'datetime-local' ||
      field.type === 'date' ||
      field.type === 'time'
    "
  >
    <input
      class="block w-full input"
      :class="{ 'bg-gray-100': field.readonly }"
      :type="field.type ?? 'text'"
      :readonly="field.readonly ?? false"
      :placeholder="field.placeholder ?? (!field.readonly && field.label ? `Nhập ${field.label.toLowerCase()}` : '')"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      @blur="validateField($event.target.value)"
      :max="field.max ?? ''"
      :min="field.min ?? ''"
    />
  </template>
  <template v-else-if="field.type === 'select_single'">
    <select
      class="select"
      :class="{
        'text-gray-600': !modelValue || (modelValue.length === 0 && field.placeholder),
      }"
      :readonly="field.readonly ?? false"
      @input="$emit('update:modelValue', $event.target.value)"
    >
      <option v-if="field.placeholder" disabled value="" :selected="!modelValue">
        {{ field.placeholder }}
      </option>
      <option
        v-for="(item, id) in field.options"
        :key="id"
        :value="item.id !== undefined ? item.id : id"
        :selected="optionSelected(item, id)"
      >
        {{ item.name ? item.name : item }}
      </option>
    </select>
  </template>
  <template v-else-if="field.type === 'select_multiple'">
    <SelectMultiple :value="modelValue" @change="inputChange" :field="field" />
  </template>
  <template v-else-if="field.type === 'select_source'">
    <SelectSourceV2 :value="modelValue" @change="inputChange" :field="field" />
  </template>
  <template v-else-if="field.type === 'textarea'">
    <textarea
      :rows="field.rows ?? 3"
      :placeholder="field.placeholder ?? (!field.readonly && field.label ? `Nhập ${field.label.toLowerCase()}` : '')"
      class="block w-full input"
      :readonly="field.readonly ?? false"
      :value="modelValue"
      @blur="inputChange($event.target.value)"
    >
    </textarea>
  </template>
  <template v-else-if="field.type === 'richtext'">
    <Richtext :value="modelValue" @change="inputChange" :field="field" />
  </template>
  <template v-else-if="field.type === 'toggle'">
    <div class="flex items-center space-x-4">
      <label class="relative inline-block w-12 h-6 overflow-hidden outline-none cursor-pointer toggle ring-transparent">
        <input
          type="checkbox"
          :checked="modelValue"
          @change="$emit('update:modelValue', !!$event.target.checked)"
          class="w-0 h-0 outline-none opacity-0 peer"
          v-if="field.options === undefined"
          :id="fieldId"
        />
        <input
          v-else
          type="checkbox"
          :checked="field.options.indexOf(modelValue) === 1"
          @change="toggleChange"
          class="w-0 h-0 outline-none opacity-0 peer"
        />
        <span
          class="slider rounded-full absolute inset-0 transition-colors duration-400 bg-gray-200 cursor-pointer before:block before:content-[''] before:absolute before:bg-white before:w-4 before:h-4 before:transition before:rounded-full peer-checked:bg-primary ring-transparent"
        ></span>
      </label>
      <label class="font-medium text-gray-800 cursor-pointer" :for="fieldId">
        {{ field.label }}
      </label>
    </div>
  </template>
  <template v-else-if="field.type === 'radio'">
    <template v-for="(item, key) of field.options" :key="key">
      <div class="radio">
        <input
          type="radio"
          :value="item.id !== undefined ? item.id : key"
          :id="`${fieldId}_${key}`"
          :name="fieldId"
          :checked="item.id && modelValue && item.id.toString() === modelValue.toString()"
          @input="$emit('update:modelValue', $event.target.value)"
        />
        <label :for="`${fieldId}_${key}`">
          {{ item.name ? item.name : item }}
        </label>
        <span></span>
      </div>
    </template>
  </template>
  <template v-else-if="field.type === 'checkbox'">
    <div class="checkbox">
      <input
        type="checkbox"
        :id="fieldId"
        :name="fieldId"
        :checked="modelValue && modelValue.toString()"
        @change="$emit('update:modelValue', !!$event.target.checked)"
      />
      <label :for="fieldId">
        {{ field.label }}
      </label>
      <span></span>
    </div>
  </template>
  <template v-else-if="field.type === 'checkbox_list'">
    <div class="checkbox">
      <input
        type="checkbox"
        :id="fieldId"
        :name="fieldId"
        :checked="modelValue"
        @change="$emit('update:modelValue', !!$event.target.checked)"
      />
      <label :for="fieldId">
        {{ field.options.label }}
      </label>
      <span></span>
    </div>
  </template>
  <template v-else-if="field.type === 'checkbox_tree'">
    <template v-for="(item, index) in field.options" :key="index">
      <div>
        <div class="checkbox">
          <input
            type="checkbox"
            :id="`${fieldId}_${index}`"
            :value="item.id"
            :indeterminate.prop="containsSome(modelValue, pluck(item.items ?? []))"
            v-model="modelValue"
            @input="onChangeCheckbox($event, item)"
          />
          <label :for="`${fieldId}_${index}`">
            {{ item.name }}
          </label>
          <span></span>
        </div>
        <div v-if="item.items" class="ml-12">
          <div class="checkbox" v-for="(child, childIndex) in item.items" :key="childIndex">
            <input
              type="checkbox"
              :id="`${fieldId}_${index}_${childIndex}`"
              :value="child.id"
              v-model="modelValue"
              @input="onChangeCheckbox($event, child, pluck(item.items))"
            />
            <label :for="`${fieldId}_${index}_${childIndex}`">
              {{ child.name }}
            </label>
            <span></span>
          </div>
        </div>
      </div>
    </template>
  </template>
  <template v-else-if="field.type === 'media_upload'">
    <!-- <div> -->
    <InputFile :value="modelValue" @change="inputChange" :field="field" />
    <!-- <div v-if="field.description" v-html="field.description" class="mt-2 text-xs font-medium text-gray-500"></div>
    </div> -->
  </template>
  <template v-else-if="field.type === 'tree'">
    <TreeViewItemV2 v-model="modelValue" :field="field" />
  </template>
  <template v-else-if="field.type === 'select_tree'">
    <SelectTree :value="modelValue" @change="inputChange" :field="field" />
  </template>
</template>

<script>
import SelectSingle from '@/Components/Fields/SelectSingle';
import SelectMultiple from '@/Components/Fields/SelectMultiple';
import Richtext from '@/Components/Fields/Richtext';
import InputFile from '@/Components/Fields/InputFile';
import TreeViewItemV2 from '@/Components/TreeViewItemV2';
import SelectTree from '@/Components/Fields/SelectTree';

import { validateField } from '@/validator.js';

export default {
  inheritAttrs: false,
  components: {
    SelectSingle,
    SelectMultiple,
    Richtext,
    InputFile,
    TreeViewItemV2,
    SelectTree,
  },
  props: ['field', 'modelValue'],
  emits: ['update:modelValue', 'validate'],

  computed: {
    fieldId() {
      return Math.random().toString(36).substr(2, 9);
    },
  },

  created() {
    if (this.modelValue) {
      this.validateField();
    } else if (this.field.type === 'select_single' && !this.field.placeholder && this.field.options[0]) {
      this.inputChange(this.field.options[0].id.toString());
    }
  },

  methods: {
    inputChange(value) {
      this.$emit('update:modelValue', value);
      this.validateField(value);
    },
    toggleChange(event) {
      let value = this.field.options[0];
      if (!!event.target.checked) {
        value = this.field.options[1];
      }
      this.$emit('update:modelValue', value);
    },
    validateField(value = null) {
      const currentValue = value !== null ? value : this.modelValue;
      const fieldIsValid = validateField(currentValue, this.field.rules);
      this.$emit('validate', fieldIsValid);
    },
    containsSome(parents, childs) {
      const hasSome = parents.some((id) => childs.includes(id));
      const hasAll = this.containsAll(parents, childs);
      return hasSome && !hasAll;
    },
    containsAll(parents, childs) {
      return childs.every((i) => parents.includes(i));
    },
    optionSelected(item, id) {
      const value = item.id !== undefined ? item.id : id;
      return value.toString() === (this.modelValue ? this.modelValue.toString() : '');
    },
    onChangeCheckbox(event, item, siblingIds = []) {
      let values = [...this.modelValue];
      const currentId = parseInt(event.target.value);

      if (event.target.checked) {
        values.push(currentId);
      } else {
        const index = values.indexOf(currentId);
        values.splice(index, 1);
      }

      if (item.items && item.items.length > 0) {
        const childIds = this.pluck(item.items);
        if (event.target.checked) {
          values = values.concat(childIds);
        } else {
          childIds.push(currentId);
          values = this.modelValue.filter((id) => !childIds.includes(id));
        }
      } else if (item.parent_id) {
        if (event.target.checked && this.containsAll(values, siblingIds)) {
          values.push(parseInt(item.parent_id));
        } else if (!event.target.checked) {
          const parentIndex = values.indexOf(parseInt(item.parent_id));
          parentIndex > -1 && values.splice(parentIndex, 1);
        }
      }

      this.$emit('update:modelValue', values);
    },
  },
};
</script>
