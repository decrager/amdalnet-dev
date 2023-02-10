<template>
  <div class="app-container" style="padding: 24px">
    <el-card v-loading="loading">
      <workflow-ukl />
      <div role="alert" class="el-alert el-alert--error is-dark" style="margin-top: 10px">
        <div class="el-alert__content">
          <p class="el-alert__description">
            Pastikan Workspace UKL UPL sudah dilengkapi
          </p>
        </div>
      </div>
      <h2>
        Submit Formulir UKL UPL
        <span v-if="isFormulator">ke Pemrakarsa</span>
      </h2>
      <div>
        <el-button :loading="loading" type="primary" @click="workspace">
          Workspace
        </el-button>
        <a v-if="showDocument" class="btn-docx" :href="downloadDocxPath" :download="`ka-${projectName}.docx`">
          Export to .DOCX
        </a>
        <a v-if="showDocument" class="btn-pdf" :href="urlPdf" :download="`ka-${projectName}.pdf`">
          Export to .PDF
        </a>
        <a v-if="showBukti" class="btn-submit" :href="urlBukti" :download="`bukti-submit-ka.pdf`">
          Unduh Bukti Pengiriman
        </a>
        <div
          v-if="isFormulator"
          role="alert"
          class="el-alert el-alert--error is-dark"
          style="width: 50%;margin-top: 10px"
        >
          <div class="el-alert__content">
            <div class="el-alert__description">
              <p style="font-weight: bold;">PERHATIAN</p>
              <p>Tombol Buat Ulang Dokumen diperuntukan apabila Workspace UKL-UPL anda tidak terbuat dengan sempurna.
                Tombol Buat Ulang Dokumen akan mereset progres Workspace yang sudah ada sebelumnya ke format awal.</p>
            </div>
          </div>
        </div>
        <el-button
          v-if="isFormulator"
          :loading="loading"
          type="primary"
          class="btn-resetworkspace"
          style="margin-top: 10px"
          @click="regenerateDocx"
        >
          Buat ulang dokumen
        </el-button>
        <!-- <span style="float: right;">
          <span style="font-weight: bold; padding-right: 10px;">Versi Workspace</span>
          <el-select v-model="version" filterable placeholder="Pilih Versi Workspace" size="mini" @change="getData(version)">
            <el-option
              v-for="item in versiDoc"
              :key="item.versi"
              :label="item.versi"
              :="item.versi"
            />
          </el-select>
        </span> -->
      </div>
      <el-row :gutter="20" style="margin-top: 20px">
        <el-col :sm="24" :md="14">
          <div class="grid-content bg-purple" />
          <iframe
            v-if="urlPdf !== null || showDocument"
            :src="`https://docs.google.com/gview?url=${encodeURIComponent(
              urlPdf
            )}&embedded=true`"
            width="100%"
            height="723px"
            frameborder="0"
          />
        </el-col>
        <el-col :sm="24" :md="10">
          <ReviewPenyusun v-if="isFormulator" :documenttype="'UKL UPL'" />
          <ReviewPemrakarsa v-if="isInitiator" :documenttype="'UKL UPL'" @submitPemrakarsa="submitPemrakarsa" />
          <Lampiran />
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import ReviewPenyusun from '@/views/review-dokumen/ReviewPenyusun';
import ReviewPemrakarsa from '@/views/review-dokumen/ReviewPemrakarsa';
import axios from 'axios';
import WorkflowUkl from '@/components/WorkflowUkl';
import Lampiran from '../review-dokumen/Lampiran.vue';
import Resource from '@/api/resource';
const kaReviewsResource = new Resource('ka-reviews');
const projectsResource = new Resource('projects');

export default {
  components: {
    WorkflowUkl,
    ReviewPenyusun,
    ReviewPemrakarsa,
    Lampiran,
  },
  data() {
    return {
      loading: false,
      projectName: '',
      idProject: 0,
      urlPdf: null,
      urlBukti: null,
      createTime: null,
      showDocument: false,
      showBukti: false,
      docxUrl: null,
      versiDoc: {},
      version: null,
      versi: null,
      downloadDocxPath: '',
      dataWorkspace: null,
    };
  },
  computed: {
    ...mapGetters({
      markingStatus: 'markingStatus',
      userInfo: 'user',
      userId: 'userId',
    }),
    isFormulator() {
      return this.userInfo.roles.includes('formulator');
    },
    isInitiator() {
      return this.userInfo.roles.includes('initiator');
    },
    isMarking() {
      return this.markingStatus === 'uklupl-mt.returned-examination';
    },
  },
  mounted() {
    this.setProjectId();
    this.getRequiredDoc();
  },
  async created() {
    this.$store.dispatch('getStep', 5);
    this.getBuktiDoc();
    this.getSubmit();
    await this.getData();
  },
  methods: {
    async getData() {
      this.loading = true;
      if (this.markingStatus !== 'uklupl-mt.returned-examination' || this.markingStatus === null) {
        const data = await axios.get(
          `/api/dokumen-ukl-upl/${this.$route.params.id}`
        );
        this.dataWorkspace = data.data;
      }
      const data = this.dataWorkspace;
      this.docxUrl = data.docx_url;
      this.urlPdf = data.pdf_url;
      this.createTime = data.create_time;
      this.showDocument = true;
      console.log(this.urlPdf, this.showDocument);
      this.projectName = data.file_name;
      this.versi = data.versi_doc;
      this.loading = false;
      this.downloadDocxPath = this.docxUrl;
    },
    async getBuktiDoc() {
      const data = await projectsResource.list({
        project_id: this.$route.params.id,
        doc: 'UKL - UPL',
      });
      this.urlBukti = data;
    },
    async getSubmit() {
      const data = await kaReviewsResource.list({
        idProject: this.$route.params.id,
        documentType: 'ukl-upl',
        status: 'submit-to-pemrakarsa',
      });
      if (data !== null) {
        this.showBukti = true;
      }
    },
    submitPemrakarsa(status) {
      if (status === true) {
        this.showBukti = true;
      }
    },
    setProjectId() {
      this.idProject = this.$route.params.id;
    },
    async getRequiredDoc() {
      await this.$store.dispatch('getRequiredDoc', this.idProject);
    },
    workspace() {
      this.$router.push({
        name: 'projectWorkspace',
        params: {
          id: this.$route.params.id,
          filename: this.projectName,
          createTime: this.createTime,
          versi: this.versi,
          workspaceType: 'ukl-upl',
        },
      });
    },
    regenerateDocx() {
      this.$confirm(
        'Reset Dokumen. Apakah anda yakin akan mengenerate ulang dokumen?',
        'Peringatan',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Batal',
          type: 'warning',
        }).then(() => {
        this.loading = true;
        axios
          .get(`/api/dokumen-ukl-upl/${this.$route.params.id}?regenerate=true`)
          .then(() => {
            this.getData();
            this.loading = false;
          });
      });
    },
  },
};
</script>

<style scoped>
.body__section {
  display: flex;
  column-gap: 20px;
  margin-top: 20px;
}

.body__section.left__section {
  flex: 2;
}

.body__section.right__section {
  flex: 1;
}

.heading__comment {
  display: flex;
  column-gap: 15px;
}

.heading__comment.img__comment {
  flex: 0.5;
}

.heading__comment.name__comment {
  flex: 2;
}

.img__comment img {
  width: 32px;
  border-radius: 50%;
  border: 2px solid #099c4b;
}

.btn-docx,
.btn-pdf,
.btn-submit {
  padding: 10px 20px;
  font-size: 14px;
  border-radius: 4px;
  color: #ffffff;
  display: inline-block;
  line-height: 1;
  white-space: nowrap;
  cursor: pointer;
  -webkit-appearance: none;
  text-align: center;
  box-sizing: border-box;
  outline: none;
  margin: 0;
  transition: 0.1s;
  font-weight: 400;
}

.btn-resetworkspace {
  padding: 10px 20px;
  font-size: 14px;
  border-radius: 4px;
  color: #ff4949;
  display: inline-block;
  line-height: 1;
  white-space: nowrap;
  cursor: pointer;
  -webkit-appearance: none;
  text-align: center;
  font-weight: bold;
  box-sizing: border-box;
  outline: none;
  margin: 0;
  transition: 0.1s;
  font-weight: 400;
}

.btn-docx {
  background-color: #216221;
  border: 1px solid #216221;
}

.btn-pdf {
  background-color: #FFBA00;
  border: 1px solid #FFBA00;
}
.btn-submit {
  background-color: #0058ff;
  border: 1px solid #0058ff;
}

.btn-resetworkspace {
  background-color: #ffffff;
  border: 1px solid #ff4949;
}
</style>
