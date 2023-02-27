<template>
  <nav
    class="
      sidebar
      w-[var(--sidebar-width)]
      h-screen
      bg-gray-900
      flex flex-col
      fixed
    "
  >
    <div
      class="
        flex
        items-center
        justify-between
        px-3
        py-5
        text-white
        border-b border-gray-800
      "
    >
      <Icon name="logo" />
    </div>
    <div class="py-6 space-y-4">
      <div class="space-y-4">
        <ul>
          <li>
            <Link
              :href="route('sidebar.dashboard.index')"
              class="nav-link"
              :class="{ active: isUrl('dashboard') }"
            >
              <span>Tổng Quan</span>
            </Link>
          </li>
        </ul>
        <ul>
          <li class="nav-header">Sản phẩm</li>
          <li v-if="hasAnyPermission(['sidebar.products.index'])">
            <Link
              :href="route('sidebar.products.index')"
              class="nav-link"
              :class="{ active: isUrl('products') }"
            >
              <span>Sản phẩm</span>
            </Link>
          </li>
        </ul>
        <ul>
          <li class="nav-header">Nội dung</li>
          <li v-if="hasAnyPermission(['sidebar.posts.index'])">
            <Link
              :href="route('sidebar.posts.index')"
              class="nav-link"
              :class="{ active: isUrl('posts') }"
            >
              <span>Bài viết</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.post-categories.index'])">
            <Link
              :href="route('sidebar.post-categories.index')"
              class="nav-link"
              :class="{ active: isUrl('post-categories') }"
            >
              <span>Danh mục bài viết</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.policies.index'])">
            <Link
              :href="route('sidebar.policies.index')"
              class="nav-link"
              :class="{ active: isUrl('policies') }"
            >
              <span>Chính sách</span>
            </Link>
          </li>
        </ul>
        <ul>
          <li class="nav-header">Cài đặt</li>
          <li v-if="hasAnyPermission(['sidebar.navs.index'])">
            <Link
              :href="route('sidebar.navs.index')"
              class="nav-link"
              :class="{ active: isUrl('navs') }"
            >
              <span>Menu</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.ads.index'])">
            <Link
              :href="route('sidebar.ads.index')"
              class="nav-link"
              :class="{ active: isUrl('ads') }"
            >
              <span>Banner</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.media.index'])">
            <Link
              :href="route('sidebar.media.index')"
              class="nav-link"
              :class="{ active: isUrl('media') }"
            >
              <span>Media</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.users.index'])">
            <Link
              :href="route('sidebar.users.index')"
              class="nav-link"
              :class="{ active: isUrl('users') }"
            >
              <span>Thành viên</span>
            </Link>
          </li>
          <li>
            <Link
              class="nav-link"
              :href="route('sidebar.admins.index')"
              :class="{ active: isUrl('admins') }"
            >
              <span>Tài khoản</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.roles.index'])">
            <Link
              class="nav-link"
              :href="route('sidebar.roles.index')"
              :class="{ active: isUrl('roles') }"
            >
              <span>Vai trò</span>
            </Link>
          </li>
        </ul>
      </div>
    </div>
    <ul class="mt-auto">
      <li>
        <Link :href="route('logout')" method="post" as="button" class="nav-link"
          >Đăng xuất</Link
        >
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  data() {
    return {
      currentDomain: null,
      permissions: this.$page.props.auth.permissions,
    };
  },

  mounted() {
    this.currentDomain = window.location.hostname;
  },

  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(7);
      if (urls[0] === "") {
        return currentUrl === "";
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length;
    },

    hasAnyPermission(action) {
      if (Array.isArray(action)) {
        return this.permissions.some((r) => action.indexOf(r) >= 0);
      }
      return this.permissions.includes(action);
    },
  },
};
</script>

<style lang="scss" scoped>
.main-wrapper {
  display: flex;
  & > main {
    flex-grow: 1;
    width: auto;
  }
}

.sidebar {
  .nav-link {
    @apply flex items-center w-full gap-2 px-6 py-2 text-white transition duration-150 text-base;
    &:not(.active) {
      @apply opacity-70;
      &:hover {
        @apply opacity-100;
      }
    }
    &.active {
      color: theme("colors.primary.DEFAULT");
      @apply bg-gray-800;
    }
  }

  .nav-header {
    @apply list-none px-6 py-2 uppercase text-xs  text-primary-darker;
    &:not(:first-of-type) {
      margin-top: 0.5rem;
    }
  }
}
</style>
