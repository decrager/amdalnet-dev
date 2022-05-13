<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <workflow-ukl />
      <h2>Matriks UKL UPL</h2>
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
      <el-collapse v-if="isFormulirComplete" v-model="activeName" :accordion="true" @change="checkIfFormComplete">
        <el-collapse-item name="1" title="MATRIKS UKL">
          <matriks-ukl-table
            v-if="activeName === '1'"
            @handleCheckProjectMarking="handleCheckProjectMarking"
          />
        </el-collapse-item>
        <el-collapse-item name="2" title="MATRIKS UPL" :disabled="matriksUplDisabled">
          <matriks-upl-table
            v-if="activeName === '2'"
            @handleCheckProjectMarking="handleCheckProjectMarking"
          />
        </el-collapse-item>
        <el-collapse-item name="3" title="DOKUMEN PENDUKUNG" :disabled="dokPendukungDisabled">
          <dokumen-pendukung
            v-if="activeName === '3'"
            @handleDokPendukungUploaded="handleDokPendukungUploaded"
          />
        </el-collapse-item>
        <el-collapse-item name="4" title="PETA TITIK PEMANTAUAN & PENGELOLAAN" :disabled="petaBatasDisabled">
          <upload-peta-batas-ukl-upl
            v-if="activeName === '4'"
            @handleEnableSimpanLanjutkan="handleEnableSimpanLanjutkan"
            @handlePetaBatasUploaded="handlePetaBatasUploaded"
          />
        </el-collapse-item>
      </el-collapse>
    </el-card>
  </div>
</template>

<script>
import MatriksUklTable from './components/tables/MatriksUklTable.vue';
import MatriksUplTable from './components/tables/MatriksUplTable.vue';
import DokumenPendukung from './components/DokumenPendukung.vue';
import WorkflowUkl from '@/components/WorkflowUkl';
import Resource from '@/api/resource';
import axios from 'axios';
import UploadPetaBatasUklUpl from './components/UploadPetaBatasUklUpl.vue';
const impactIdtResource = new Resource('impact-identifications');

export default {
  name: 'MatriksUklUpl',
  components: {
    MatriksUklTable,
    MatriksUplTable,
    DokumenPendukung,
    WorkflowUkl,
    UploadPetaBatasUklUpl,
  },
  data() {
    return {
      isSubmitEnabled: false,
      isFormulirComplete: false,
      vsaListKey: 0,
      uklActive: true,
      uplActive: false,
      activeName: '1',
      petaBatasUploaded: false,
      matriksUplDisabled: false, // enabled if 'fill-uklupl-matrix-ukl'
      dokPendukungDisabled: false, // enabled if 'fill-uklupl-matrix-upl'
      petaBatasDisabled: false, // enabled if dokPendukung is done
      // matriksUplDisabled: true,
      // dokPendukungDisabled: true,
      // petaBatasDisabled: true,
    };
  },
  mounted() {
    this.checkImpactIdentificationData();
    this.checkIfFormComplete();
    this.$store.dispatch('getStep', 4);
  },
  methods: {
    async checkImpactIdentificationData() {
      // check if nominal & units has been filled
      // if not, show error popup, then redirect to formulir page
      const idProject = parseInt(this.$route.params && this.$route.params.id);
      const { data } = await impactIdtResource.list({
        id_project: idProject,
      });
      var completed = 0;
      data.map((imp) => {
        if (imp.id_change_type !== null && (imp.id_unit !== null || imp.unit !== null)) {
          completed++;
        }
      });
      if (completed !== data.length || data.length === 0) {
        this.$message({
          message: 'Mohon lengkapi formulir UKL-UPL terlebih dahulu',
          type: 'error',
          duration: 5 * 1000,
        });
        this.$router.push({
          name: 'FormulirUklUpl',
          params: idProject,
        });
      } else {
        this.isFormulirComplete = true;
      }
    },
    async checkIfFormComplete() {
      const idProject = parseInt(this.$route.params && this.$route.params.id);
      await axios.get('api/matriks-ukl-upl/is-form-complete/' + idProject)
        .then(response => {
          this.isSubmitEnabled = response.data.data && this.petaBatasUploaded;
        });
    },
    handleEnableSimpanLanjutkan() {
      this.checkIfFormComplete();
    },
    handlePetaBatasUploaded() {
      this.petaBatasUploaded = true;
    },
    async handleCheckProjectMarking() {
      const idProject = parseInt(this.$route.params && this.$route.params.id);
      await axios.get('api/matriks-ukl-upl/get-project-marking/' + idProject)
        .then(response => {
          if (parseInt(response.data.status) === 200) {
            const projectMarking = response.data.data;
            if (projectMarking === 'fill-uklupl-matrix-ukl') {
              this.matriksUplDisabled = false;
            } else if (projectMarking === 'fill-uklupl-matrix-upl') {
              this.dokPendukungDisabled = false;
            }
          }
        });
    },
    handleDokPendukungUploaded() {
      this.petaBatasDisabled = false;
    },
    handleSaveForm() {
      const id = this.$route.params && this.$route.params.id;
      this.$router.push({
        name: 'DokumenUklUpl',
        params: id,
      });
    },
  },
};
</script>

<style>
h2 {
  display:inline-block;
  margin-block-start: 0em;
}

</style>
