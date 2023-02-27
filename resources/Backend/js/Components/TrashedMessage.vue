<template>
  <div class="flex items-center justify-between p-4 bg-yellow-300 rounded">
    <div class="flex items-center">
      <!-- <IconTrash class="flex-shrink-0 w-4 h-4 fill-yellow-800" /> -->
      <div class="text-sm font-medium text-yellow-800">
        <slot />
      </div>
    </div>
    <button class="text-sm text-yellow-800 hover:underline" tabindex="-1" type="button" @click="$emit('restore')">
      Khôi phục
    </button>
  </div>
</template>

<script>
// import IconTrash from "@/Icons/Trash";

// export default {
//   components: {
//     IconTrash,
//   },
// };
</script>
