<template>
  <div v-if="isBesaranDampakTable">
    <besaran-dampak-table :data="data" />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import BesaranDampakTable from './BesaranDampakTable.vue';
const projectStageResource = new Resource('project-stages');
const impactIdtResource = new Resource('impact-identifications');

export default {
  name: 'IdentifikasiDampakTable',
  components: { BesaranDampakTable },
  props: {
    idProject: {
      type: Number,
      default: () => 0,
    },
    table: {
      type: String,
      default: () => '',
    },
  },
  data() {
    return {
      data: [],
      projectStages: [],
      isBesaranDampakTable: false,
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    createDataArray(imps, stages) {
      var stageIds = [4, 1, 2, 3];
      const data = [];
      stageIds.map((id) => {
        const impacts = [];
        imps.map((imp) => {
          if (imp.id_project_stage === id){
            impacts.push(imp);
          }
        });
        var projectStageName = '';
        stages.map((s) => {
          if (s.id === id) {
            projectStageName = s.name;
          }
        });
        data.push({
          id_project_stage: id,
          project_stage_name: projectStageName,
          impacts: impacts,
        });
      });
      const dataFlat = [];
      var dummyId = 9990;
      data.map((d) => {
        dataFlat.push({
          id: dummyId,
          is_stage: true,
          project_stage_name: d.project_stage_name,
        });

        d.impacts.map((imp) => {
          dataFlat.push({
            id: imp.id,
            is_stage: false,
            id_change_type: imp.id_change_type,
            component_name: imp.component_name,
            rona_awal_name: imp.rona_awal_name,
            nominal: imp.nominal,
            id_unit: imp.id_unit,
            unit_name: imp.unit_name,
          });
        });
        dummyId++;
      });
      return dataFlat;
    },
    async getData() {
      if (this.table === 'besaran-dampak'){
        this.isBesaranDampakTable = true;
      }
      const prjStageList = await projectStageResource.list({});
      this.projectStages = prjStageList.data;
      const impactList = await impactIdtResource.list({
        id_project: this.idProject,
        join_tables: true,
      });
      impactList.data.map((imp) => {
        if (imp.id_project_stage === null) {
          imp.id_project_stage = imp.id_project_stage_master;
        }
        if (imp.component_name === null) {
          imp.component_name = imp.component_name_master;
        }
        if (imp.rona_awal_name === null) {
          imp.rona_awal_name = imp.rona_awal_name_master;
        }
      });
      this.data = this.createDataArray(impactList.data, this.projectStages);
      this.$emit('handleSetData', this.data);
    },
  },
};
</script>
