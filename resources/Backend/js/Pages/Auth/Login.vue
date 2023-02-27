<template>
  <Head title="Đăng nhập" />

  <div class="mb-6 sm:mx-auto sm:w-full sm:max-w-md">
    <h2 class="text-3xl font-bold leading-9 text-center text-gray-900">Đăng nhập</h2>
  </div>
  <form @submit.prevent="submit" class="space-y-6">
    <ValidationErrors />
    <Fieldset
      v-model="form.email"
      :field="{
        label: 'Email',
      }"
    />
    <Fieldset
      v-model="form.password"
      :field="{
        label: 'Mật khẩu',
        type: 'password',
      }"
    />
    <Fieldset
      v-model="form.remember"
      :field="{
        label: 'Ghi nhớ tôi',
        type: 'checkbox',
      }"
    />
    <Button type="submit" :loading="form.processing" label="Đăng nhập" class="w-full" />
  </form>
</template>

<script>
import ValidationErrors from '@/Components/ValidationErrors.vue';
import Guest from '@/Layouts/Guest.vue';

import { Head, Link } from '@inertiajs/inertia-vue3';
export default {
  layout: Guest,

  components: {
    ValidationErrors,
    Head,
    Link,
  },

  props: {
    canResetPassword: Boolean,
    status: String,
  },

  data() {
    return {
      form: this.$inertia.form({
        email: 'admin@gmail.com',
        password: 'admin@gmail.com',
        remember: true,
      }),
    };
  },

  methods: {
    submit() {
      this.form
        .transform((data) => ({
          ...data,
          remember: this.form.remember ? 'on' : '',
        }))
        .post(this.route('login'), {
          onFinish: () => this.form.reset('password'),
        });
    },
  },
};
</script>
