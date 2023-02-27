<template>
  <FormPage :form="form" :rules="rules">
    <div class="space-y-6 card">
      <Fieldset v-model="form.name" :disabled="true" :field="{ rules: rules['name'], label: 'Name' }" />

      <Fieldset v-model="form.value" :field="{ rules: rules['value'], label: 'Value', type: 'textarea' }" />
    </div>
  </FormPage>
</template>

<script>
import Authenticated from '@/Layouts/Authenticated';

export default {
  layout: Authenticated,
  props: ['item', 'rules'],
  data() {
    return {
      form: null,
    };
  },

  created() {
    this.initForm();
  },

  watch: {
    item() {
      this.initForm();
    },
  },

  methods: {
    initForm() {
      this.form = this.$inertia.form({ ...this.item });
    },
  },
};
</script>
