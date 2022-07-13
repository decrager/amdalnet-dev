<template>
  <div style="padding:2em;">
    <el-table
      v-loading="loading"
      :data="data"
    >
      <el-table-column label="No." width="70" />
      <el-table-column label="Lampiran" />
      <el-table-column label="" />
    </el-table>
  </div>
</template>
<script>
import Resource from '@/api/resource';

export default {
  name: 'ProjectAttachments',
  props: {
    id: { // id project
      type: Number,
      default: 0,
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
