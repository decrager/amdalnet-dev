<template>
  <div>
    <dampak-potensial-table v-if="isDampakPotensialTable" :data="data" />
    <dampak-penting-hipotetik-table v-if="isDampakPentingHipotetikTable" :data="data" />
    <metode-studi-table v-if="isMetodeStudiTable" :data="data" />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import DampakPotensialTable from './DampakPotensialTable.vue';
import DampakPentingHipotetikTable from './DampakPentingHipotetikTable.vue';
import MetodeStudiTable from './MetodeStudiTable.vue';
const projectStageResource = new Resource('project-stages');
const impactIdtResource = new Resource('impact-identifications');

export default {
  name: 'IdentifikasiDampakTable',
  components: { DampakPotensialTable, DampakPentingHipotetikTable, MetodeStudiTable },
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
      isMetodeStudiTable: false,
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
      } else if (this.table === 'metode-studi'){
        this.isMetodeStudiTable = true;
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
        if (imp.impact_study === null) {
          imp.impact_study = {
            id: null,
            id_impact_identification: imp.id,
            forecast_method: null,
            required_information: null,
            data_gathering_method: null,
            analysis_method: null,
            evaluation_method: null,
          };
        }
      });
      var dataList = impactList.data;
      if (this.table === 'metode-studi'){
        dataList = dataList.filter(imp => imp.is_hypothetical_significant);
      }
      this.data = this.createDataArray(dataList, this.projectStages);
      this.$emit('handleSetData', this.data);
    },
  },
};
</script>
