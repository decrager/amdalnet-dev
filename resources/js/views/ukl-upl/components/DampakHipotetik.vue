<template>
  <div v-loading="isLoading === true" class="app-container">

    <el-button
      type="success"
      size="small"
      icon="el-icon-check"
      style="margin-bottom: 10px;"
      @click="handleSaveForm()"
    >
      Simpan Perubahan
    </el-button>

    <span style="float:right">
      <span v-show="!isLoading"><el-button icon="el-icon-refresh" round @click="refresh" /></span>
      <span v-show="isLoading === true"><el-button icon="el-icon-loading"> Refreshing data...</el-button></span>

    </span>
    <table style="margin: 2em 0; border-collapse: collapse;clear:both;">
      <thead>
        <tr>
          <th colspan="3">Rencana Usaha dan/atau Kegiatan yang Berpotensi Menimbulkan Dampak Lingkungan</th>
          <th style="font-size:80%" rowspan="2">Pengelolaan Lingkungan yang sudah
            direncanakan sejak awal sebagai bagian
            dari rencana kegiatan</th>
          <th rowspan="2">Evaluasi Dampak Potensial</th>
          <th rowspan="2">Dampak Penting Hipotetik</th>
          <th rowspan="2">Wilayah Studi</th>
          <th rowspan="2">Batas Waktu Kajian</th>
          <th rowspan="2">Kesimpulan</th>
        </tr>
        <tr>
          <th>Komponen Dampak</th>
          <th>Komponen Lingkungan</th>
          <th>Sumber Dampak</th>
        </tr>
      </thead>
      <template v-for="stage in data">
        <tr :key="'stage_'+ stage.id" :data-index="stage.project_stage_name">
          <td colspan="9" class="title" @click="showStage(stage.id_project_stage)"><strong>{{ stage.index }}. {{ stage.project_stage_name }}</strong></td>
        </tr>
        <tbody v-show="openedStage === stage.id_project_stage" :key="'hipotetik_' + stage.id_project_stage">
          <template v-for="impact in stage.impacts">
            <tr :key="'impact_'+ impact.id" class="title" animated>
              <td>
                <el-select v-model="impact.id_change_type" placeholder="Select">
                  <el-option
                    v-for="item in changeType"
                    :key="item.id"
                    :label="item.name"
                    :value="item.id"
                  />
                </el-select>
              </td>
              <td>Kebisingan</td>
              <td>akibat Mobilisasi Alat Berat</td>
              <td>Pengelolaan Lingkungan yang
                sudah direncanakan sejak awal
                sebagai bagian dari rencana
                kegiatan</td>
              <td>

                <template v-for="pie in pieParams">
                  <div :key="'pie_'+stage.id+'_'+pie.id" class="div-fka formA">
                    <p><strong>{{ pie.name }}</strong> {{ pie.description }}</p>
                    <el-input
                      v-model="textAA"
                      type="textarea"
                      :rows="5"
                      placeholder="Please input"
                    />
                  </div>
                </template>
              </td>
              <td>
                <el-select v-model="impact.is_hypothetical_significant" placeholder="Select">
                  <el-option
                    v-for="item in dPHs"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value"
                    :disabled="item.disabled"
                  />
                </el-select>
                <div v-show="!impact.is_hypothetical_significant" :key="'DTPH_'+impact.id" style="margin-top:0.6em;">
                  <el-switch v-model="impact.is_managed" active-text="Dikelola" />
                </div>
              </td>
              <td />
              <td>
                <p><el-input-number v-model="impact.study_length_year" :min="0" :max="10" size="mini" /> tahun</p>
                <p><el-input-number v-model="impact.study_length_month" :min="0" :max="12" size="mini" /> bulan</p>

              </td>
              <td />
            </tr>
          </template>
        </tbody>
      </template>

    </table>
  </div>
</template>
<script>
import Resource from '@/api/resource';
const projectStageResource = new Resource('project-stages');
const impactIdtResource = new Resource('impact-identifications');
const changeTypeResource = new Resource('change-types');
const pieParamsResource = new Resource('pie-params');

