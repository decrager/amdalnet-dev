<template>
  <div class="app-container" style="padding: 24px">
    <el-card v-loading="loading">
      <workflow />
      <h2>Rekomendasi Hasil Uji Kelayakan</h2>
      <div>
        <el-button
          v-if="projects !== null"
          :loading="loadingPDF"
          type="danger"
          @click="exportPdf"
        >
          Export to .PDF
        </el-button>
        <a
          v-if="projects !== null"
          class="el-button el-button--primary el-button--medium"
          :href="projects"
          download
        >
          Export to .DOCX
        </a>
      </div>
      <el-row :gutter="20" style="margin-top: 20px">
        <el-col :span="16">
          <div class="grid-content bg-purple" />
          <iframe
            v-if="projects !== null"
            :src="
              'https://docs.google.com/gview?url=' + projects + '&embedded=true'
            "
            width="100%"
            height="723px"
            frameborder="0"
          />
        </el-col>
        <el-col :span="8">
          <div class="grid-content bg-purple" />
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const feasibilityTestResource = new Resource('feasibility-test');
import axios from 'axios';
import Workflow from '@/components/Workflow';

export default {
  components: {
    Workflow,
  },
  data() {
    return {
      idProject: 0,
      projects: null,
      loading: false,
      loadingPDF: false,
      projectId: this.$route.params && this.$route.params.id,
      showDocument: true,
      projectName: '',
    };
  },
  created() {
    this.getData();
    this.$store.dispatch('getStep', 6);
  },
  methods: {
    async getData() {
      this.loading = true;
      const projectName = await feasibilityTestResource.list({
        dokumen: 'true',
        idProject: this.$route.params.id,
      });
      this.projects =
        window.location.origin + '/storage/uji-kelayakan/' + projectName;
      this.projectName = projectName;
      this.loading = false;
    },
    async exportDocxPhpWord() {
      await feasibilityTestResource.list({
        idProject: this.$route.params.id,
        formulir: 'true',
      });
    },
    async exportPdf() {
      this.loadingPDF = true;
      axios({
        url: `api/feasibility-test`,
        method: 'GET',
        responseType: 'blob',
        params: {
          idProject: this.$route.params.id,
          pdf: 'true',
        },
      })
        .then((response) => {
          this.loadingPDF = false;
          // const getHeaders = response.headers['content-disposition'].split('; ');
          // const getFileName = getHeaders[1].split('=');
          // const getName = getFileName[1].split('=');
          var fileURL = window.URL.createObjectURL(new Blob([response.data]));
          var fileLink = document.createElement('a');
          fileLink.href = fileURL;
          fileLink.setAttribute(
            'download',
            `${this.projectName.replace('.docx', '.pdf')}`
          );
          document.body.appendChild(fileLink);
          fileLink.click();
        })
        .catch((err) => {
          err = '';
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
</style>
