<template>
  <div v-loading="isLoading" class="app-container">

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
      <!-- based on mockup received on Thu, 2 Dec 2021 -->
      <thead>
        <tr>
          <th colspan="2">Rencana Usaha dan/atau Kegiatan yang Berpotensi Menimbulkan Dampak Lingkungan</th>
          <th>Pengelolaan yang sudah direncanakan</th>
          <th>Komponen Rona yang Terkena Dampak</th>
          <th>Dampak Potensial</th>
          <th>Evaluasi Dampak Potensial</th>
          <th>Dampak Penting Hipotetik</th>
          <th>Wilayah Studi</th>
          <th>Batas Waktu Kajian</th>
        </tr>
      </thead>
      <template v-for="stage in data">
        <tr :key="'stage_'+ stage.id_project_stage" :data-index="stage.project_stage_name">
          <td colspan="9" class="title" @click="showStage(stage.id_project_stage)"><strong>{{ stage.index }}. {{ stage.project_stage_name }}</strong></td>
        </tr>
        <tbody v-show="openedStage === stage.id_project_stage" :key="'hipotetik_' + stage.id_project_stage">
          <template v-for="(impact, idx) in stage.impacts">
            <tr :key="'impact_'+ impact.id" class="title" animated>
              <td style="width:30px;">{{ (idx + 1) }}.</td>
              <td>{{ impact.component_name }}</td>
              <td>{{ impact.initial_study_plan }}</td>
              <td>{{ impact.rona_awal_name }}</td>
              <td>
                <el-select
                  v-model="impact.id_change_type"
                  placeholder="Pilih"
                  filterable
                  allow-create
                  clearable
                  @change.native="handleOptionChange"
                >
                  <el-option
                    v-for="item in changeType"
                    :key="item.id"
                    :label="item.name"
                    :value="item.id"
                  />
                </el-select>
                <p>{{ impact.rona_awal_name }} akibat {{ impact.component_name }}</p>
              </td>
              <td>
                <template v-for="(pie, index) in pieParams">
                  <div :key="'pie_'+impact.id+'_'+pie.id" class="div-fka formA">
                    <el-popover
                      v-if="(pie.description != null) && (pie.description != '')"
                      placement="top-start"
                      width="350"
                      trigger="hover"
                    >
                      <p style="word-break: break-word !important; text-align:left !important;">{{ pie.description }}</p>
                      <p slot="reference" :key="'po_'+ pie.id + '_'+ impact.id" style="font-weight:bold; cursor: pointer;"><strong>{{ pie.name }}</strong></p>
                    </el-popover>
                    <el-input
                      v-model="impact.potential_impact_evaluation[index].text"
                      type="textarea"
                      :rows="3"
                      :value="impact.potential_impact_evaluation[index].text"
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
                <div v-show="!impact.is_hypothetical_significant" :key="'DTPH_'+impact.id" style="margin:1em 0;">
                  <el-switch v-model="impact.is_managed" active-text=" Dikelola" />
                </div>
              </td>
              <td>{{ impact.study_location }}</td>
              <td>
                <p><el-input-number v-model="impact.study_length_year" :min="0" :max="10" size="mini" /> tahun</p>
                <p><el-input-number v-model="impact.study_length_month" :min="0" :max="11" size="mini" /> bulan</p>
              </td>
            </tr>
          </template>
        </tbody>
      </template>
      <!--
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
        <tr :key="'stage_'+ stage.id_project_stage" :data-index="stage.project_stage_name">
          <td colspan="9" class="title" @click="showStage(stage.id_project_stage)"><strong>{{ stage.index }}. {{ stage.project_stage_name }}</strong></td>
        </tr>
        <tbody v-show="openedStage === stage.id_project_stage" :key="'hipotetik_' + stage.id_project_stage">
          <template v-for="impact in stage.impacts">
            <tr :key="'impact_'+ impact.id" class="title" animated>
              <td>
                <el-select
                  v-model="impact.id_change_type"
                  placeholder="Pilih"
                  filterable
                  allow-create
                  clearable
                  @change.native="handleOptionChange"
                >
                  <el-option
                    v-for="item in changeType"
                    :key="item.id"
                    :label="item.name"
                    :value="item.id"
                  />
                </el-select>
              </td>
              <td>{{ impact.component_name }}</td>
              <td>{{ impact.rona_awal_name }}</td>
              <td>{{ impact.initial_study_plan }}</td>
              <td>
                <template v-for="(pie, index) in pieParams">
                  <div :key="'pie_'+impact.id+'_'+pie.id" class="div-fka formA">
                    <p><strong>{{ pie.name }}</strong> {{ pie.description }}</p>
                    <el-input
                      v-model="impact.potential_impact_evaluation[index].text"
                      type="textarea"
                      :rows="3"
                      placeholder="Please input"
                      :value="impact.potential_impact_evaluation[index].text"
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
                <div v-show="!impact.is_hypothetical_significant" :key="'DTPH_'+impact.id" style="margin:1em 0;">
                  <el-switch v-model="impact.is_managed" active-text=" Dikelola" />
                </div>
              </td>
              <td>{{ impact.study_location }}</td>
              <td>
                <p><el-input-number v-model="impact.study_length_year" :min="0" :max="10" size="mini" /> tahun</p>
                <p><el-input-number v-model="impact.study_length_month" :min="0" :max="12" size="mini" /> bulan</p>

              </td>
              <td />
            </tr>
          </template>
        </tbody>
      </template>
      -->
    </table>
  </div>
