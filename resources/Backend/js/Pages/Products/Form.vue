<template>
  <FormPage :form="form" :rules="rules" name="Sản phẩm">
    <div class="space-y-6 card">
      <Fieldset
        v-model="form.title"
        :field="{
          rules: rules['title'],
          label: 'Tiêu đề',
        }"
      />
      <small v-if="form.id">
        <span v-for="(item, index) in form.url" :key="index">
          <a :href="item" target="_blank" class="link">{{ item }}</a
          ><br />
        </span>
      </small>
    </div>
    <MetaData v-model="form" />
    <template #right>
      <div class="card">
        <Fieldset
          v-model="form.status"
          :field="{
            type: 'radio',
            label: 'Trạng thái',
            options: [
              { id: 'ACTIVE', name: 'Hiển thị' },
              { id: 'INACTIVE', name: 'Ẩn' },
            ],
          }"
        />
      </div>
      <div class="space-y-3 card">
        <label class="label">Hình ảnh</label>
        <Fieldset
          v-model="form.thumbnail"
          :field="{
            type: 'media_upload',
            folder: currentModel(),
          }"
        />
      </div>
    </template>
  </FormPage>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated";

export default {
  layout: Authenticated,
  remember: "form",
  props: ["item", "rules"],
  data() {
    return {
      form: {},
    };
  },

  created() {
    this.initForm();
  },

  watch: {
    item(value) {
      value.id && this.initForm();
    },
  },

  methods: {
    initForm() {
      this.form = this.$inertia.form({ ...this.item });
    },
  },
};
</script>
