<template>
  <div v-if="isProjectUklUpl" class="app-container" style="padding: 24px">
    <el-card>
      <workflow-ukl />
      <h2>Formulir UKL UPL</h2>
      <span>
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
      </span>
      <el-collapse :key="accordionKey" v-model="activeName" :accordion="true">
        <el-collapse-item name="1" title="IDENTIFIKASI KOMPONEN KEGIATAN DAN KOMPONEN LINGKUNGAN">
          <!-- <pelingkupan
            v-if="activeName === '1'"
            @handleReloadVsaList="handleReloadVsaList"
          /> -->
          <modul-pelingkupan v-if="activeName === '1'" />
        </el-collapse-item>
        <el-collapse-item name="2" title="MATRIKS IDENTIFIKASI DAMPAK">
          <matriks-identifikasi-dampak-table v-if="activeName === '2'" />
        </el-collapse-item>
        <el-collapse-item name="3" title="JENIS DAN BESARAN DAMPAK">
          <jenis-besaran-dampak-table
            v-if="activeName === '3'"
            @handleEnableSimpanLanjutkan="handleEnableSimpanLanjutkan"
          />
        </el-collapse-item>
      </el-collapse>
    </el-card>
  </div>
</template>

<script>
// import Pelingkupan from '@/views/amdal/components/Pelingkupan.vue';
import ModulPelingkupan from '@/views/amdal/pelingkupan/index.vue';
import MatriksIdentifikasiDampakTable from '@/views/amdal/components/tables/MatriksIdentifikasiDampakTable.vue';
import JenisBesaranDampakTable from './components/tables/JenisBesaranDampakTable.vue';
import WorkflowUkl from '@/components/WorkflowUkl';
import Resource from '@/api/resource';
const projectResource = new Resource('projects');

export default {
  name: 'FormulirUklUpl',
  components: {
    // Pelingkupan,
    ModulPelingkupan,
    MatriksIdentifikasiDampakTable,
    JenisBesaranDampakTable,
    WorkflowUkl,
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
      isProjectUklUpl: false,
    };
  },
  computed: {
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
  },
  mounted() {
    this.setProjectId();
    this.$store.dispatch('getStep', 3);
    this.checkifUklUpl();
    this.data;
  },
  methods: {
    setProjectId(){
      const id = this.$route.params && this.$route.params.id;
      this.idProject = id;
    },
    async checkifUklUpl() {
      const project = await projectResource.get(this.idProject);
      if (project.required_doc === 'AMDAL') {
        this.$message({
          message: 'Kegiatan membutuhkan dokumen AMDAL',
          type: 'error',
          duration: 5 * 1000,
        });
        this.$router.push({
          name: 'FormulirAmdal',
          params: this.idProject,
        });
      } else {
        this.isProjectUklUpl = true;
      }
    },
    handleSaveForm() {
      const id = this.$route.params && this.$route.params.id;
      this.$router.push({
        name: 'MatriksUklUpl',
        params: id,
      });
    },
    handleEnableSimpanLanjutkan() {
      this.isSubmitEnabled = true;
    },
    handleReloadVsaList(tab) {
      this.accordionKey = this.accordionKey + 1;
      if (tab === 'pelingkupan') {
        this.activeName = '1';
      } else if (tab === 'matriks-identifikasi-dampak') {
        this.activeName = '2';
      } else if (tab === 'jenis-besaran-dampak') {
        this.activeName = '3';
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