</template>
<script>
import Resource from '@/api/resource';
const projectStageResource = new Resource('project-stages');
const impactIdtResource = new Resource('impact-identifications');
const changeTypeResource = new Resource('change-types');
const pieParamsResource = new Resource('pie-params');
const pieEntriesResource = new Resource('pie-entries');

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
      pies: [],
      impIds: [],
    };
  },
  mounted() {
    this.data = [];
    this.isLoading = true;
    this.idProject = parseInt(this.$route.params && this.$route.params.id);
    setTimeout(() => (this.isLoading = false), 2000);
    // this.getData();
  },
  methods: {
    handleOptionChange(option){
      /*
      if (typeof option === 'string' && option.length > 0) {
        const changeTypeResource = new Resource('change-types');
        changeTypeResource.store({
          id: 0,
          name: option,
        }).then((res) => {
          this.changeType.push(res.data);
          this.$emit('input', res.data.id);
        });
        console.log(option);
      }*/
      console('handleOptionChange', option);
    },
    handleSetData(data) {
      this.data = data;
      console.log(data);
    },
    handleSaveForm() {
      console.log('DampakHipotetik saving entries...', this.data);
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
          this.$message({
            message: error.message,
            type: 'error',
            duration: 5 * 1000,
          });
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
          if (this.pies){
            imp.potential_impact_evaluation.map((pie) => {
              pie.text = this.getPie(imp.id, pie.id_pie_param);
              console.log(pie.text);
            });
          }
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

      return data;
    },
    async getData() {
      console.log('starting getData at DampakHipotetik');
      this.data = [];
      this.isLoading = true;

      await changeTypeResource.list({})
        .then((res) => {
          this.changeType = res.data;
          this.changeType.push({
            name: 'Lainnya',
            id: 0,
          });
        });

      await pieParamsResource.list({}).then((res) => {
        this.pieParams = res.data;
      });

      const impIds = [];
      const prjStageList = await projectStageResource.list({});
      this.projectStages = prjStageList.data;
      const impactList = await impactIdtResource.list({
        id_project: this.idProject,
        join_tables: true,
      });

      impactList.data.map((imp) => {
        if (!(impIds.find((e) => e === imp.id))) {
          impIds.push(imp.id);
        }
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
        imp.potential_impact_evaluation = [];

        this.pieParams.forEach((e) => {
          imp.potential_impact_evaluation.push({
            id_impact_identification: imp.id,
            id_pie_param: e.id,
            text: null,
          });
        });
      });

      console.log(impIds);
      const pies = await pieEntriesResource.list({
        id_impact_identification: impIds,
      });
      this.pies = pies;

      var dataList = impactList.data;
      this.data = this.createDataArray(dataList, this.projectStages);
      this.isLoading = false;
      console.log('end getData...', this.data);
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
    getPie(id, index){
      const pie = this.pies.find((e) => ((e.id_impact_identification === id) &&
        (e.id_pie_param === index)));
      if (pie) {
        console.log(pie.text);
        return pie.text;
      }
      return '';
    },
    showStage(index){
      console.log(index);
      this.openedStage = (this.openedStage === index) ? null : index;
    },
    inPrimaryType(val){
      const ctype = this.changeType.find((c) => (c.id === val));
      console.log('in primary type', ctype);
      if (ctype){
        return true;
      }
      return false;
    },
  },
};
</script>

<style scoped>
  table td.title {
    height: 200% !important;
  }
  table td.title:hover {
    background-color: #efefef;
    cursor: pointer;
  }
  td .el-input-number--mini {
    width: 100px;

  }
 .el-tooltip__popper {
    max-width: 300px;
    line-height: 150%;
  }
</style>
