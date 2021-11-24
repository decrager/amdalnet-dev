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
    <table style="margin: 2em 0; border-collapse: collapse;">
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
      <tbody>

        <template v-for="stage in data">
          <tr :key="'stage_'+ stage.id" :data-index="stage.project_stage_name">
            <td colspan="9" class="title" @click="showStage(stage.id)"><strong>{{ stage.index }}. {{ stage.project_stage_name }}</strong></td>
          </tr>
          <tr v-show="openedStage === stage.id" :key="'hipotetik_' + stage.id" class="title" animated>
            <td>
              <el-select v-model="valueA" placeholder="Select">
                <el-option
                  v-for="item in kejadian"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
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
              <div class="div-fka formA">
                <p><strong>A. Besaran rencana Usaha dan/atau Kegiatan</strong> (yang
                  menyebabkan dampak tersebut dan rencana pengelolaan
                  lingkungan awal yang menjadi bagian rencana Usaha
                  dan/atau Kegiatan untuk menanggulangi dampak)</p>
                <el-input
                  v-model="textAA"
                  type="textarea"
                  :rows="2"
                  placeholder="Please input"
                />
              </div>
              <div class="div-fka formA">
                <p><strong>B. Kondisi rona lingkungan</strong> (yang ada termasuk kemampuan
                  mendukung Usaha dan/atau Kegiatan tersebut atau tidak)</p>
                <el-input
                  v-model="textAB"
                  type="textarea"
                  :rows="2"
                  placeholder="Please input"
                />
              </div>
              <div class="div-fka formA">
                <p><strong>C. Pengaruh rencana Usaha dan/atau Kegiatan</strong> (terhadap
                  kondisi Usaha dan/atau Kegiatan lain di sekitar lokasi</p>
                <el-input
                  v-model="textAC"
                  type="textarea"
                  :rows="2"
                  placeholder="Please input"
                />
              </div>
              <div class="div-fka formA">
                <p><strong>D. Intensitas perhatian masyarakat</strong> (terhadap rencana Usaha
                  dan/atau Kegiatan, baik harapan, kekhawatiran,
                  persetujuan
                  atau penolakan terhadap rencana Usaha
                  dan/atau Kegiatan)</p>
                <el-input
                  v-model="textAD"
                  type="textarea"
                  :rows="2"
                  placeholder="Please input"
                />
              </div>
              <div class="div-fka formA">
                <p><strong>E. Kesimpulan</strong></p>
                <el-input
                  v-model="textAE"
                  type="textarea"
                  :rows="2"
                  placeholder="Please input"
                />
              </div>
            </td>
            <td>
              <el-select v-model="vDPHs" placeholder="Select">
                <el-option
                  v-for="item in dPHs"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                  :disabled="item.disabled"
                />
              </el-select>
            </td>
            <td />
            <td>
              <p><el-input-number v-model="tahunA" :min="0" :max="10" size="mini" /> tahun</p>
              <p><el-input-number v-model="bulanA" :min="0" :max="12" size="mini" /> bulan</p>

            </td>
            <td />
          </tr>
        </template>

        <tr>
          <td colspan="9" class="title">NORMAL</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
import Resource from '@/api/resource';
const projectStageResource = new Resource('project-stages');
const impactIdtResource = new Resource('impact-identifications');

export default {
  name: 'DampakHipotetik',
  data() {
    return {
      idProject: 0,
      data: [],
      projectStages: [],
      openedStage: null,
    };
  },
  mounted() {
    this.idProject = parseInt(this.$route.params && this.$route.params.id);
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
      return dataFlat;
    },
    async getData() {
      console.log('starting getData at DampakHipotetik');
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
      this.data = this.createDataArray(dataList, this.projectStages);

      console.log('end of getData at DampakHipotetik');
      console.log(this.data);
    },
    showStage(index){
      this.openedStage = (this.openedStage === index) ? null : index;
    },
  },
};
</script>
