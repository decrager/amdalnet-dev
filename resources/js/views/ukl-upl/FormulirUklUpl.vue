<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <workflow />
      <h2>Formulir Kerangka Acuan</h2>
      <span>
        <el-button
          class="pull-right"
          type="success"
          size="small"
          icon="el-icon-check"
          :disabled="!isSubmitEnabled"
          @click="handleSaveForm()"
        >
          Simpan & Lanjutkan
        </el-button>
      </span>
      <el-collapse :key="accordionKey" v-model="activeName" :accordion="true">
        <el-collapse-item name="1" title="PELINGKUPAN">
          <pelingkupan
            @handleReloadVsaList="handleReloadVsaList"
          />
        </el-collapse-item>
        <el-collapse-item name="2" title="MATRIKS IDENTIFIKASI DAMPAK">
          <matriks-identifikasi-dampak-table
            @handleReloadVsaList="handleReloadVsaList"
          />
        </el-collapse-item>
        <el-collapse-item name="3" title="PETA BATAS WILAYAH STUDI & PETA PENDUKUNG">
          <upload-peta-batas
            @handleReloadVsaList="handleReloadVsaList"
          />

        </el-collapse-item>
        <el-collapse-item name="4" title="DAMPAK POTENSIAL & DAMPAK PENTING HIPOTETIK">
          <dampak-hipotetik
            @handleReloadVsaList="handleReloadVsaList"
          />
          <!--
          <dampak-potensial
            @handleReloadVsaList="handleReloadVsaList"
          />
          <dampak-penting-hipotetik
            @handleReloadVsaList="handleReloadVsaList"
          />-->
        </el-collapse-item>
        <el-collapse-item name="5" title="METODE STUDI">
          <metode-studi
            @handleReloadVsaList="handleReloadVsaList"
            @handleEnableSubmitForm="handleEnableSubmitForm"
          />
        </el-collapse-item>
        <el-collapse-item title="Matriks Dampak Penting Hipotetik" name="6">
          <matriks-dampak-penting-hipotetik
            @handleReloadVsaList="handleReloadVsaList"
          />
        </el-collapse-item>
        <el-collapse-item title="Bagan Alir Pelingkupan" name="7">
          <bagan-alir />
        </el-collapse-item>
      </el-collapse>
    </el-card>
  </div>
</template>

<script>
import Pelingkupan from './components/Pelingkupan.vue';
// import MatrikIdentifikasiDampak from './components/MatrikIdentifikasiDampak.vue';
import MatriksIdentifikasiDampakTable from './components/tables/MatriksIdentifikasiDampakTable.vue';
import MatriksDampakPentingHipotetik from './components/MatriksDampakPentingHipotetik.vue';
import DampakHipotetik from './components/DampakHipotetik.vue';
import MetodeStudi from './components/MetodeStudi.vue';
import Workflow from '@/components/Workflow';
import BaganAlir from './components/BaganAlir.vue';
import UploadPetaBatas from './components/UploadPetaBatas.vue';

export default {
  name: 'FormulirUklUpl',
  components: {
    Pelingkupan,
    MatriksIdentifikasiDampakTable,
    DampakHipotetik,
    MetodeStudi,
    MatriksDampakPentingHipotetik,
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
      accordionKey: 1,
      idProject: 0,
      isSubmitEnabled: false,
      scopingActive: true,
      matriksActive: false,
      dampakPotensialActive: false,
      dampakPentingHipotetikActive: false,
      metodeStudiActive: false,
      activeName: '1',
    };
  },
  mounted() {
    this.setProjectId();
    this.$store.dispatch('getStep', 3);
    this.data;
  },
  methods: {
    setProjectId(){
      const id = this.$route.params && this.$route.params.id;
      this.idProject = id;
    },
    handleSaveForm() {
      const id = this.$route.params && this.$route.params.id;
      this.$router.push({
        name: 'DokumenUklUpl',
        params: id,
      });
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
      } else if (tab === 'peta-batas') {
        this.activeName = '3';
      } else if (tab === 'dampak-penting') {
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
