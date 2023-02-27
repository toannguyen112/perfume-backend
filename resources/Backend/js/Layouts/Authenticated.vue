<template>
  <div class="flex min-h-screen">
    <Sidebar />
    <main class="ml-[var(--sidebar-width)] md:flex-1">
      <div class="px-4 py-8 md:p-12 md:overflow-y-auto">
        <slot />
      </div>
    </main>
  </div>
</template>

<script>
import Sidebar from '@/Components/Sidebar';

export default {
  components: {
    Sidebar,
  },
};
</script>
