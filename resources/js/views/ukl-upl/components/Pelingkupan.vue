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
  props: {
    idProject: {
      type: Number,
      default: () => 0,
    },
  },
  data() {
    return {
      projectStages: [],
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    async getData() {
      const prjStages = await projectStageResource.list({
        ordered: true,
      });
      this.projectStages = prjStages.data;
    },
  },
};
</script>
