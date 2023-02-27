<template>
  <template v-if="multiple">
    <div class="grid gap-3" :class="itemsPerRow">
      <div
        class="relative col-span-1 group"
        v-for="(file, index) in files"
        :key="index"
      >
        <div
          class="absolute h-[80px] inset-0 transition-opacity duration-200 bg-white opacity-0 group-hover group-hover:opacity-50"
        ></div>

        <div
          class="overflow-hidden transition-colors duration-200 bg-gray-100 border border-gray-100 rounded select-none aspect-[1/1] relative"
        >
          <img
            v-if="file.source_url"
            :src="file.source_url"
            class="object-contain w-full"
          />
          <img
            v-else-if="isImage(file.image_url || file)"
            :src="staticUrl(file.image_url || file)"
            class="object-contain w-full"
          />
          <div v-else class="flex items-center p-4 text-xs break-all">
            {{ getFileName(file.image_url || file) }}
          </div>

          <div
            class="absolute h-[20px] flex invisible space-x-1 transition-all duration-200 opacity-0 right-1 bottom-1 group-hover:opacity-100 group-hover:visible"
          >
            <Button size="xs" @click="removeSelectedFiles(index)" label="Xóa" />
          </div>
        </div>
      </div>
      <div class="relative col-span-1 group" v-if="files.length < maxItems">
        <div
          class="overflow-hidden text-gray-400 transition-colors duration-200 border border-gray-200 border-dashed rounded cursor-pointer select-none hover:border-gray-400 hover:text-gray-600 aspect-[1/1]"
          @click="showMediaManager = true"
        >
          <div class="flex items-center justify-center h-full">
            <Icon name="plus" />
          </div>
        </div>
      </div>
    </div>
  </template>
  <template v-else>
    <template v-if="Array.isArray(files) && files.length > 0">
      <div
        class="relative flex h-full max-w-xs bg-gray-200 border rounded-sm group"
      >
        <div class="absolute inset-0 flex items-center justify-center">
          <div
            class="absolute inset-0 transition-opacity duration-200 bg-white opacity-0 group-hover group-hover:opacity-50"
            @click="showMediaManager = true"
          ></div>
          <div
            class="absolute flex invisible space-x-2 transition-all duration-200 opacity-0 right-2 bottom-2 group-hover:opacity-100 group-hover:visible"
          >
            <Button size="xs" @click="removeSelectedFiles" label="Xoá" />
          </div>
        </div>
        <img
          v-if="isImage(files[0].image_url || files[0])"
          :src="staticUrl(files[0].image_url || files[0])"
          class="object-contain w-full"
        />
        <div v-else class="flex items-center p-4 text-xs break-all">
          {{ getFileName(files[0].image_url || files[0]) }}
        </div>
      </div>
    </template>
    <template v-else>
      <div
        class="relative w-full h-full max-w-xs p-3 text-gray-700 transition-colors duration-200 bg-gray-100 rounded-sm cursor-pointer select-none hover:bg-gray-200 hover:text-gray-900"
        @click="showMediaManager = true"
      >
        <div
          class="flex flex-col items-center justify-center w-full h-full py-4 space-y-2 text-xs font-medium text-center text-gray-600 border border-gray-400 border-dashed"
        >
          <Icon name="image" v-if="field.icon ?? true" />
          <span>CLICK ĐỂ CHỌN</span>
        </div>
      </div>
    </template>
  </template>
  <MediaManager
    v-if="showMediaManager"
    v-model:show="showMediaManager"
    @onSelect="onSelect"
    :multiple="multiple"
  />

  <DialogModal
    :show="editFileModal !== null"
    @close="editFileModal === null"
    max-width="lg"
  >
    <template #content>
      <div class="space-y-6" v-if="editFileModal">
        <div>
          <label
            class="block mb-2 font-semibold tracking-wide text-gray-700 font-display"
          >
            URL
          </label>
          <input
            v-model="editFileModal.link"
            type="text"
            class="block w-full py-[0.5rem] px-[1rem] border border-gray-300 focus:border-solid focus:outline-none focus:ring-0 rounded"
          />
        </div>
        <template v-if="editFileModal.options">
          <template v-for="(option, field) in editFileModal.options">
            <div>
              <label
                class="block mb-2 font-semibold tracking-wide text-gray-700 font-display"
              >
                {{ field }}
              </label>
              <input
                v-model="editFileModal.options[field]"
                type="text"
                class="block w-full py-[0.5rem] px-[1rem] border border-gray-300 focus:border-solid focus:outline-none focus:ring-0 rounded"
              />
            </div>
          </template>
        </template>
      </div>
    </template>

    <template #footer>
      <Button variant="white" @click="editFileModal = null" label="Hủy" />
      <Button type="button" class="ml-2" @click="editFileUpdate" label="Lưu" />
    </template>
  </DialogModal>
