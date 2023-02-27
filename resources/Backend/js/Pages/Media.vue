<template>
  <Authenticated>
    <MediaManager :show="true" :selectable="false" />
  </Authenticated>
</template>

<script>
import Authenticated from '@/Layouts/Authenticated';
import MediaManager from '@/Components/Fields/MediaManager.vue';

export default {
  components: {
    Authenticated,
    MediaManager,
  },
};
</script>
