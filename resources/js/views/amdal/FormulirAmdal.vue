<template>
  <div v-if="isProjectAmdal" class="app-container">
    <el-card style="padding: 24px">
      <workflow />

      <el-tabs
        v-model="tabName"
        type="card"
      >
        <el-tab-pane label="Formulir Kerangka Acuan" name="fka" />
      </el-tabs>
      <el-button
        v-if="isFormulator"
        class="pull-right"
        type="success"
        size="small"
        icon="el-icon-check"
        :disabled="!isSubmitEnabled"
        @click="handleSaveForm()"
      >
        Simpan & Lanjutkan
      </el-button>
      <div class="clearfix" style="margin-bottom:1em;" />
      <!-- <h2>Formulir Kerangka Acuan</h2> -->

      <el-collapse :key="accordionKey" v-model="activeName" :accordion="true">
        <el-collapse-item name="1" title="PELINGKUPAN">
          <modul-pelingkupan />
          <!-- <pelingkupan
            v-if="activeName === '1'"
            @handleReloadVsaList="handleReloadVsaList"
          /> -->
        </el-collapse-item>
        <el-collapse-item name="2" title="MATRIKS IDENTIFIKASI DAMPAK">
          <matriks-identifikasi-dampak-table v-if="activeName === '2'" />
        </el-collapse-item>
        <el-collapse-item name="3" title="EVALUASI DAMPAK POTENSIAL & DAMPAK PENTING HIPOTETIK">
          <dampak-hipotetik-m-d />
          <!--
          <dampak-hipotetik
            v-if="activeName === '3'"
            @handleReloadVsaList="handleReloadVsaList"
          /> -->
          <!--
          <dampak-potensial
            @handleReloadVsaList="handleReloadVsaList"
          />
          <dampak-penting-hipotetik
            @handleReloadVsaList="handleReloadVsaList"
          />-->
        </el-collapse-item>
        <el-collapse-item name="4" title="PETA BATAS WILAYAH STUDI & PETA PENDUKUNG">
          <upload-peta-batas
            v-if="activeName === '4'"
            @handleReloadVsaList="handleReloadVsaList"
          />

        </el-collapse-item>
        <el-collapse-item name="5" title="METODE STUDI">
          <metode-studi
            v-if="activeName === '5'"
            @handleReloadVsaList="handleReloadVsaList"
            @handleEnableSubmitForm="handleEnableSubmitForm"
          />
        </el-collapse-item>
        <el-collapse-item title="Matriks Dampak Penting Hipotetik" name="6">
          <MatriksDPHTable v-if="activeName === '6'" />
        </el-collapse-item>
        <el-collapse-item title="Bagan Alir Pelingkupan" name="7">
          <bagan-alir v-if="activeName === '7'" />
        </el-collapse-item>
      </el-collapse>
    </el-card>
  </div>
</template>

<script>
// import Pelingkupan from './components/Pelingkupan.vue';
import ModulPelingkupan from './pelingkupan/index.vue';
import MatriksIdentifikasiDampakTable from './components/tables/MatriksIdentifikasiDampakTable.vue';
import MatriksDPHTable from './components/tables/MatriksDPHTable.vue';
// import DampakHipotetik from './components/DampakHipotetik.vue';
import DampakHipotetikMD from './components/DPDPH.vue';
import MetodeStudi from './components/MetodeStudi.vue';
import Workflow from '@/components/Workflow';
import BaganAlir from './components/BaganAlir.vue';
import UploadPetaBatas from './components/UploadPetaBatas.vue';

import Resource from '@/api/resource';
const projectResource = new Resource('projects');
const scopingResource = new Resource('scoping');

export default {
  name: 'FormulirAmdal',
  components: {
    // Pelingkupan,
    ModulPelingkupan, // dengan master komponen lokal
    MatriksIdentifikasiDampakTable,
    // DampakHipotetik,
    DampakHipotetikMD,
    MetodeStudi,
    MatriksDPHTable,
    Workflow,
    BaganAlir,
    UploadPetaBatas,
  },
  props: {
    data: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      sticky: 0,
      isProjectAmdal: false,
      accordionKey: 1,
      idProject: 0,
      isSubmitEnabled: false,
      scopingActive: true,
      matriksActive: false,
      dampakPotensialActive: false,
      dampakPentingHipotetikActive: false,
      metodeStudiActive: false,
      activeName: '1',
      tabName: 'fka',
    };
  },
  computed: {
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
  },
  mounted() {
    this.setProjectId();
    this.checkifAmdal();
    this.$store.dispatch('getStep', 3);
  },
  methods: {
    async checkifAmdal() {
      const project = await projectResource.get(this.idProject);
      if (project.required_doc !== 'AMDAL') {
        this.$message({
          message: 'Kegiatan tidak membutuhkan dokumen AMDAL',
          type: 'error',
          duration: 5 * 1000,
        });
        this.$router.push({
          name: 'FormulirUklUpl',
          params: this.idProject,
        });
      } else {
        this.isProjectAmdal = true;
      }
    },
    setProjectId(){
      const id = this.$route.params && this.$route.params.id;
      this.idProject = id;
    },
    async handleSaveForm() {
      const id = this.$route.params && this.$route.params.id;
      const checkFormulirKA = await scopingResource.list({
        check_formulir_ka: true,
        id_project: this.idProject,
      });
      if (!checkFormulirKA.data) {
        this.$message({
          message: 'Mohon lengkapi Formulir KA terlebih dahulu',
          type: 'error',
          duration: 5 * 1000,
        });
      } else {
        this.$message({
          message: 'Formulir KA berhasil disimpan',
          type: 'success',
          duration: 5 * 1000,
        });
        this.$router.push({
          name: 'DokumenAmdal',
          params: id,
        });
      }
    },
    handleEnableSubmitForm() {
      this.isSubmitEnabled = true;
    },
    handleReloadVsaList(tab) {
      this.accordionKey = this.accordionKey + 1;
      if (tab === 'pelingkupan') {
        this.activeName = '1';
      } else if (tab === 'matriks-identifikasi-dampak') {
        this.activeName = '2';
      } else if (tab === 'dampak-penting') {
        this.activeName = '3';
      } else if (tab === 'peta-batas') {
        this.activeName = '4';
      } else if (tab === 'metode-studi') {
        this.activeName = '5';
      } else if (tab === 'matriks-dph') {
        this.activeName = '6';
      } else if (tab === 'bagan-alir') {
        this.activeName = '7';
      }
    },
  },
};
</script>

<style>
.bagan__alir {
  display: none;
}

h2 {
  display:inline-block;
  margin-block-start: 0em;
}

.el-collapse-item__header {
  /* background-color: #296d36; */
  background-color: #1E5128;
  padding-left: 10px;
  font-size: large;
  font-weight: bold;
  color: rgb(196, 196, 196);
}
.el-collapse-item__content {
  padding-top: 10px;
}

table th, table td {word-break: normal !important; padding:.5em; line-height:1.2em; border: 1px solid #eee;}
table td { vertical-align: top !important;}
table thead  {background-color:#6cc26f !important; color: white !important;}
table td.title, table tr.title td, table.title td { text-align:left;}
div.div-fka {
  padding: 0.5em;
  margin-bottom: 0.6em;
  background-color: #fafafa;
}
</style>
