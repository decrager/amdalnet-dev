<template>
  <el-tabs type="card">
    <el-tab-pane v-for="s of projectStages" :key="s.id" :label="s.name">
      <pelingkupan-table
        :id-project="idProject"
        :id-project-stage="s.id"
      />
    </el-tab-pane>
  </el-tabs>
</template>
<script>

import Resource from '@/api/resource';
import PelingkupanTable from './PelingkupanTable.vue';
const projectStageResource = new Resource('project-stages');

export default {
  name: 'Pelingkupan',
  components: { PelingkupanTable },
  data() {
    return {
      idProject: 0,
      projectStages: [],
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    async getData() {
      this.idProject = parseInt(this.$route.params && this.$route.params.id);
      const prjStages = await projectStageResource.list({
        ordered: true,
      });
      this.projectStages = prjStages.data;
    },
  },
};
</script>
