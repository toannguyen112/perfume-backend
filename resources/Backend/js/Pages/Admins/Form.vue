<template>
  <FormPage :form="form" :rules="rules" name="Tài khoản">
    <div class="space-y-6 card">
      <Fieldset
        v-model="form.name"
        :field="{
          rules: rules['name'],
          label: 'Tên',
        }"
      />
      <Fieldset
        v-model="form.email"
        :field="{
          rules: rules['email'],
          label: 'Email',
        }"
      />
      <Fieldset
        v-model="form.roles"
        :field="{
          label: 'Vai trò',
          type: 'select_source',
          source: {
            model: 'Role',
            method: 'get',
            only: ['id', 'name'],
          },
          labelBy: 'name',
          multiple: true,
        }"
      />
      <div class="flex space-x-2">
        <Fieldset
          class="flex-1"
          v-model="form.new_password"
          :field="{
            type: 'password',
            rules: rules['new_password'],
            label: 'Mật khẩu mới',
          }"
        />
        <Fieldset
          class="flex-1"
          v-model="form.new_password_confirmation"
          :field="{
            type: 'password',
            rules: rules['new_password_confirmation'],
            label: 'Xác nhận mật khẩu mới',
          }"
        />
      </div>
    </div>
  </FormPage>
</template>

<script>
import Authenticated from '@/Layouts/Authenticated';
import Form from '@/Components/Form';
import Fieldset from '@/Components/Fields/Fieldset';
import Checkbox from '@/Components/Checkbox';
import SelectSource from '@/Components/Fields/SelectSource';

const emptyForm = {
  name: null,
  new_password: null,
  new_password_confirmation: null,
};

export default {
  components: {
    Checkbox,
    Form,
    Fieldset,
    SelectSource,
  },
  layout: Authenticated,
  props: ['item', 'rules'],
  data() {
    return {
      form: this.$inertia.form({ ...emptyForm, ...this.item }),
    };
  },
};
</script>
