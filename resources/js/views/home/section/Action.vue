<template>
  <section id="actions" class="actions section">
    <div class="actions__container container grid">
      <div class="actions__data__links">
        <h2 class="section__title actions__title__links">Layanan AMDALNET</h2>
        <div class="actions__box__links__wrapper" style="margin-bottom: 0" @click="() => showPenapisan = !showPenapisan">
          <div class="actions__box__links__icon">
            <img src="/images/list2.svg" alt="">
          </div>
          <div class="actions__box__links__desc">
            <h2 class="actions__box__links__desc__title"><span class="title__primary">Penapisan</span> Otomatis</h2>
            <!-- <span class="actions__box__links__desc__subtitle">Pengajuan Persetujuan lingkungan untuk usaha dan kegiatan</span> -->
          </div>
          <div>
            <img v-show="!showPenapisan" src="/images/right-arrow.svg" alt="" style="width: 70px; height: 85px;">
            <img v-show="showPenapisan" src="/images/down-arrow.svg" alt="" style="width: 70px; height: 85px;">
          </div>
        </div>
        <div class="content" :hidden="!showPenapisan">
          <h1 style="color: white">Simulasi Penapisan Dokumen</h1>
          <div>
            <sub-project-table :list="listSubProject" :list-kbli="getListKbli" @cancel="cancelParam" />
            <el-button
              type="primary"
              @click="handleAddSubProjectTable"
            >+</el-button>
          </div>
          <h2 style="color: white; margin-top: 20px">Hasil Akhir Penapisan {{ last_result }}</h2>
        </div>

        <div class="actions__box__links__wrapper" style="margin-top: 20px; margin-bottom: 0" @click="() => showPelingkupan = !showPelingkupan">
          <div class="actions__box__links__icon">
            <img src="/images/map3.svg" alt="">
          </div>
          <div class="actions__box__links__desc">
            <h2 class="actions__box__links__desc__title"><span class="title__primary">Asistensi</span> Pelingkupan</h2>
            <!-- <span class="actions__box__links__desc__subtitle">Peta tematik penyebaran daerah yang sudah mendapat izin AMDAL</span> -->
          </div>
          <div>
            <img v-show="!showPelingkupan" src="/images/right-arrow.svg" alt="" style="width: 70px; height: 85px;">
            <img v-show="showPelingkupan" src="/images/down-arrow.svg" alt="" style="width: 70px; height: 85px;">
          </div>
        </div>
        <div class="content" :hidden="!showPelingkupan" style="padding-top: 20px;">
          <h3 class="sub-title"><img src="/images/tracking.svg" style="width: 16px; height:16px" alt=""> Proses Persetujuan Lingkungan</h3>
          <h3 class="sub-title" @click="showPubDialog"><img src="/images/tracking.svg" style="width: 16px; height:16px" alt=""> Pelayanan Public</h3>
          <h3 class="sub-title"><img src="/images/tracking.svg" style="width: 16px; height:16px" alt=""> Tracking Dokumen</h3>
        </div>

        <div class="actions__box__links__wrapper" style="margin-top: 20px; margin-bottom: 0" @click="() => showDigi = !showDigi">
          <div class="actions__box__links__icon">
            <img src="/images/communication.svg" alt="">
          </div>
          <div class="actions__box__links__desc">
            <h2 class="actions__box__links__desc__title"><span class="title__primary">AMDAL</span> Digital Workspace</h2>
            <!-- <span class="actions__box__links__desc__subtitle">Konsultasi untuk pengajuan izin lingkungan</span> -->
          </div>
          <div>
            <img v-show="!showDigi" src="/images/right-arrow.svg" alt="" style="width: 70px; height: 85px;">
            <img v-show="showDigi" src="/images/down-arrow.svg" alt="" style="width: 70px; height: 85px;">
          </div>
        </div>
        <div class="content" :hidden="!showDigi" style="padding-top: 20px;">
          <h3 class="sub-title"><img src="/images/list2.svg" style="width: 16px; height:16px" alt=""> AMDAL Digital</h3>
          <router-link to="/webgis">
            <h3 class="sub-title"><img src="/images/map3.svg" style="width: 16px; height:16px" alt=""> WebGIS AMDAL</h3>
          </router-link>
        </div>

        <!-- <div class="actions__box__links__wrapper">
          <div class="actions__box__links__icon">
            <img src="/images/tracking.svg" alt="">
          </div>
          <div class="actions__box__links__desc">
            <h2 class="actions__box__links__desc__title"><span class="title__primary">Tracking</span> Dokumen</h2>
            <span class="actions__box__links__desc__subtitle">Layanan untuk melacak status dokumen AMDAL</span>
          </div>
        </div> -->
      </div>

      <div class="actions__data__video">
        <h2 class="section__title actions__title__video">Video</h2>
        <div class="actions__video__wrapper">
          <img src="/images/no-video.png" alt="">
        </div>
        <div class="actions__video__wrapper">
          <img src="/images/no-video.png" alt="">
        </div>
        <div class="actions__video__wrapper">
          <img src="/images/no-video.png" alt="">
        </div>
        <div class="actions__video__wrapper">
          <img src="/images/no-video.png" alt="">
        </div>
      </div>
    </div>
    <!-- <div class="simulations__box__links__wrapper">
      <h1 style="color: white">Simulasi Penapisan Dokumen</h1>
      <div>
        <sub-project-table :list="listSubProject" :list-kbli="getListKbli" @cancel="cancelParam" />
        <el-button
          type="primary"
          @click="handleAddSubProjectTable"
        >+</el-button>
      </div>
      <h2 style="color: white; margin-top: 20px">Hasil Akhir Penapisan {{ last_result }}</h2>
    </div> -->
    <public-question-dialog :show="showPublicQues" @cancel="() => showPublicQues = false" />
  </section>
</template>

<script>
import SubProjectTable from '../../project/components/SubProjectTable.vue';
// import AmdalSimulationDialog from '../components/AmdalSimulationDialog.vue';
import PublicQuestionDialog from '../components/PublicQuestionDialog.vue';
export default {
  name: 'ActionHome',
  components: { SubProjectTable, PublicQuestionDialog },
  data() {
    return {
      showPublicQues: false,
      listSubProject: [],
      showAmdalSimulation: false,
      last_result: '',
      showPenapisan: false,
      showPelingkupan: false,
      showDigi: false,
    };
  },
  computed: {
    getListKbli() {
      return this.$store.getters.kblis;
    },
  },
  methods: {
    showPubDialog(){
      console.log('kepencet');
      this.showPublicQues = true;
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
      const listMainProjectAmdal = this.listSubProject.filter(e => e.type === 'utama' && e.result === 'AMDAL');
      const listMainProjectUklUpl = this.listSubProject.filter(e => e.type === 'utama' && e.result === 'UKL-UPL');
      const listMainProjectSppl = this.listSubProject.filter(e => e.type === 'utama' && e.result === 'SPPL');

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

      this.last_result = choosenProject.result + ' ' + choosenProject.amdal_type;
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
</style>
