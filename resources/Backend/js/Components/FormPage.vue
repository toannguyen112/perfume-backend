@@ -1,81 +0,0 @@
<template>
  <Form :form="form" :rules="rules" v-slot="{ destroy, restore, isLoading, form, rules }">
    <div :class="$slots.right ? 'max-w-screen-xl mx-auto grid grid-cols-12 gap-x-6 gap-y-6' : 'space-y-6'">
      <div class="flex" :class="$slots.right ? 'col-span-12' : 'max-w-3xl mx-auto'">
        <div class="space-x-2 text-2xl font-bold">
          <Link
            class="capitalize text-primary hover:text-primary-darker"
            :href="route(`sidebar.${currentModel()}.index`)"
            >{{ $attrs.name ?? currentModel() }}</Link
          >
          <span class="font-semibold">/</span>
          <span> {{ form.name ?? form.title }}</span>
        </div>
        <Button
          v-if="!$slots.submit && ($attrs.canSubmit === undefined || $attrs.canSubmit === true)"
          class="ml-auto"
          :loading="isLoading"
          type="submit"
          label="Lưu"
        />
      </div>
      <div :class="$slots.right ? 'col-span-9' : 'max-w-3xl mx-auto'">
        <FlashMessages />
        <TrashedMessage v-if="form.deleted_at" class="mb-6" @restore="restore">
          Đối tượng này đã bị xoá.
        </TrashedMessage>
        <slot></slot>
        <hr class="my-6" />
        <div class="flex justify-between" v-if="$attrs.canSubmit === undefined || $attrs.canSubmit === true">
          <Button :loading="isLoading" type="submit" label="Lưu" />
          <Button v-if="!form.deleted_at && form.id" variant="link-danger" @click="destroy" label="Xóa" />
        </div>
      </div>

      <div v-if="$slots.right" class="col-span-3 space-y-6">
        <slot name="right"></slot>
      </div>
    </div>
  </Form>
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
};
</script>
