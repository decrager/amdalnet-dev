<template>
  <section id="actions" class="actions section">
    <div class="container">
      <el-row :gutter="20">
        <el-col :span="20">
          <h2 class="section__title actions__title__links">Layanan AMDALNET</h2>
        </el-col>
        <el-col :span="4">
          <h2 class="section__title actions__title__video">Video</h2>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="20">
          <div class="actions__box__links__wrapper" @click="() => showPenapisan = !showPenapisan">
            <div class="actions__box__links__icon">
              <img src="/images/list2.svg" alt="">
            </div>
            <div class="actions__box__links__desc">
              <h2 class="actions__box__links__desc__title"><span class="title__primary">Penapisan</span> Otomatis</h2>
            </div>
            <div>
              <img v-show="!showPenapisan" src="/images/right-arrow.svg" alt="" style="width: 70px; height: 85px;">
              <img v-show="showPenapisan" src="/images/down-arrow.svg" alt="" style="width: 70px; height: 85px;">
            </div>
          </div>
          <div class="content" :hidden="!showPenapisan">
            <h1 style="color: white">Simulasi Penapisan Dokumen</h1>
            <el-select
              v-model="study_approach"
              placeholder="Pilih"
              style="width: 25%; margin-bottom: 10px"
              size="medium"
            >
              <el-option
                v-for="item in studyApproachOptions"
                :key="item.value.id"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
            <div>
              <sub-project-table :list="listSubProject" :list-kbli="getListKbli" @cancel="cancelParam" />
              <el-button
                type="primary"
                @click="handleAddSubProjectTable"
              >+</el-button>
            </div>
            <h2 style="color: white; margin-top: 20px">Hasil penapisan dokumen lingkungan {{ last_result }}</h2>
          </div>
        </el-col>
        <el-col :span="4">
          <div class="thumbnailVideo">
            <!-- <img src="/images/penapisan.jpeg" alt=""> -->
            <iframe
              style="width:100%; height:128px;"
              :src="video1"
            />
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="20">
          <div class="actions__box__links__wrapper" @click="() => showPelingkupan = !showPelingkupan">
            <div class="actions__box__links__icon">
              <img src="/images/docPlus.svg" alt="">
            </div>
            <div class="actions__box__links__desc">
              <h2 class="actions__box__links__desc__title"><span class="title__primary">Asistensi</span> Pelingkupan</h2>
            </div>
            <div>
              <img v-show="!showPelingkupan" src="/images/right-arrow.svg" alt="" style="width: 70px; height: 85px;">
              <img v-show="showPelingkupan" src="/images/down-arrow.svg" alt="" style="width: 70px; height: 85px;">
            </div>
          </div>
          <div class="content" :hidden="!showPelingkupan" style="padding-top: 20px;">
            <h3 class="sub-title button" @click="handleSetMenu()"><img src="/images/docPlus.svg" style="width: 16px; height:16px" alt=""> Proses Persetujuan Lingkungan</h3>
            <h3 class="sub-title button" @click="showPubDialog"><img src="/images/pubques.svg" style="width: 16px; height:16px" alt=""> Pelayanan Public</h3>
            <h3 class="sub-title button" @click="showTrackingDialog"><img src="/images/search.svg" style="width: 16px; height:16px" alt=""> Tracking Dokumen</h3>
          </div>
        </el-col>
        <el-col :span="4">
          <div class="thumbnailVideo">
            <!-- <img src="/images/ka.jpeg" alt=""> -->
            <iframe
              style="width:100%; height:128px;"
              :src="video2"
            />
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="20">
          <div class="actions__box__links__wrapper" @click="() => showDigi = !showDigi">
            <div class="actions__box__links__icon">
              <img src="/images/digworkspace.svg" alt="">
            </div>
            <div class="actions__box__links__desc">
              <h2 class="actions__box__links__desc__title"><span class="title__primary">AMDAL</span> Digital Workspace</h2>
            </div>
            <div>
              <img v-show="!showDigi" src="/images/right-arrow.svg" alt="" style="width: 70px; height: 85px;">
              <img v-show="showDigi" src="/images/down-arrow.svg" alt="" style="width: 70px; height: 85px;">
            </div>
          </div>
          <div class="content" :hidden="!showDigi" style="padding-top: 20px;">
            <h3 class="sub-title button"><img src="/images/digworkspace2.svg" style="width: 16px; height:16px" alt=""> AMDAL Digital</h3>
            <router-link to="/webgis">
              <h3 class="sub-title button"><img src="/images/map3.svg" style="width: 16px; height:16px" alt=""> WebGIS AMDAL</h3>
            </router-link>
          </div>
        </el-col>
        <el-col :span="4">
          <div class="thumbnailVideo">
            <!-- <img src="/images/login.jpeg" alt=""> -->
            <iframe
              style="width:100%; height:128px;"
              :src="video3"
            />
          </div>
        </el-col>
      </el-row>
    </div>
    <public-question-dialog :show="showPublicQues" @cancel="() => showPublicQues = false" />
    <tracking-document-dialog
      :show="showTrackingDocument"
      @handleClose="closeAllDialog"
      @showTrackingDocumentDetail="showTrackingDocumentDetailDialog"
    />
    <tracking-document-detail-dialog
      v-if="project.id !== undefined"
      :show="showTrackingDocumentDetail"
      :project="project"
      @handleClose="closeAllDialog"
      @cancel="closeTrackingDocumentDetail"
    />
  </section>