</template>

<script>
import MediaManager from "@/Components/Fields/MediaManager";
import DialogModal from "@/Components/DialogModal";

export default {
  components: {
    MediaManager,
    DialogModal,
  },

  props: ["field", "value"],
  emits: ["change"],

  data() {
    return {
      files: [],
      showMediaManager: false,
      editFileModal: null,
    };
  },

  computed: {
    multiple() {
      return this.field.multiple || false;
    },
    accept() {
      return this.field.accept ?? "image/png, image/gif, image/jpeg";
    },
    itemsPerRow() {
      return this.field.perRow ?? "grid-cols-4";
    },
    maxItems() {
      return this.field.max || 99;
    },
    expected() {
      return this.field.expected || "object";
    },
    expectedUrl() {
      return this.expected === "url" && !this.multiple;
    },
  },

  watch: {
    value() {
      this.files = [];
      this.initFiles();
    },
  },

  created() {
    this.initFiles();
  },

  methods: {
    initFiles() {
      if (!this.value || Object.keys(this.value).length === 0) return;
      if (Object.keys(this.value)[0] === "id") {
        this.value.id && this.files.push(this.value);
      } else {
        this.files = this.value;
      }
      this.appendDefaultValue();
    },
    appendDefaultValue() {
      if (this.field.options) {
        this.files = this.files.map((x) => {
          let options = this.field.options;
          if (x.options) {
            options = { ...options, ...x.options };
          }
          return { ...x, options: options };
        });
      }
    },
    onSelect(files) {
      const self = this;
      const expectedFiles = this.getExpected(files);

      if (this.multiple) {
        this.files = this.files.concat(expectedFiles);

        if (this.expected === "object") {
          this.files = this.files.filter(
            (v, i, a) => a.findIndex((v2) => v2.id === v.id) === i
          );
        } else {
          this.files = this.files.filter(
            (v, i, a) => a.findIndex((v2) => v2 === v) === i
          );
        }

        this.files = this.files.slice(0, this.maxItems);
      } else {
        this.files = expectedFiles;
      }

      this.emitValue(this.files);
    },

    showEditFileModal(index = 0) {
      this.editFileModal = this.files[index];
    },

    editFileUpdate() {
      const index = this.files.findIndex(
        (x) => x.url === this.editFileModal.url
      );
      this.files[index] = this.editFileModal;
      this.editFileModal = null;
      this.emitExpectedValue();
    },

    removeSelectedFiles(index = 0) {
      this.files.splice(index, 1);
      if (this.expectedUrl || !this.multiple) {
        this.$emit("change", null);
      } else {
        this.$emit("change", this.files);
      }
    },

    getFileName(path) {
      return path ? path.split("/").pop() : path;
    },

    getExpected(files) {
      if (this.expected === "object") {
        return files;
      } else {
        return this.pluck(files, this.expected);
      }
    },

    emitExpectedValue() {
      const files = this.getExpected(this.files);
      this.emitValue(files);
    },

    emitValue(files) {
      if (this.multiple) {
        this.$emit("change", files);
      } else {
        this.$emit("change", files[0]);
      }
    },
  },
};
</script>
