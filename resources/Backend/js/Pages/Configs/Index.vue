<template>
  <div class="container">
    <Table :data="data" :filters="filters" :columns="columns" @search="getData" />
  </div>
</template>

<script>
import Authenticated from '@/Layouts/Authenticated';
export default {
  layout: Authenticated,

  data() {
    return {
      columns: [
        { field: 'name', label: 'Tên' },
        { field: 'value', label: 'Giá trị' },
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
      axios.get(route(`sidebar.${this.currentModel()}.index`, route().params)).then((res) => {
        this.data = res.data;
      });
    },
  },
};
</script>
