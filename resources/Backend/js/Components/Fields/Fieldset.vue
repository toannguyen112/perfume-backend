<template>
  <fieldset>
    <label v-if="field.label !== undefined && field.type !== 'checkbox' && field.type !== 'toggle'" class="block label">
      <span>{{ field.label }}</span>
      <span v-if="field.help" class="help">{{ field.help }}</span>
    </label>

    <template v-if="field.translate !== undefined">
      <div class="flex" :class="field.row ? 'space-x-2' : 'flex-col space-y-3'">
        <Field
          v-for="(locale, localeIndex) in locales"
          :key="localeIndex"
          class="flex-1"
          :field="field"
          :modelValue="modelValueTranslate(locale, field.translate)"
          @update:modelValue="onChangeTranslate($event, locale)"
          @validate="onValidate"
          ref="field"
        />
      </div>
    </template>
    <template v-else>
      <Field
        :field="field"
        :modelValue="modelValue"
        @update:modelValue="$emit('update:modelValue', $event)"
        @validate="onValidate"
        ref="field"
      />
    </template>

    <small
      class="flex items-center h-5 mt-1 text-sm text-gray-600"
      v-if="field.type === 'textarea' || field.type === 'richtext'"
    >
      {{ charCount }} characters, {{ wordCount }} words.
    </small>
    <small class="flex items-center h-5 mt-1 text-sm text-red-400" v-if="!isValid">
      {{ field.label }} không hợp lệ
    </small>
  </fieldset>
</template>
<script>
import Field from '@/Components/Fields/Field';
export default {
  components: {
    Field,
  },
  props: ['field', 'modelValue'],
  data() {
    return {
      isValid: true,
    };
  },
  computed: {
    locales() {
      return this.$page.props.config && this.$page.props.config.locales ? this.$page.props.config.locales : [];
    },
    wordCount() {
      if (typeof this.modelValue !== 'string') return 0;
      return this.modelValue.trim().split(/\s+/).length;
    },
    charCount() {
      if (!this.modelValue) return 0;
      return this.modelValue.length;
    },
  },

  methods: {
    onValidate(isValid) {
      this.isValid = isValid;
    },
    onChangeTranslate(currentValue, currentLocale) {
      let translations = [];

      for (const locale of this.locales) {
        let value = currentValue;

        if (currentLocale !== locale) {
          value = this.modelValueTranslate(locale, this.field.translate);
        }

        translations.push({
          locale: locale,
          [this.field.translate]: value,
        });
      }

      this.$emit('update:modelValue', translations);
    },
    modelValueTranslate(locale, field) {
      const locales = this.modelValue.find((x) => x.locale === locale);
      return locales && locales[field] ? locales[field] : null;
    },
  },
};
</script>
