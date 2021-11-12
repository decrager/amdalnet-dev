<template>
  <div>
    <dampak-potensial-table v-if="isDampakPotensialTable" :data="data" />
    <dampak-penting-hipotetik-table v-if="isDampakPentingHipotetikTable" :data="data" />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import DampakPotensialTable from './DampakPotensialTable.vue';
import DampakPentingHipotetikTable from './DampakPentingHipotetikTable.vue';
const projectStageResource = new Resource('project-stages');
const impactIdtResource = new Resource('impact-identifications');

export default {
  name: 'IdentifikasiDampakTable',
  components: { DampakPotensialTable, DampakPentingHipotetikTable },
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
      isDampakPotensialTable: false,
      isDampakPentingHipotetikTable: false,
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
          imp['is_stage'] = false;
          dataFlat.push(imp);
        });
        dummyId++;
      });
      return dataFlat;
    },
    async getData() {
      if (this.table === 'dampak-potensial'){
        this.isDampakPotensialTable = true;
      } else if (this.table === 'dampak-penting-hipotetik'){
        this.isDampakPentingHipotetikTable = true;
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
