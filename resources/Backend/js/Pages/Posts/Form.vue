<template>
  <FormPage :form="form" :rules="rules" name="Bài viết">
    <div class="space-y-6 card">
      <Fieldset
        v-model="form.title"
        :field="{
          rules: rules['title'],
          label: 'Tên bài viết',
        }"
      />
      <small v-if="form.id">
        <span v-for="(item, index) in form.url" :key="index">
          <a :href="item" target="_blank" class="link">{{ item }}</a
          ><br />
        </span>
      </small>
      <Fieldset
        v-model="form.author"
        :field="{
          rules: rules['author'],
          label: 'Tác giả',
        }"
      />
      <Fieldset
        v-model="form.description"
        :field="{
          rules: rules['description'],
          label: 'Mô tả',
          type: 'textarea',
        }"
      />
      <Fieldset
        v-model="form.summary"
        :field="{
          rules: rules['summary'],
          label: 'Nội dung chính',
          type: 'textarea',
        }"
      />
      <Fieldset
        v-model="form.content"
        :field="{
          rules: rules['content'],
          label: 'Nội dung',
          type: 'richtext',
        }"
      />
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
      <div class="card">
        <Fieldset
          v-model="form.categories"
          :field="{
            type: 'select_tree',
            label: 'Danh mục',
            placeholder: 'Danh mục',
            labelBy: 'title',
            keyBy: 'id',
            childrenBy: 'nodes',
            source: {
              model: 'PostCategory',
              method: 'getRoot',
              only: ['id', 'title', 'nodes'],
            },
          }"
        />
      </div>
      <div class="card">
        <Fieldset
          v-model="form.post_related_ids"
          :field="{
            type: 'select_source',
            label: 'Bài viết liên quan',
            placeholder: 'Bài viết liên quan',
            labelBy: 'title',
            keyBy: 'id',
            multiple: 'true',
            source: {
              model: 'Post',
              method: 'get',
              only: ['id', 'title'],
            },
          }"
        />
      </div>
      <div class="space-y-6 card">
        <Fieldset
          v-model="form.view"
          :field="{
            rules: rules['view'],
            label: 'Lượt xem',
            type: 'number',
            readonly: true,
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
      this.item.view = this.item.view ? this.item.view : 0;
      this.form = this.$inertia.form({ ...this.item });
    },
  },
};
</script>
