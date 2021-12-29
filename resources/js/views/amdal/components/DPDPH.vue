<template>
  <div v-loading="isLoading">
    <div style="text-align:right;">  <el-button
      type="success"
      icon="el-icon-check"
      style="margin-bottom: 10px;"
      :disabled="totalChanges <= 0"
      @click="saveChanges()"
    >
      Simpan Semua Perubahan
    </el-button>
    </div>
    <dampak-hipotetik-master-table
      :impacts="impacts"
      :id-project="id_project"
      :change-types="changeTypes"
      :stages="stages"
      :total-changes="totalChanges"
      @dataSelected="onDataSelected"
    />
    <dampak-hipotetik-detail-form
      :data="selectedData"
      :change-types="changeTypes"
      :pie-params="pieParams"
      @hasChanges="hasChanges"
      @saveData="onSaveData"
    />
  </div>
</template>
<script>
import DampakHipotetikMasterTable from './tables/DPHMasterTable.vue';
import DampakHipotetikDetailForm from './forms/DPHDetailForm.vue';

import Resource from '@/api/resource';
const impactsResource = new Resource('impacts');
const projectStagesResource = new Resource('project-stages');
const changeTypeResource = new Resource('change-types');
const pieParamsResource = new Resource('pie-params');

export default {
  name: 'DampakHipotetikMD',
  components: { DampakHipotetikDetailForm, DampakHipotetikMasterTable },
  data() {
    return {
      id_project: 0,
      impacts: [],
      isLoading: false,
      totalChanges: 0,
      stages: [],
      selectedData: null,
      pieParams: [],
      changeTypes: [],
    };
  },
  mounted(){
    this.id_project = parseInt(this.$route.params && this.$route.params.id);
    this.impacts = [];
    this.getParams();
    this.getImpacts();
  },
  methods: {
    async getImpacts(){
      projectStagesResource.list({ ordered: true }).then((res) => {
        this.stages = res;
        // console.log('stages', this.stages);
      });

      this.impacts = null;
      impactsResource.list({
        id_project: this.id_project,
      }).then((res) => {
        this.impacts = res;
        // console.log('impacts', this.impacts);
      });
    },
    async getParams(){
      console.log('getting params!');
      await pieParamsResource.list({}).then((res) => {
        this.pieParams = res.data;
      });
      await changeTypeResource.list({}).then((res) => {
        this.changeTypes = res.data;
        this.changeTypes.push({
          name: 'Lainnya',
          id: 0,
        });
      });
    },
    handlePie(obj){
      // this.selectedData.pie = obj;
    },
    onDataSelected(obj) {
      this.selectedData = obj;
      // console.log('onSelectedData', this.selectedData.stage);
    },
    hasChanges(obj) {
      this.handlePie(obj);
      const hc = this.impacts.filter(e => (e.hasChanges === true));
      this.totalChanges = hc.length;
      // console.log('calling totaller!', this.totalChanges);
    },
    onSaveData(obj){
      const temp = this.impacts;
      this.impacts = null;
      this.impacts = temp;
    },
    saveChanges(){
      const imps = new Resource('impact-ids');
      const impstosave = this.impacts.filter(e => e.hasChanges === true);
      this.isLoading = true;
      imps.store(impstosave).then((res) => {
        this.$message({
          message: 'Dampak Hipotetik berhasil disimpan',
          type: 'success',
          duration: 5 * 1000,
        });
        this.isLoading = false;
        this.refresh();
      }); /* .catch((err) => {
        this.isLoading = false;
      });*/
    },
    refresh(){
      this.totalChanges = 0;
      this.getImpacts();
    },
  },
};
</script>
<style lang="scss" scoped>

</style>