export default {
  name: 'DampakHipotetik',
  data() {
    return {
      idProject: 0,
      data: [],
      projectStages: [],
      openedStage: null,
      changeType: [],
      pieParams: [],
      pieInputMatrix: [],
      isLoading: false,
      dPHs: [
        { label: 'DPH', value: true },
        { label: 'DTPH', value: false },
      ],
    };
  },
  mounted() {
    this.isLoading = true;
    this.idProject = parseInt(this.$route.params && this.$route.params.id);
    setTimeout(() => (this.isLoading = false), 3000);
    this.getData();
  },
  methods: {
    handleSetData(data) {
      this.data = data;
    },
    handleSaveForm() {
      impactIdtResource
        .store({
          study_data: this.data,
        })
        .then((response) => {
          var message = (response.code === 200) ? 'Dampak Penting Hipotetik berhasil disimpan' : 'Terjadi kesalahan pada server';
          var message_type = (response.code === 200) ? 'success' : 'error';
          this.$message({
            message: message,
            type: message_type,
            duration: 5 * 1000,
          });
          if (response.code === 200){
            this.$emit('handleReloadVsaList', 'metode-studi');
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    createDataArray(imps, stages) {
      const stageIds = [4, 1, 2, 3];
      const indexes = ['', 'B', 'C', 'D', 'A'];
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
          index: indexes[id],
          id_project_stage: id,
          project_stage_name: projectStageName,
          impacts: impacts,
        });
      });
      const dataFlat = [];
      var dummyId = 99999999;
      data.map((d) => {
        var impactIdx = 1;
        dataFlat.push({
          id: dummyId,
          index: d.index,
          is_stage: true,
          project_stage_name: d.project_stage_name,
        });

        d.impacts.map((imp) => {
          imp['index'] = impactIdx;
          imp['is_stage'] = false;
          dataFlat.push(imp);
          impactIdx++;
        });
        dummyId++;
      });

      console.log(['createArray', data]);

      /* const inputPieMatrix = [];
      imps.map((d) => {
        inputPieMatrix[d.id_impact_identification] = [];
      });

      this.pieInputMatrix = inputPieMatrix;
      console.log(dataFlat); */

      // return dataFlat;

      return data;
    },
    async getData() {
      console.log('starting getData at DampakHipotetik');
      this.isLoading = true;
      this.pieInputMatrix = [];
      const prjStageList = await projectStageResource.list({});
      this.projectStages = prjStageList.data;
      const impactList = await impactIdtResource.list({
        id_project: this.idProject,
        join_tables: true,
      });

      const inputPieMatrix = [];

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

          inputPieMatrix.push(
            {
              id_impact_identification: imp.id,
              id_change_type: imp.id_change_type,
              study_length_month: imp.study_length_month,
              study_length_year: imp.study_length_year,
            });
        }
      });
      var dataList = impactList.data;
      this.pieInputMatrix = inputPieMatrix;

      this.data = this.createDataArray(dataList, this.projectStages);

      // console.log(['end of getData at DampakHipotetik', dataList]);
      // console.log(['end of getData at DampakHipotetik', inputPieMatrix]);
      console.log(['end of getData at DampakHipotetik', this.data]);
      console.log(['end of getData at DampakHipotetik', this.projectStages]);

      const sChangeType = await changeTypeResource.list();
      this.changeType = sChangeType.data;
      const sPieParams = await pieParamsResource.list();
      this.pieParams = sPieParams.data;

      this.isLoading = false;
    },
    initPieMatrix(){
      this.pieInputMatrix.map((d) => {
        // const matrix = [];
        this.pieParams.map((e) => {
          this.pieInputMatrix[d].push({
            id_pie_param: e.id,
            text: null,
            change_type: null,
          });
        });
      });
      console.log('pie matrix');
      console.log(this.pieInputMatrix);
    },
    refresh(){
      this.getData();
    },
    showStage(index){
      this.openedStage = (this.openedStage === index) ? null : index;
      console.log('showStage');
      console.log(this.openedStage);
    },
  },
};
</script>

<style scoped>
  table td.title {
    height: 200% !important;
  }
  table td.title:hover {
    background-color: #fafafa;
    cursor: pointer;
  }
</style>
