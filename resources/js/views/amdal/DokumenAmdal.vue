<template>
  <div class="app-container" style="padding: 24px">
    <el-card v-if="formulirKACompleted && templateKALoaded" v-loading="loading">
      <h2>
        Submit Formulir kerangka Acuan
        <span v-if="isFormulator">ke Pemrakarsa</span>
      </h2>
      <div>
        <el-button
          v-if="showDocument"
          :loading="loadingPDF"
          type="danger"
          @click="exportPdf"
        >
          Export to .PDF
        </el-button>
        <a
          v-if="showDocument"
          class="btn-docx"
          :href="'/storage/formulir/' + downloadDocxPath"
          download
        >
          Export to .DOCX
        </a>
      </div>
      <el-row :gutter="20" style="margin-top: 20px">
        <el-col :sm="24" :md="14">
          <div class="grid-content bg-purple" />
          <iframe
            v-if="showDocument"
            :src="
              'https://docs.google.com/gview?url=' + projects + '&embedded=true'
            "
            width="100%"
            height="723px"
            frameborder="0"
          />
        </el-col>
        <el-col :sm="24" :md="10">
          <ReviewPenyusun
            v-if="isFormulator"
            :documenttype="'Kerangka Acuan'"
          />
          <ReviewPemrakarsa
            v-if="isInitiator"
            :documenttype="'Kerangka Acuan'"
          />
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import ReviewPenyusun from '@/views/review-dokumen/ReviewPenyusun';
import ReviewPemrakarsa from '@/views/review-dokumen/ReviewPemrakarsa';
const scopingResource = new Resource('scoping');
const andalComposingResource = new Resource('andal-composing');
import axios from 'axios';

export default {
  components: {
    ReviewPenyusun,
    ReviewPemrakarsa,
  },
  data() {
    return {
      templateKALoaded: false,
      formulirKACompleted: false,
      idProject: 0,
      projects: '',
      loading: false,
      loadingPDF: false,
      projectId: this.$route.params && this.$route.params.id,
      out: '',
      showDocument: false,
      downloadDocxPath: '',
      userInfo: {
        roles: [],
      },
    };
  },
  computed: {
    isFormulator() {
      return this.userInfo.roles.includes('formulator');
    },
    isInitiator() {
      return this.userInfo.roles.includes('initiator');
    },
  },
  async created() {
    this.userInfo = await this.$store.dispatch('user/getInfo');
    await this.getData();
  },
  methods: {
    async getData() {
      this.loading = true;
      const checkFormulirKA = await scopingResource.list({
        check_formulir_ka: true,
        id_project: this.$route.params.id,
      });
      if (!checkFormulirKA.data) {
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
      this.project_title = data.project_title;
      this.projects =
        window.location.origin + `/storage/formulir/${this.downloadDocxPath}`;
      this.showDocument = true;
      this.loading = false;
      this.templateKALoaded = true;
    },
    async exportPdf() {
      this.loadingPDF = true;
      axios({
        url: `api/andal-composing`,
        method: 'GET',
        responseType: 'blob',
        params: {
          pdf: 'true',
          idProject: this.$route.params.id,
          type: 'ka',
        },
      }).then((response) => {
        var fileURL = window.URL.createObjectURL(new Blob([response.data]));
        var fileLink = document.createElement('a');
        fileLink.href = fileURL;
        fileLink.setAttribute(
          'download',
          `ka-${this.project_title.toLowerCase()}.pdf`
        );
        document.body.appendChild(fileLink);
        fileLink.click();
        this.loadingPDF = false;
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
.btn-docx {
  padding: 10px 20px;
  font-size: 14px;
  border-radius: 4px;
  color: #ffffff;
  background-color: #216221;
  display: inline-block;
  line-height: 1;
  white-space: nowrap;
  cursor: pointer;
  border: 1px solid #216221;
  -webkit-appearance: none;
  text-align: center;
  box-sizing: border-box;
  outline: none;
  margin: 0;
  transition: 0.1s;
  font-weight: 400;
}
</style>
