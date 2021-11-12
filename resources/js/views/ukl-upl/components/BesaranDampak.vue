<template>
  <div class="app-container">
    <el-button
      type="success"
      size="small"
      icon="el-icon-check"
      style="margin-bottom: 10px;"
      @click="handleSaveForm()"
    >
      Simpan Perubahan
    </el-button>
    <table>
      <tr class="tr-header">
        <td class="td-header">
          <span>Komponen Dampak</span>
        </td>
        <td class="td-header">
          <span>Komponen Rona Lingkungan Awal</span>
        </td>
        <td class="td-header" width="130">
          <span />
        </td>
        <td class="td-header">
          <span>Sumber Dampak</span>
        </td>
        <td class="td-header">
          <span>Besaran Dampak</span>
        </td>
      </tr>
      <tr v-for="impact of data" :key="impact.id" class="tr-data">
        <td v-if="impact.is_stage" colspan="5" class="td-data">
          <span>{{ impact.project_stage_name }}</span>
        </td>
        <td v-if="!impact.is_stage" class="td-data">
          <el-select
            v-model="impact.id_change_type"
            placeholder="Perubahan"
            style="width: 100%"
          >
            <el-option
              v-for="item of changeTypeOptions"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </td>
        <td v-if="!impact.is_stage" class="td-data">
          <span>{{ impact.rona_awal_name }}</span>
        </td>
        <td v-if="!impact.is_stage" class="td-data">
          <span>akibat</span>
        </td>
        <td v-if="!impact.is_stage" class="td-data">
          <span>{{ impact.component_name }}</span>
        </td>
        <td v-if="!impact.is_stage" class="td-data">
          <span>
            <el-input v-model="impact.nominal" width="65" />
            <el-select
              v-model="impact.id_unit"
              placeholder="unit"
              style="width: 100%"
              width="65"
            >
              <el-option
                v-for="item of unitOptions"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </span>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const changeTypeResource = new Resource('change-types');
const projectStageResource = new Resource('project-stages');
const unitResource = new Resource('units');
const impactIdtResource = new Resource('impact-identifications');

export default {
  name: 'BesaranDampak',
  data() {
    return {
      idProject: 0,
      data: [],
      changeTypeOptions: [],
      unitOptions: [],
      projectStages: [],
    };
  },
  mounted() {
    this.idProject = parseInt(this.$route.params && this.$route.params.id);
    this.getData();
  },
  methods: {
    handleSaveForm() {
      impactIdtResource
        .store({
          unit_data: this.data,
        })
        .then((response) => {
          var message = (response.code === 200) ? 'Besaran Dampak berhasil disimpan' : 'Terjadi kesalahan pada server';
          var message_type = (response.code === 200) ? 'success' : 'error';
          this.$message({
            message: message,
            type: message_type,
            duration: 5 * 1000,
          });
          if (response.code === 200) {
            this.$emit('handleEnableSubmitForm');
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
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
      const changeTypeList = await changeTypeResource.list({});
      this.changeTypeOptions = changeTypeList.data;
      const unitList = await unitResource.list({});
      this.unitOptions = unitList.data;
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
    },
  },
};
</script>

<style scoped>
table {
  border-collapse: collapse;
  font-size: 14px;
}
.tr-header {
  border: 1px solid white;
  background-color: #3AB06F;
  color: white;
}
.td-header {
  border: 1px solid white;
  padding: 10px;
}
.tr-data, .td-data {
  border: 1px solid gray;
  padding: 10px;
}
</style>
