<template>
  <Authenticated>
    <div class="container py-4">
      <Table :data="data" :filters="filters" :columns="columns" name="Tài khoản" />
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
      columns: [
        { field: 'name' },
        { field: 'email' },
        {
          field: 'role_name',
          label: 'Vai trò',
          format: function (value) {
            return value.join(', ');
          },
        },
        {
          field: 'formatted_email_verified_at',
          label: 'Email verified at',
        },
        { field: 'formatted_created_at', label: 'Created at' },
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
      axios.get(route(`sidebar.${this.currentModel()}.index`), route().params).then((res) => {
        this.data = res.data;
      });
    },
  },
};
</script>
