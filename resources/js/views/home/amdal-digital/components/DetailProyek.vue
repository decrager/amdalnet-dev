<template>
  <project-home :id="id" />
</template>
<script>
import ProjectHome from '@views/project/Home/index.vue';
export default {
  name: 'DetailProyek',
  components: {
    ProjectHome,
  },
  props: {
    id: {
      type: Number,
      default: 0,
    },
  },
};
</script>
