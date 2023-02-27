@
<template>
  <div
    class="fixed top-0 bottom-0 right-0 z-50 bg-white left-from-sidebar"
    v-show="show"
  >
    <div class="topbar">
      <h1 class="flex items-center font-semibold text-gray-700">
        <div
          class="p-4 -ml-4 cursor-pointer hover:text-gray-900"
          @click="$emit('update:show', false)"
          v-if="selectable"
        >
          <Icon name="arrow-right" class="transform rotate-180" />
        </div>
        Media Manager
      </h1>
      <div class="flex ml-auto space-x-3">
        <input
          type="text"
          placeholder="Nhập tên tệp..."
          class="
            flex-inline
            w-[400px]
            py-[0.5rem]
            px-[1rem]
            border border-gray-300
            focus:border-solid focus:outline-none focus:ring-0
            rounded
            hover:border-gray-400
            focus:border-gray-500
          "
          @input="onChange"
        />
        <Button @click.prevent="browse" class="space-x-2">
          <Icon name="upload" />
          <span> Upload </span>
        </Button>
        <input
          type="file"
          class="hidden"
          accept="image/png, image/gif, image/jpeg, image/svg+xml, application/pdf ,image/webp"
          multiple="true"
          ref="image"
          @change="fileChange"
        />
      </div>
    </div>
    <div class="flex">
      <div
        class="px-4 py-4 overflow-auto sm:px-10 md:px-12 sm:py-10 md:flex-1"
        @dragover.prevent="isDragging = true"
      >
        <div
          class="fixed inset-0 border-4 border-dashed before:absolute before:bg-green-300 before:bg-opacity-25 before:inset-0 before:z-10"
          :class="
            isDragging
              ? 'z-10 border-green-300 before:visible visible'
              : 'z-0 border-transparent before:invisible invisible'
          "
          @dragleave.prevent="isDragging = false"
          @drop.prevent="(isDragging = false), (dragCounter = 0), drop($event)"
        ></div>
        <div class="relative grid grid-cols-8 gap-4 pb-12">
          <div
            class="relative col-span-1"
            v-for="(file, index) in uploadingFiles.slice().reverse()"
            :key="index"
            @dragover.prevent="isDragging = true"
          >
            <div
              v-if="!file.id"
              class="absolute inset-0 z-10 flex items-center justify-center cursor-wait "
            >
              <i class="z-10 gg-spinner"></i>
              <div class="absolute inset-0 z-0 bg-white opacity-50"></div>
            </div>
            <div
              :class="
                file.path ? 'group cursor-pointer' : 'pointer-events-none '
              "
            >
              <div
                class="
                  overflow-hidden
                  transition-colors
                  duration-200
                  bg-gray-100
                  border border-gray-200
                  rounded
                  cursor-pointer
                  select-none
                  aspect-[1/1]
                  group-hover:border-gray-500
                  relation
                "
                :class="{
                  'outline-black': selectedFiles.includes(file),
                }"
                @click="onSelect(file)"
              >
                <img
                  class="object-contain pointer-events-none"
                  :src="file.base64_code"
                  loading="lazy"
                />
              </div>

              <div
                class="absolute invisible space-x-1 transition-all duration-200 opacity-0 right-3 bottom-3"
              >
                <Button
                  size="sm"
                  v-show="!selectedFiles.includes(file)"
                  @click="copyUrl(file)"
                >
                  Url
                </Button>
                <Button
                  size="sm"
                  :class="{
                    'group-hover:opacity-100 group-hover:visible': file.path,
                  }"
                  v-show="!selectedFiles.includes(file)"
                  @click="deleteFile(file)"
                >
                  Xoá
                </Button>
              </div>
            </div>
          </div>

          <div
            class="relative col-span-1"
            v-if="data"
            v-for="(file, index) in data.data"
            :key="index"
          >
            <div class="cursor-pointer group">
              <div
                class="
                  overflow-hidden
                  transition-colors
                  duration-200
                  bg-gray-100
                  border border-gray-200
                  rounded
                  select-none
                  aspect-[1/1]
                  group-hover:border-gray-500
                "
                :class="{
                  'outline-black': selectedFiles.includes(file),
                }"
                @click="onSelect(file)"
              >
                <img
                  v-if="isImage(file.filename)"
                  class="object-contain pointer-events-none"
                  :src="this.staticUrl(file.image_url)"
                  loading="lazy"
                />
                <div v-else class="flex items-center p-4 text-xs break-all">
                  {{ file.filename }}
                </div>
              </div>
              <div
                class="absolute invisible space-x-1 transition-all duration-200 opacity-0 bottom-3 right-3 group-hover:opacity-100 group-hover:visible"
              >
                <Button
                  size="sm"
                  v-show="!selectedFiles.includes(file)"
                  @click="copyUrl(file)"
                >
                  Url
                </Button>
                <Button
                  size="sm"
                  v-show="!selectedFiles.includes(file)"
                  @click="deleteFile(file)"
                >
                  Xoá
                </Button>
              </div>
            </div>
          </div>
        </div>

        <Pagination
          v-if="data"
          :links="data.links"
          @changePage="getMedias($event)"
        />
      </div>
      <!-- <aside
        class="
          w-[var(--media-aside-width)]
          border-l
          bg-gray-100
          border-gray-300
        "
      >
      </aside> -->
    </div>
    <div
      v-if="multiple && selectedFiles.length > 0"
      class="absolute bottom-0 left-0 right-0 flex items-center justify-center w-full h-16 space-x-2 bg-white border-t "
    >
      <Button @click="selectedFiles = []"> Bỏ chọn </Button>
      <Button @click="submitFileSelect()">
        Chọn ({{ selectedFiles.length }})
      </Button>
    </div>
  </div>
