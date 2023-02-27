<template>
  <div class="card">
    <div class="flex">
      <label class="label">Biến thể</label>
      <div class="ml-auto space-x-1">
        <span v-for="(option, index) in options" class="py-1 bg-gray-100 cursor-pointer badge">
          {{ option.name }}
        </span>
      </div>
    </div>
    <div class="space-y-6">
      <div v-if="variants.length > 0 || options.length > 0" class="space-y-2">
        <details v-for="(variant, variantIndex) in variants" class="p-2">
          <summary class="cursor-pointer">
            <span class="mb-0 select-none label"> {{ variant.product_name }} - {{ variant.title }} </span>
          </summary>
          <div class="mt-4">
            <table class="table">
              <tr>
                <td>Variant ID:</td>
                <td>{{ variant.id }}</td>
              </tr>
              <tr>
                <td>SKU:</td>
                <td>{{ variant.sku }}</td>
              </tr>
              <tr>
                <td>Giá:</td>
                <td>{{ toMoney(variant.price) }}</td>
              </tr>
              <tr>
                <td>Giá so sánh:</td>
                <td>{{ variant.old_price }}</td>
              </tr>
              <tr>
                <td>Sapo ID:</td>
                <td>
                  <a
                    :href="`https://rangdong.mysapo.net/admin/products?Query=${variant.sku}`"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="link"
                    >{{ variant.source_id }}</a
                  >
                </td>
              </tr>
              <tr>
                <td>Thuộc tính:</td>
                <td class="space-y-2">
                  <div v-for="(option, index) in options">
                    <Fieldset
                      :modelValue="variant.options[option.id]"
                      @update:modelValue="
                        variant.options[option.id] = option.nodes.find((x) => x.id.toString() === $event.toString())
                      "
                      :field="{
                        type: 'select_single',
                        label: optionName(option),
                        options: option.nodes.sort(),
                      }"
                    />
                  </div>
                </td>
              </tr>
              <tr>
                <td>Hình ảnh:</td>
                <td class="flex items-center space-x-2">
                  <div v-for="(image, index) in variant.images" class="w-[80px] h-[80px] bg-gray-100">
                    <img :src="image.src" class="object-fit" />
                  </div>
                </td>
              </tr>
            </table>
          </div>
        </details>
      </div>

      <!-- <Button label="Thêm mới" variant="outline-secondary" @click="addOption" /> -->
    </div>

    <DialogModal :show="variantModal !== null" @close="variantModal = null" max-width="lg">
      <template #content>
        <Fieldset
          v-model="variantModal.title"
          :field="{
            rules: 'required',
            label: 'Tên biến thể',
          }"
        />
        <hr class="my-6" />
        <div class="space-y-3">
          <div v-for="(option, index) in options">
            <label class="label">{{ findOption(option.id).name }}</label>
            <Fieldset
              v-model="variantModal.options"
              :field="{
                type: 'radio',
                options: option.nodes,
              }"
            />
          </div>
        </div>
      </template>

      <template #footer>
        <Button variant="white" @click="variantModal = null" label="Hủy" />
        <Button type="button" class="ml-2" @click="editFileUpdate" label="Lưu" />
      </template>
    </DialogModal>
  </div>
</template>
<script>
import DialogModal from '@/Components/DialogModal';
export default {
  components: {
    DialogModal,
  },
  props: ['variants', 'options', 'allOptions'],
  emits: ['update:variants'],
  data() {
    return {
      values: [],
      variantModal: null,
      variantOptions: [],
    };
  },
  created() {
    this.variants.map(function (variant) {
      variant.options = variant.options.length === 0 ? {} : variant.options;
      return variant;
    });
  },
  methods: {
    getChildNodes(parent) {
      if (!parent) return [];

      const parentId = parent.id;
      const option = this.options.find((x) => x.id === parentId);
      return option ? option.nodes : [];
    },

    combinations(args) {
      var r = [],
        max = args.length - 1;
      function helper(arr, i) {
        for (var j = 0, l = args[i].length; j < l; j++) {
          var a = arr.slice(0);
          a.push(args[i][j]);
          if (i == max) r.push(a);
          else helper(a, i + 1);
        }
      }
      helper([], 0);
      return r;
    },

    findOption(id, isChild = false) {
      if (!isChild) {
        return this.allOptions.find((x) => x.id === id);
      } else {
        for (const option of this.allOptions) {
          const child = option.nodes.find((item) => item.id === id);
          if (child) return child;
        }
      }
    },

    optionName(option) {
      const findOption = this.findOption(option.id);
      return findOption ? findOption.name : null;
    },
  },
};
</script>
