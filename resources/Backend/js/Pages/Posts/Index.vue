<template>
  <div class="container">
    <Table
      :canImport="false"
      :data="data"
      :filters="filters"
      :columns="columns"
      @search="getData"
      name="Bài viết"
      idBy="id"
    />
  </div>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated";
export default {
  layout: Authenticated,

  data() {
    return {
      columns: [
        { field: "title", label: "Tên" },
        { field: "author", label: "Tác giả" },
        {
          field: "status",
          label: "Trạng thái",
          format: function (value) {
            return value === "ACTIVE" ? "Hiển thị" : "Ẩn";
          },
        },
        { field: "formatted_updated_at", label: "Ngày cập nhật" },
        { field: "formatted_created_at", label: "Ngày tạo" },
      ],
      data: {},
      filters: {
        search: null,
      },
    };
  },

  created() {
    this.getData();
  },

  methods: {
    getData() {
      axios
        .get(route(`sidebar.${this.currentModel()}.index`, route().params))
        .then((res) => {
          this.data = res.data;
        });
    },
  },
};
</script>