</template>
<script>
import { onMounted, onUnmounted } from "vue";

const MAX_SIZE_OF_IMAGE = 5;
const MAX_SIZE_OF_VIDEO = 10;

export default {
  props: {
    show: {
      default: false,
    },
    multiple: {
      default: false,
    },
    selectable: {
      default: true,
    },
  },

  setup(props, { emit }) {
    const close = () => {
      emit("update:show", false);
    };

    const closeOnEscape = (e) => {
      if (e.key === "Escape" && props.show) {
        close();
      }
    };

    onMounted(() => document.addEventListener("keydown", closeOnEscape));
    onUnmounted(() => {
      document.removeEventListener("keydown", closeOnEscape);
    });

    return {
      close,
    };
  },

  data() {
    return {
      uploadingFiles: [],
      selectedFiles: [],
      isDragging: false,
      data: null,
      timer: null,
    };
  },

  created() {
    this.getMedias();
  },

  watch: {
    show(value) {
      if (value && this.data === null) {
        this.getMedias();
      }
    },
  },

  methods: {
    getMedias(page = 1, search = "") {
      axios.get(route("sidebar.media.index", { page, search })).then((res) => {
        this.data = res.data;
      });
    },
    async copyUrl(file) {
      try {
        await navigator.clipboard.writeText(this.staticUrl(file.image_url));
      } catch ($e) {}
    },
    onSelect(file) {
      if (!this.selectable) return;

      if (!this.multiple) {
        this.selectedFiles[0] = file;
        this.submitFileSelect();
      } else {
        if (!this.selectedFiles.includes(file)) {
          this.selectedFiles.push(file);
        } else {
          const fileIndex = this.selectedFiles.indexOf(file);
          this.selectedFiles.splice(fileIndex, 1);
        }
      }
    },
    submitFileSelect() {
      this.$emit("on-select", this.selectedFiles);
      this.selectedFiles = [];
      this.$emit("update:show", false);
    },
    browse() {
      this.$refs.image.click();
    },
    drop(event) {
      this.uploadFiles(event.dataTransfer.files);
    },
    fileChange() {
      this.uploadFiles(this.$refs.image.files);
    },
    uploadFiles(images) {
      if (images.length === 0) return;

      for (const image of images) {
        const fileCheck = this.fileCheck(image);
        if (!fileCheck.valid) {
          alert(
            `Dung lượng file tối đa là ${fileCheck.maxSize}MB. Vui lòng thử lại.`
          );
          this.$refs.image.value = "";
          return false;
        }
      }

      var formData = new FormData();
      for (let index = 0; index < images.length; index++) {
        const image = images[index];

        if (this.isImage(image.name)) {
          const reader = new FileReader();
          reader.onload = (e) => {
            this.uploadingFiles.push({
              filename: image.name,
              base64_code: e.target.result,
            });
          };
          reader.readAsDataURL(image);
        } else {
          this.uploadingFiles.push({
            filename: image.name,
            base64_code: null,
            size: image.size,
          });
        }
        formData.append("files[" + index + "]", image);
      }
      this.$refs.image.value = "";
      this.postFiles(formData);
    },
    postFiles(formData) {
      axios.post(route("sidebar.media.store"), formData).then((response) => {
        if (response.status === 200) {
          for (let index = 0; index < response.data.data.length; index++) {
            const file = response.data.data[index];
            this.uploadingFiles[index] = {
              ...this.uploadingFiles[index],
              ...file,
            };
          }
        }
      });
    },
    deleteFile(file) {
      axios.post(route("sidebar.media.destroy", { id: file.id }));
      const fileIndex = this.data.data.findIndex((x) => x.id === file.id);
      this.data.data.splice(fileIndex, 1);

      const uploadingFileIndex = this.uploadingFiles.findIndex(
        (x) => x.id === file.id
      );
      this.uploadingFiles.splice(uploadingFileIndex, 1);
    },
    fileCheck(file) {
      const maxSize = this.isImage(file.filename)
        ? MAX_SIZE_OF_IMAGE
        : MAX_SIZE_OF_VIDEO;
      const fileSize = file.size / 1024 / 1024;

      return { valid: fileSize <= maxSize, maxSize };
    },
    isPdf(filename) {
      return this.hasExtension(filename, [".pdf"]);
    },
    onChange(event) {
      if (this.timer) {
        clearTimeout(this.timer);
        this.timer = null;
      }

      this.timer = setTimeout(() => {
        this.getMedias(1, event.target.value);
      }, 150);
    },
  },
};
</script>
<style lang="scss" scoped>
.left-from-sidebar {
  left: var(--sidebar-width);
}
.topbar {
  @apply absolute flex items-center flex-shrink-0 w-full px-4 bg-white border-b sm:px-10 md:px-12;
  height: var(--topbar-height);
}

.topbar + div {
  @apply fixed right-0;
  top: var(--topbar-height);
  height: calc(100% - var(--topbar-height));
  left: var(--sidebar-width);
}
</style>
