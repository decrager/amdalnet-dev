<template>
  <div v-loading="isLoading">
    <div style="text-align:right;margin-top:1em;">
      <el-popconfirm
        v-if="totalChanges > 0"
        confirm-button-text="Iya, refresh!"
        cancel-button-text="Tidak"
        title="Ada perubahan yang belum disimpan. Lanjutkan muat ulang data?"
        icon-color="red"
        @confirm="refresh()"
      >
        <el-button
          slot="reference"
          icon="el-icon-refresh"
        > Refresh data
        </el-button>
      </el-popconfirm>

      <el-button
        v-if="totalChanges <= 0"
        icon="el-icon-refresh"
        @click="refresh()"
      > Refresh data
      </el-button>
    </div>
    <dampak-hipotetik-master-table
      :impacts="impacts"
      :id-project="id_project"
      :change-types="changeTypes"
      :stages="stages"
      :total-changes="totalChanges"
      :loading="isLoading"
      :mode="mode"
      @dataSelected="onDataSelected"
      @saveChanges="saveChanges"
    />
    <dampak-hipotetik-detail-form
      :data="selectedData"
      :change-types="changeTypes"
      :pie-params="pieParams"
      :mode="mode"
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
  props: {
    mode: {
      type: Number,
      default: 0,
    },
  },
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
      impact_res_name: 'impacts',
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
      this.impacts = null;
      this.isLoading = true;
      impactsResource.list({
        id_project: this.id_project,
        mode: this.mode,
      }).then((res) => {
        res.map((e) => {
          e.hasChanges = false;
        });
        const order = [4, 1, 2, 3]; // stage's order
        this.impacts = res.sort((a, b) => order.indexOf(a.project_stage) - order.indexOf(b.project_stage));
      }).finally(() => {
        this.isLoading = false;
      });
    },
    async getParams(){
      // console.log('getting params!');
      await projectStagesResource.list({ ordered: true }).then((res) => {
        this.stages = res.data;
        console.log('get param stages', this.stages);
      });
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
      this.selectedData.hasChanges = true;
    },
    onDataSelected(obj) {
      this.selectedData = obj;
      // console.log('onSelectedData', this.selectedData.stage);
    },
    hasChanges(obj) {
      // this.handlePie(obj);
      const hc = this.impacts.filter(e => (e.hasChanges === true));
      this.totalChanges = hc.length;
      // console.log('calling totaller!', this.totalChanges);
    },
    onSaveData(obj){
      const temp = this.impacts;
      this.impacts = null;
      this.impacts = temp;
    },
    saveChanges(evt){
      const impstosave = this.impacts.filter(e => e.hasChanges === true);
      this.isLoading = true;
      impactsResource.store({ mode: this.mode, data: impstosave }).then((res) => {
        this.$message({
          message: 'Dampak Hipotetik berhasil disimpan',
          type: 'success',
          duration: 5 * 1000,
        });
      })
        .finally(() => {
          this.refresh();
          this.isLoading = false;
        });
    },
    refresh(){
      this.totalChanges = 0;
      this.getImpacts();
    },
    sortImpacts(imps){
      return imps.sort((a, b) => {
        const ia = this.stages.findIndex(sa => a.project_stage === sa.id);
        const ib = this.stages.findIndex(sb => b.project_stage === sb.id);
        return ia - ib;
      });
    },
  },
};
</script>
<style lang="scss" scoped>

</style>