</template>

<script>
import SubProjectTable from '../../project/components/SubProjectTable.vue';
// import AmdalSimulationDialog from '../components/AmdalSimulationDialog.vue';
import PublicQuestionDialog from '../components/PublicQuestionDialog.vue';
import TrackingDocumentDialog from '../components/TrackingDocumentDialog.vue';
import TrackingDocumentDetailDialog from '../components/TrackingDocumentDetailDialog.vue';
import axios from 'axios';
// import Materi from './Materi.vue';
// import Kebijakan from './Kebijakan.vue';

export default {
  name: 'ActionHome',
  components: {
    SubProjectTable,
    PublicQuestionDialog,
    TrackingDocumentDialog,
    TrackingDocumentDetailDialog,
    // Materi,
    // Kebijakan,
  },
  data() {
    return {
      study_approach: 'Tunggal',
      studyApproachOptions: [
        {
          value: 'Terpadu',
          label: 'Terpadu',
        },
        {
          value: 'Tunggal',
          label: 'Tunggal',
        },
        // {
        //   value: 'Kawasan',
        //   label: 'Kawasan',
        // },
      ],
      showPublicQues: false,
      showTrackingDocument: false,
      showTrackingDocumentDetail: false,
      listSubProject: [],
      showAmdalSimulation: false,
      last_result: '',
      showPenapisan: false,
      showPelingkupan: false,
      showDigi: false,
      showMateri: false,
      showKebijakan: false,
      project: {},
      video1: '',
      video2: '',
      video3: '',
    };
  },
  computed: {
    getListKbli() {
      return this.$store.getters.kblis;
    },
  },
  created(){
    this.getAll();
  },
  methods: {
    getAll() {
      axios
        .get(`/api/tutorial-video`)
        .then((response) => {
          response.data.data.map((val, i) => {
            if (val.tutorial_type === 'Amdal Digital Workspace'){
              this.video3 = val.url_video;
            }
            if (val.tutorial_type === 'Penapisan Otomatis'){
              this.video1 = val.url_video;
            }
            if (val.tutorial_type === 'Asistensi Pelingkupan'){
              this.video2 = val.url_video;
            }
          });
        });
    },
    closeAllDialog() {
      this.showPublicQues = false;
      this.showTrackingDocumentDetail = false;
      this.showTrackingDocument = false;
    },
    showPubDialog(){
      this.closeAllDialog();
      this.showPublicQues = true;
    },
    showTrackingDialog(){
      this.closeAllDialog();
      this.showTrackingDocument = true;
    },
    showTrackingDocumentDetailDialog(project){
      this.project = project;
      this.closeAllDialog();
      this.showTrackingDocumentDetail = true;
    },
    closeTrackingDocumentDetail() {
      this.showTrackingDocumentDetail = false;
      this.project = {};
    },
    cancelParam(){
      this.calculateListSubProjectResult();
      this.calculateChoosenProject();
    },
    handleAddSubProjectTable(){
      this.listSubProject.push({});
    },
    sumResult(listParam){
      let result = '';
      let result_risk = '';
      let scale = '';
      let scale_unit = '';
      let amdal_type = '';

      for (let i = 0; i < listParam.length; i++) {
        if (listParam[i].result === 'AMDAL'){
          result = listParam[i].result;
          result_risk = listParam[i].result_risk;
          scale = listParam[i].scale;
          scale_unit = listParam[i].scale_unit;
          amdal_type = listParam[i].amdal_type;
          break;
        } else if (listParam[i].result === 'UKL-UPL'){
          result = listParam[i].result;
          result_risk = listParam[i].result_risk;
          scale = listParam[i].scale;
          scale_unit = listParam[i].scale_unit;
        } else if (listParam[i].result === 'SPPL' && result !== 'UKL-UPL'){
          result = listParam[i].result;
          result_risk = listParam[i].result_risk;
          scale = listParam[i].scale;
          scale_unit = listParam[i].scale_unit;
        }
      }
      return { result, result_risk, scale, scale_unit, amdal_type };
    },
    calculateListSubProjectResult(){
      this.listSubProject = this.listSubProject.map(e => {
        e.listSubProjectParamsCalc = e.listSubProjectParams.filter(e => e.used);

        const { result, result_risk, scale, scale_unit, amdal_type } = this.sumResult(e.listSubProjectParamsCalc);
        e.result = result;
        e.result_risk = result_risk;
        e.scale = scale;
        e.scale_unit = scale_unit;
        e.amdal_type = amdal_type;

        return e;
      });
    },
    calculateChoosenProject(){
      // console.log('project tanpa filter', this.currentProject);
      const listMainProjectAmdal = this.listSubProject.filter(e => {
        if (this.study_approach === 'Tunggal'){
          return e.type === 'utama' && e.result === 'AMDAL';
        } else if (this.study_approach === 'Terpadu'){
          return e.result === 'AMDAL';
        }
      });
      const listMainProjectUklUpl = this.listSubProject.filter(e => {
        if (this.study_approach === 'Tunggal'){
          return e.type === 'utama' && e.result === 'UKL-UPL';
        } else if (this.study_approach === 'Terpadu'){
          return e.result === 'UKL-UPL';
        }
      });
      const listMainProjectSppl = this.listSubProject.filter(e => {
        if (this.study_approach === 'Tunggal'){
          return e.type === 'utama' && e.result === 'SPPL';
        } else if (this.study_approach === 'Terpadu'){
          return e.result === 'SPPL';
        }
      });

      // console.log('listAmdal', listMainProjectAmdal);
      let choosenProject = '';

      if (listMainProjectAmdal.length !== 0){
        choosenProject = listMainProjectAmdal[0];
      } else if (listMainProjectUklUpl.length !== 0) {
        choosenProject = listMainProjectUklUpl[0];
      } else if (listMainProjectSppl.length !== 0) {
        choosenProject = listMainProjectSppl[0];
      }

      // console.log('choosenProject', choosenProject);

      // add choosen project to current project
      // this.kbli = choosenProject.kbli;
      // this.required_doc = choosenProject.result;
      // this.risk_level = choosenProject.result_risk;
      // this.result_risk = choosenProject.result_risk;
      // this.sector = choosenProject.sector;
      // this.authority = 'Pusat';
      // this.plan_type = choosenProject.name;
      // this.scale = choosenProject.scale;
      // this.scale_unit = choosenProject.scale_unit;
      // this.listSubProjectParams = choosenProject.listSubProjectParams;
      const at = choosenProject.amdal_type ? choosenProject.amdal_type : '';
      this.last_result = choosenProject.result + ' ' + at;
    },
    handleSetMenu(){
      this.$router.push('/login');
    },
  },
};
</script>
<style>
  .content {
  padding: 0 18px;
  overflow: hidden;
  background-color: #20492E;
  padding-bottom: 10px;
  }

  .sub-title {
    color: white;
    margin-bottom: 5px
  }
  .sub-title:hover {
    cursor: pointer;
    color: #F6993F;
  }
  .thumbnailVideo img{height: 121px;width: 100%;margin-top: 0.2rem;}
</style>
