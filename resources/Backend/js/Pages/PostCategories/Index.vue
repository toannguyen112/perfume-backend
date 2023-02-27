<template>
  <div class="grid grid-cols-12 gap-6">
    <div class="col-span-5 p-3 bg-white rounded shadow">
      <Fieldset
        v-if="categories && categories.length"
        :field="{
          type: 'tree',
          maxLevel: 5,
          expandDefaultLevel: 2,
          nameBy: 'title',
          labelBy: 'title',
          childrenBy: 'nodes',
          options: categories,
          draggable: false,
        }"
      />
      <hr class="my-8" />
        <Button
          label="Thêm mới"
          size="sm"
          :href="route(`sidebar.post-categories.index`)"
        />
    </div>
    <div class="col-span-7">
      <FlashMessages />
      <Form :form="form" :rules="rules" v-slot="{ destroy }">
        <div class="space-y-6 card">
          <div class="flex justify-between w-full">
            <Button label="Lưu" @click="store()" />
          </div>
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
          <Fieldset
            v-model="form.parent_id"
            :field="{
              type: 'select_source',
              label: 'Danh mục cha',
              placeholder: 'Danh mục cha',
              labelBy: 'title',
              keyBy: 'id',
              multiple: false,
              source: {
                model: 'PostCategory',
                method: 'get',
                only: ['id', 'title'],
              },
            }"
          />
          <Fieldset
            v-if="form.id"
            v-model="form.id"
            disabled
            :field="{
              rules: rules['id'],
              label: 'ID',
            }"
          />
          <Fieldset
            v-model="form.title"
            :field="{
              rules: rules['parent_id'],
              label: 'Tên danh mục',
            }"
          />
          <small v-if="form.id">
            <span v-for="(item, index) in form.url" :key="index">
              <a :href="item" target="_blank" class="link">{{ item }}</a><br />
            </span>
          </small>
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
        <MetaData v-model="form" />
        <div class="flex mt-6">
          <Button label="Lưu" @click="store()" />
          <Button
            variant="link-danger"
            label="Xóa"
            class="ml-auto"
            @click="destroy()"
            v-if="form.id"
          />
        </div>
      </Form>
    </div>
  </div>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated";
import FlashMessages from "@/Components/FlashMessages";

export default {
  layout: Authenticated,
  props: ["item", "rules"],
  components: {
    FlashMessages,
  },

  data() {
    return {
      form: {},
      categories: [],
    };
  },

  created() {
    this.getData();
    this.initForm();
  },

  watch: {
    item() {
      this.initForm();
    },
  },

  mounted() {
    this.$bus.on("treeSelectedItem", (item) => {
      this.selectedItem(item);
    });
  },

  beforeUnmount() {
    this.$bus.off("treeSelectedItem");
  },

  methods: {
    getData() {
      axios
        .post(route(`helper.model-data`), {
          model: "PostCategory",
          method: "getRoot",
        })
        .then((res) => {
          this.categories = res.data;
        });
    },
    store() {
      this.form.parent_id =
        this.form.parent_id == null ? 0 : this.form.parent_id;

      const url = route(`sidebar.${this.currentModel()}.store`, {
        id: this.form.id,
      });

      this.form.post(url, { onSuccess: () => this.getData() });
    },
    destroy() {
      if (confirm("Bạn có chắc muốn xóa")) {
        const url = route(`sidebar.${this.currentModel()}.destroy`, {
          id: this.form.id,
        });
      }
    },
    selectedItem(item) {
      this.form = {
        ...this.form,
        ...item,
      };
    },
    initForm() {

      this.item.view = this.item.view ? this.item.view : 0;
      this.form = this.$inertia.form({ ...this.item });
    },
  },
};
</script>
