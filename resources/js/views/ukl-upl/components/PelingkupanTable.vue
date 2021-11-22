<template>
  <div>table</div>
</template>

<script>

export default {
  name: 'PelingkupanTable',
  props: {
    idProject: {
      type: Number,
      default: () => 0,
    },
    idProjectStage: {
      type: Number,
      default: () => 0,
    },
  },
  data() {
    return {
      subProjects: [],
      subProjectComponents: [],
      componentTypes: [],
      subProjectRonaAwals: [],
    };
  },
  mounted() {

  },
  methods: {
    async getData() {

    },
  },
};
</script>
