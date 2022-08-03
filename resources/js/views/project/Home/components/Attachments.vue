<template>
  <div style="padding:2em;">
    <screening-attachments />
    <amdal-attachments />
    <ukl-upl-attachments />
  </div>
</template>
<script>
import Resource from '@/api/resource';
import ScreeningAttachments from './sections/ScreeningAttachments.vue';
import AmdalAttachments from './sections/AmdalAttachments.vue';
import UklUplAttachments from './sections/UklUplAttachments.vue';

export default {
  name: 'ProjectAttachments',
  components: { ScreeningAttachments, AmdalAttachments, UklUplAttachments },
  props: {
    /*    id: { // id project
      type: Number,
      default: 0,
    },*/
    project: {
      type: Object,
      default: () => {},
    },
  },
  data(){
    return {
      data: [],
      stages: ['Penapisan', 'Kerangka Acuan', 'Andal RKL RPL'],
      loading: false,
    };
  },
  mounted(){
    // this.getProjectAttachments();
    console.log(this.project);
  },
  methods: {
    async getProjectAttachments(){
      this.loading = true;
      const projectArchiveResource = new Resource('project-attachments/' + this.id);
      await projectArchiveResource.list()
        .then((res) => {
          console.log(res);
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
};
</script>
