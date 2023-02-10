<template>
  <div class="app-container" style="padding: 24px">
    <el-card v-if="formulirKACompleted && templateKALoaded" v-loading="loading">
      <div role="alert" class="el-alert el-alert--error is-dark" style="margin-top: 10px">
        <div class="el-alert__content">
          <p class="el-alert__description">
            Pastikan Workspace Formulir Kerangka Acuan sudah dilengkapi
          </p>
        </div>
      </div>
      <h2>
        Submit Formulir Kerangka Acuan
        <span v-if="isFormulator">ke Pemrakarsa</span>
      </h2>
      <div>
        <!-- <el-button
          v-if="showDocument"
          :loading="loadingPDF"
          type="danger"
          @click="exportPdf"
        >
          Export to .PDF
        </el-button> -->
        <el-button :loading="loading" type="primary" @click="workspace">
          Workspace
        </el-button>
        <a
          v-if="showDocument"
          class="btn-pdf"
          :href="urlPdf"
          :download="`ka-${project_title}.pdf`"
        >
          Export to .PDF
        </a>
        <a
          v-if="showDocument"
          class="btn-docx"
          :href="downloadDocxPath"
          :download="`ka-${project_title}.docx`"
        >
          Export to .DOCX
        </a>
        <a v-if="showBukti" class="btn-submit" :href="urlBukti" :download="`bukti-submit-ukl-upl.pdf`">
          Unduh Bukti Pengiriman
        </a>
      </div>
      <el-row :gutter="20" style="margin-top: 20px">
        <el-col :sm="24" :md="14">
          <div class="grid-content bg-purple" />
          <iframe
            v-if="showDocument"
            :src="`https://docs.google.com/gview?url=${encodeURIComponent(
              urlPdf
            )}&embedded=true`"
            width="100%"
            height="723px"
            frameborder="0"
          />
          <!-- <iframe
            v-if="showDocument"
            :src="
              'https://docs.google.com/gview?url=' + encodeURIComponent(projects) + '&embedded=true'
            "
            width="100%"
            height="723px"
            frameborder="0"
          /> -->
          <!-- <iframe
            v-if="showDocument"
            :src="`https://view.officeapps.live.com/op/embed.aspx?src=${projects}`"
            width="100%"
            height="723px"
            frameborder="0"
          /> -->
        </el-col>
        <el-col :sm="24" :md="10">
          <ReviewPenyusun
            v-if="isFormulator"
            :documenttype="'Kerangka Acuan'"
          />
          <ReviewPemrakarsa
            v-if="isInitiator"
            :documenttype="'Kerangka Acuan'"
            @submitPemrakarsa="submitPemrakarsa"
          />
          <Lampiran />
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Resource from '@/api/resource';
import ReviewPenyusun from '@/views/review-dokumen/ReviewPenyusun';
import ReviewPemrakarsa from '@/views/review-dokumen/ReviewPemrakarsa';
import Lampiran from '@/views/review-dokumen/Lampiran';
const scopingResource = new Resource('scoping');
const andalComposingResource = new Resource('andal-composing');
const kaReviewResource = new Resource('ka-reviews');
const projectsResource = new Resource('projects');

export default {
  components: {
    ReviewPenyusun,
    ReviewPemrakarsa,
    Lampiran,
  },
  data() {
    return {
      templateKALoaded: false,
      formulirKACompleted: false,
      idProject: 0,
      projects: '',
      urlPdf: '',
      loading: false,
      urlBukti: null,
      showBukti: false,
      fileNameKa: null,
      loadingPDF: false,
      projectId: this.$route.params && this.$route.params.id,
      out: '',
      showDocument: false,
      downloadDocxPath: '',
      project_title: '',
      // userInfo: {
      //   roles: [],
      // },
    };
  },
  computed: {
    ...mapGetters({
      userInfo: 'user',
      userId: 'userId',
    }),
    isFormulator() {
      return this.userInfo.roles.includes('formulator');
    },
    isInitiator() {
      return this.userInfo.roles.includes('initiator');
    },
  },
  async created() {
    // this.userInfo = await this.$store.dispatch('user/getInfo');
    this.getBuktiDoc();
    this.getSubmit();
    await this.getData();
  },
  methods: {
    async getData() {
      this.loading = true;
      const checkFormulirKA = await scopingResource.list({
        check_formulir_ka: true,
        id_project: this.$route.params.id,
      });
      if (checkFormulirKA.errBaganAlir) {
        this.$message({
          message:
            'Mohon lakukan export File PDF Bagan Alir Pelingkupan terlebih dahulu',
          type: 'error',
          duration: 5 * 1000,
        });
        this.$router.push({
          name: 'FormulirAmdal',
          params: this.$route.params.id,
        });
      } else if (!checkFormulirKA.data) {
        this.$message({
          message: 'Mohon lengkapi Formulir KA terlebih dahulu',
          type: 'error',
          duration: 5 * 1000,
        });
        this.$router.push({
          name: 'FormulirAmdal',
          params: this.$route.params.id,
        });
      } else {
        this.formulirKACompleted = true;
      }
      const data = await andalComposingResource.list({
        idProject: this.$route.params.id,
        formulir: 'true',
        type: 'ka',
      });
      this.downloadDocxPath = data.file_name;
      this.fileNameKa = data.file_name_ka;
      this.project_title = data.project_title;
      this.projects = this.downloadDocxPath;
      this.urlPdf = data.pdf_url;
      this.showDocument = true;
      this.loading = false;
      this.templateKALoaded = true;
    },
    async getBuktiDoc() {
      const data = await projectsResource.list({
        project_id: this.$route.params.id,
        doc: 'FORMULIR KERANGKA ACUAN',
      });
      this.urlBukti = data;
    },
    async getSubmit() {
      const data = await kaReviewResource.list({
        idProject: this.$route.params.id,
        documentType: 'ka',
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
    workspace(){
      this.$router.push({
        name: 'projectWorkspace',
        params: {
          id: this.$route.params.id,
          filename: this.fileNameKa,
          workspaceType: 'ka',
        },
      });
    },
    async exportPdf() {
      this.loadingPDF = true;
      const fileURL = await kaReviewResource.list({
        pdf: 'true',
        idProject: this.$route.params.id,
      });

      const fileLink = document.createElement('a');
      fileLink.href = fileURL;
      fileLink.setAttribute(
        'download',
        `ka-${this.project_title.toLowerCase()}.pdf`
      );
      document.body.appendChild(fileLink);
      fileLink.click();

      this.loadingPDF = false;
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
.btn-docx {
  background-color: #216221;
  border: 1px solid #216221;
}
.btn-pdf {
  background-color: #ff4949;
  border: 1px solid #ff4949;
}
.btn-submit {
  background-color: #0058ff;
  border: 1px solid #0058ff;
}
</style>
