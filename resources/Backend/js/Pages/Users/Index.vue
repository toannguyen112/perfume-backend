<template>
  <div class="container">
    <Table
      :data="data"
      :canCreate="false"
      :filters="filters"
      :columns="columns"
      @search="getData"
      name="Thành viên"
    />
  </div>
</template>

<script>
import Authenticated from "@/Layouts/Authenticated";

export default {
  layout: Authenticated,

  data() {
    return {
      columns: [{ field: "name", label: "Họ tên" }],
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
