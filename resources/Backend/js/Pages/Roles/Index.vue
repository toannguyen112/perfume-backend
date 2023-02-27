<template>
  <Authenticated>
    <div class="container py-4">
      <Table :data="data" :filters="filters" :columns="columns" name="Vai Trò" />
    </div>
  </Authenticated>
</template>

<script>
import Authenticated from '@/Layouts/Authenticated';
import Table from '@/Components/Table';

export default {
  components: {
    Authenticated,
    Table,
  },

  data() {
    return {
      columns: [{ field: 'name', label: 'Tên' }],
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
      axios.get(route(`sidebar.${this.currentModel()}.index`), route().params).then((res) => {
        this.data = res.data;
      });
    },
  },
};
</script>
