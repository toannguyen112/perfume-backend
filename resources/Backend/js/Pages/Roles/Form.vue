<template>
  <FormPage :form="form" :rules="rules" name="Vai Trò">
    <div class="card">
      <Fieldset
        v-model="form.name"
        :field="{
          label: 'Tên vai trò',
        }"
      />
    </div>
    <div class="space-y-6 card">
      <Fieldset
        v-model="form.role_permissions"
        :field="{
          type: 'checkbox_tree',
          options: typePermissions,
          label: 'Danh sách quyền',
        }"
      />
    </div>
  </FormPage>
</template>

<script>
import Authenticated from '@/Layouts/Authenticated';
import Form from '@/Components/Form';
import Fieldset from '@/Components/Fields/Fieldset';

const emptyForm = {
  name: null,
};

export default {
  components: {
    Form,
    Fieldset,
  },
  layout: Authenticated,
  props: ['item', 'rules'],
  data() {
    return {
      typePermissions: [],
      form: this.$inertia.form({ ...emptyForm, ...this.item }),
    };
  },
  created() {
    this.getPermissionsList();
  },

  methods: {
    getPermissionsList() {
      axios
        .post(
          route('helper.model-data', {
            model: 'Permission',
            method: 'getPermissionList',
          })
        )
        .then((res) => {
          this.typePermissions = res.data;
        });
    },
  },
};
</script>
