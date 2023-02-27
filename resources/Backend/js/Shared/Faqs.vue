<template>
  <details open>
    <summary class="mb-6 select-none card">
      <span class="text-lg font-semibold">FAQs</span>
    </summary>
    <div v-for="(item, index) in items" :key="index" class="p-6 pt-3 mb-2 space-y-2 bg-white rounded-md shadow">
      <div class="flex items-center text-sm font-semibold uppercase">
        Câu hỏi {{ index + 1 }}
        <div
          @click="confirmRemoveItem(index)"
          class="p-2 ml-auto border rounded cursor-pointer text-gray500 hover:text-gray-700 hover:bg-gray-100"
        >
          <Icon name="trash" />
        </div>
      </div>

      <Fieldset
        v-model="item.code"
        :field="{
          placeholder: 'Mã câu hỏi',
        }"
      />
      <Fieldset
        v-model="item.question"
        :field="{
          placeholder: 'Câu hỏi',
        }"
      />
      <Fieldset
        v-model="item.answer"
        :field="{
          placeholder: 'Câu trả lời',
          type: 'textarea',
        }"
      />
    </div>
    <div class="mt-4 mb-6">
      <Button
        label="Thêm câu hỏi"
        variant="white"
        @click="
          items.push({
            question: null,
            answer: null,
          })
        "
      />
    </div>
  </details>
</template>

<script>
export default {
  props: {
    modelValue: {
      type: Array,
      default: function (value) {
        return [];
      },
    },
  },
  emits: ['update:modelValue'],
  data() {
    return {
      items: this.modelValue,
    };
  },

  methods: {
    confirmRemoveItem(index) {
      if (confirm('Bạn có thực sự muốn xoá đối tượng này?')) {
        this.items.splice(index, 1);
      }
    },
  },
};
</script>
