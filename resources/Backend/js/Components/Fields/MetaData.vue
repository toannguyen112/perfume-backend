<template>
  <div class="space-y-6 card">
    <label class="label">SEO</label>
    <Fieldset
      v-model="modelValue.meta_title"
      :field="{
        rules: 'string|max:170',
        label: 'Meta title',
      }"
    />
    <Fieldset
      :modelValue="modelValue.custom_slug"
      @update:modelValue="
        modelValue.custom_slug = slugify($event);
        $emit('update:modelValue', modelValue);
      "
      :field="{
        label: 'Custom Slug',
      }"
    />
    <Fieldset
      v-model="modelValue.meta_description"
      :field="{
        rules: 'string|max:255',
        label: 'Meta description',
        type: 'textarea',
      }"
    />
  </div>
</template>

<script>
import Form from '@/Components/Form';
import Fieldset from '@/Components/Fields/Fieldset';
import cloneDeep from 'lodash/cloneDeep';

export default {
  components: {
    Form,
    Fieldset,
  },
  props: ['modelValue'],
  emits: ['update:modelValue'],
  methods: {
    slugify(str, separator = '-') {
      return str
        .toLowerCase()
        .replace(/\t/g, '')
        .replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, 'a')
        .replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, 'e')
        .replace(/ì|í|ị|ỉ|ĩ/g, 'i')
        .replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, 'o')
        .replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, 'u')
        .replace(/ỳ|ý|ỵ|ỷ|ỹ/g, 'y')
        .replace(/đ/g, 'd')
        .replace(/\s+/g, separator)
        .replace(/[^A-Za-z0-9_-]/g, '')
        .replace(/-+/g, separator);
    },
  },
};
</script>
