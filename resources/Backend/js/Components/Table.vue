<template>
  <div class="space-y-3">
    <div class="flex items-center">
      <div class="flex items-center w-full">
        <Button
          v-if="$attrs.canCreate === undefined || $attrs.canCreate"
          :href="route(`sidebar.${resource}.form`)"
          label="Thêm mới"
          class="mr-6"
        />
        <div class="space-x-2 text-2xl font-bold capitalize">
          {{ $attrs.name ?? resource }}
        </div>
        <div class="ml-auto">
          <input
            v-if="$attrs.onSearch !== undefined"
            class="w-[600px] max-w-80% flex input"
            autocomplete="off"
            type="text"
            name="search"
            placeholder="Tìm kiếm..."
            :value="route().params.search ? route().params.search : ''"
            @input="onChange"
          />
        </div>
      </div>
    </div>
    <div class="overflow-x-auto bg-white rounded shadow">
      <table class="table">
        <tr class="h-[2.5em]">
          <!-- <th class="relative w-16 px-6 py-2">
            <template v-if="hasData">
              <label
                :for="`checkbox_all`"
                class="
                  absolute
                  inset-0
                  flex
                  items-center
                  justify-center
                  cursor-pointer
                  select-none
                  hover:bg-gray-200
                "
              >
                <div class="checkbox">
                  <input
                    id="checkbox_all"
                    v-model="checkedAll"
                    type="checkbox"
                    :indeterminate.prop="
                      selectedRows.length > 0 &&
                      selectedRows.length < data.data.length
                    "
                  />
                  <span></span>
                </div>
              </label>
            </template>
          </th> -->
          <th class="py-0">ID</th>
          <th
            v-show="$slots && selectedRows.length > 0"
            :colspan="columns.length"
            class="py-0"
          >
            <slot :selectedRows="selectedRows"> </slot>
          </th>
          <th
            v-for="column in columns"
            v-show="!$slots || selectedRows.length === 0"
            :key="column"
          >
            <div class="flex items-center space-between">
              {{ column.label ?? column.field }}
            </div>
          </th>
          <th class="px-6 pt-6 pb-4"></th>
        </tr>
        <tr v-if="!hasData" class="h-[500px]">
          <td class="p-4 text-center border-t" :colspan="columns.length + 2">
            Không có dữ liệu.
          </td>
        </tr>
        <template v-else>
          <tr
            v-for="item in data.data"
            :key="item.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <!-- <td class="relative border-t" align="center">
              <label
                :for="`checkbox_${item.id}`"
                class="
                  absolute
                  inset-0
                  flex
                  items-center
                  justify-center
                  cursor-pointer
                  select-none
                  hover:bg-gray-200
                "
              >
                <div class="checkbox">
                  <input
                    :id="`checkbox_${item.id}`"
                    v-model="selectedRows"
                    type="checkbox"
                    :value="item.hash_id ?? item.id"
                  />
                  <span></span>
                </div>
              </label>
            </td> -->
            <td class="border-t">
              <Link
                class="
                  flex
                  items-center
                  break-words
                  whitespace-pre-wrap
                  hover:text-primary
                  focus:text-primary-dark
                "
                :href="
                  route(`sidebar.${resource}.form`, {
                    id: item.id,
                  })
                "
              >
                {{ item.id }}
              </Link>
            </td>
            <td v-for="column in columns" :key="column" class="border-t">
              <Link
                class="
                  flex
                  items-center
                  break-words
                  whitespace-pre-wrap
                  hover:text-primary
                  focus:text-primary-dark
                "
                :href="
                  route(`sidebar.${resource}.form`, {
                    id: item.id,
                  })
                "
              >
                <template v-if="column.field === 'status'">
                  <span
                    class="badge"
                    :class="{
                      'badge-success': ['ACTIVE', 1].includes(
                        item[column.field]
                      ),
                      'badge-danger': ['INACTIVE', 2].includes(
                        item[column.field]
                      ),
                      ...renderClass(item, column),
                    }"
                  >
                    {{ renderFormat(item, column) }}
                  </span>
                </template>
                <template v-else-if="column.type === 'image'">
                  <img
                    v-if="renderFormat(item, column)"
                    :src="staticUrl(renderFormat(item, column))"
                    class="h-12"
                  />
                </template>
                <template v-else>
                  <span :class="renderClass(item, column)">
                    {{ renderFormat(item, column) }}
                  </span>
                </template>
              </Link>
            </td>

            <td class="w-px border-t">
              <Link
                class="flex items-center px-4"
                :href="
                  route(`sidebar.${resource}.form`, {
                    id: item.id,
                  })
                "
                tabindex="-1"
              >
                <Icon name="arrow-right" class="block w-6 h-6 text-gray-400" />
              </Link>
            </td>
          </tr>
        </template>
      </table>
    </div>
    <div
      v-if="data && data.links && data.links.length > 3"
      class="flex items-center"
    >
      <div class="flex flex-wrap -mb-1">
        <div
          v-for="(link, key) in data.links"
          :key="key"
          class="
            flex
            mb-1
            mr-1
            overflow-hidden
            text-sm
            leading-4
            border
            rounded
            cursor-pointer
            hover:bg-gray-100
          "
          :class="
            link.url
              ? 'hover:bg-white focus:border-indigo-500 focus:text-indigo-500'
              : 'text-gray-400'
          "
        >
          <div v-if="link.url === null" class="px-4 py-3" v-html="link.label" />
          <Link
            v-else
            class="px-4 py-3"
            :class="{ 'bg-white': link.active }"
            :href="link.url"
            v-html="link.label"
          />
        </div>
      </div>
      <div class="ml-auto">
        Hiển thị kết quả từ {{ data.from }} - {{ data.to }} trên tổng
        {{ data.total }}
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["data", "filters", "columns"],
  data() {
    return {
      timer: null,
      selectedRows: [],
      checkedAll: false,
    };
  },

  computed: {
    resource() {
      return this.$attrs.model || this.currentModel();
    },
    hasData() {
      return this.data && this.data.data && this.data.data.length > 0;
    },
  },

  watch: {
    checkedAll() {
      if (this.checkedAll) {
        this.selectedRows = this.pluck(this.data.data);
      } else {
        this.selectedRows = [];
      }
    },
  },

  methods: {
    renderFormat(item, column) {
      if (column.format && column.format === "money") {
        return this.toMoney(item[column.field]);
      } else if (column.format) {
        return column.format(item[column.field]);
      }
      return item[column.field];
    },
    renderClass(item, column) {
      if (column.class) {
        const renderClass = column.class(item[column.field]);
        return { [renderClass]: true };
      }
      return {};
    },
    onChange(event) {
      let params = {
        ...route().params,
        search: event.target.value,
      };

      if (params.search === "") {
        delete params.search;
      } else if (params.page) {
        delete params.page;
      }

      const newUrl = route(`sidebar.${this.resource}.index`, params);
      history.pushState({}, null, newUrl);

      this.setTimeout();
    },

    setTimeout() {
      if (this.timer) {
        clearTimeout(this.timer);
        this.timer = null;
      }

      this.timer = setTimeout(() => {
        this.$emit("search");
      }, 150);
    },
  },
};
</script>
<style lang="scss" scoped>
td {
  @apply max-w-md;
}
</style>
