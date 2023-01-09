<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <workflow-ukl />
      <h2>Matriks UKL UPL</h2>
      <span>
        <el-button
          v-if="isFormulator && isPerbaikan"
          class="pull-right"
          type="danger"
          size="small"
          icon="el-icon-close"
          style="margin-left: .5rem;"
          @click="handleNotSave()"
        >
          Tidak
        </el-button>
        <el-button
          v-if="isFormulator && !isPerbaikan"
          class="pull-right"
          type="success"
          size="small"
          icon="el-icon-check"
          :disabled="!isSubmitEnabled"
          @click="handleSaveForm()"
        >
          Simpan & Lanjutkan
        </el-button>
        <el-button
          v-if="isFormulator && isPerbaikan"
          class="pull-right"
          type="success"
          size="small"
          icon="el-icon-check"
          @click="handleSaveForm(isPerbaikan)"
        >
          Ya
        </el-button>
        <span v-if="isPerbaikan" class="pull-right" style="font-weight: bold; margin-top: .5rem;">Apakah Ada Perbaikan pada Matriks UKL UPL ini?</span>
      </span>
      <el-collapse v-if="isFormulirComplete" v-model="activeName" :accordion="true" @change="checkIfFormComplete">
        <el-collapse-item name="1" title="MATRIKS UKL">
          <matriks-ukl-table
            v-if="activeName === '1'"
            :perbaikan="isPerbaikan"
            @handleCheckProjectMarking="handleCheckProjectMarking"
          />
        </el-collapse-item>
        <el-collapse-item name="2" title="MATRIKS UPL" :disabled="matriksUplDisabled">
          <matriks-upl-table
            v-if="activeName === '2'"
            :perbaikan="isPerbaikan"
            @handleCheckProjectMarking="handleCheckProjectMarking"
          />
        </el-collapse-item>
        <el-collapse-item name="3" title="DOKUMEN PENDUKUNG" :disabled="dokPendukungDisabled">
          <dokumen-pendukung
            v-if="activeName === '3'"
            @handleDokPendukungUploaded="handleDokPendukungUploaded"
          />
        </el-collapse-item>
        <el-collapse-item name="4" title="PETA TITIK PENGELOLAAN & PEMANTAUAN" :disabled="petaBatasDisabled">
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
const saveStatus = new Resource('save-status');

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
      idProject: 0,
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
  computed: {
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
    isPerbaikan() {
      return this.$route.query.perbaikan;
    },
  },
  mounted() {
    this.setProjectId();
    this.checkImpactIdentificationData();
    this.checkIfFormComplete();
    this.getMarking();
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
    async handleNotSave() {
      const data = await axios.get(
        `/api/dokumen-ukl-upl/${this.idProject}`
      );
      this.$router.push({
        name: 'projectWorkspace',
        params: {
          id: this.idProject,
          versi: data.data.versi_doc,
          filename: data.data.file_name,
          createTime: data.data.create_time,
          workspaceType: 'ukl-upl',
        },
        query: {
          perbaikan: true,
          isFixFormulirUklUpl: this.$route.query.isFixFormulirUklUpl,
          isFixMatriksUklUpl: false,
        },
      });
      this.handleCetakMatriks();
    },
    async checkIfFormComplete() {
      const idProject = parseInt(this.$route.params && this.$route.params.id);
      await axios.get('api/matriks-ukl-upl/is-form-complete/' + idProject)
        .then(response => {
          this.isSubmitEnabled = response.data.data && this.petaBatasUploaded;
        });
    },
    setProjectId(){
      const id = this.$route.params && this.$route.params.id;
      this.idProject = id;
    },
    async getMarking() {
      await this.$store.dispatch('getMarking', this.idProject);
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
    async handleCetakMatriks() {
      await axios.get(
        `/api/matriks-ukl-upl/${this.idProject}`
      );
    },
    async handleSaveForm(perbaikan) {
      const projectId = this.$route.params && this.$route.params.id;
      const sections = ['matriks.ukl', 'matriks.upl'];
      const result = await saveStatus.list({
        sections,
        project_id: projectId,
      });
      const errors = [];
      sections.map((value, index) => {
        result.map((val, ind) => {
          if (value === val.section && val.temporary) {
            errors.push(value);
          }
        });
      });
      if (errors.length >= 1) {
        this.$alert(
          'Silahkan lengkapi data matriks UKL atau matriks UPL kemudian pilih tombol Simpan Perubahan pada masing-masing matriks',
          {
            confirmButtonText: 'OK',
          }
        );
      } else {
        if (perbaikan) {
          this.$confirm(
            'Pastikan data perbaikan anda sudah terisi semua pada <b> Matriks UKL UPL </b>. Apakah anda yakin akan melanjutkan ke <b> Workspace UKL UPL </b> ?',
            'Peringatan',
            {
              confirmButtonText: 'OK',
              cancelButtonText: 'Batal',
              type: 'warning',
              dangerouslyUseHTMLString: true,
            }).then(() => {
            axios.get(
              `/api/dokumen-ukl-upl/${this.idProject}`
            ).then(res => {
              this.handleCetakMatriks();
              this.$router.push({
                name: 'projectWorkspace',
                params: {
                  id: this.idProject,
                  versi: res.data.versi_doc,
                  filename: res.data.file_name,
                  createTime: res.data.create_time,
                  workspaceType: 'ukl-upl',
                },
                query: {
                  perbaikan: true,
                  isFixFormulirUklUpl: this.$route.query.isFixFormulirUklUpl,
                  isFixMatriksUklUpl: true,
                },
              });
            });
          });
        } else {
          this.$router.push({
            name: 'DokumenUklUpl',
            params: projectId,
          });
        }
      }
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
