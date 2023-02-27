<template>
  <form @submit.prevent="submitForm">
    <slot :restore="restore" :destroy="destroy" :isLoading="isLoading" :form="form" :rules="rules" />
  </form>
</template>

<script>
import FlashMessages from '@/Components/FlashMessages';
import TrashedMessage from '@/Components/TrashedMessage';

import { validateForm } from '@/validator.js';

export default {
  components: {
    FlashMessages,
    TrashedMessage,
  },
  props: ['form', 'rules'],
  computed: {
    storeUrl() {
      return route(`sidebar.${this.currentModel()}.store`);
    },
    destroyUrl() {
      return route(`sidebar.${this.currentModel()}.destroy`, { id: this.form.id });
    },
    restoreUrl() {
      return route(`sidebar.${this.currentModel()}.restore`, { id: this.form.id });
    },
  },
  data() {
    return {
      isLoading: false,
    };
  },
  methods: {
    submitForm() {
      this.isLoading = true;
      // const errors = validateForm(this.form, this.rules)
      // if (errors.length) {
      //   this.$page.props.errors = errors
      //   this.$page.props.flash.error = 'Thông tin chưa hợp lệ, vui lòng kiểm tra lại.'
      //   window.scrollTo(0, 0)
      // } else {
      this.store();
      this.isLoading = false;
      // }
    },

    store() {
      this.form.post(this.storeUrl);
    },

    destroy() {
      if (confirm('Bạn chắc chắn muốn xoá đối tượng này?')) {
        this.$inertia.post(this.destroyUrl);
      }
    },

    restore() {
      if (confirm('Bạn muốn khôi phục đối tượng này?')) {
        this.$inertia.post(this.restoreUrl);
      }
    },
  },
};
</script>
