<!-- eslint-disable vue/html-indent -->
<template>
  <div class="app-container" style="padding: 24px">
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
            'https://docs.google.com/gview?url=' +
            encodeURIComponent(projects) +
            '&embedded=true'
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
  </div>
</template>

<script>
import Resource from '@/api/resource';
const feasibilityTestResource = new Resource('feasibility-test');

export default {
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
    this.$store.dispatch('getStep', 5);
  },
  methods: {
    async getData() {
      this.loading = true;
      const projectName = await feasibilityTestResource.list({
        dokumen: 'true',
        idProject: this.$route.params.id,
        uklUpl: 'true',
      });
      this.projects = projectName;
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
      const fileURL = await feasibilityTestResource.list({
        pdf: 'true',
        idProject: this.$route.params.id,
      });

      const fileLink = document.createElement('a');
      fileLink.href = fileURL;
      fileLink.setAttribute('download', `surat-rekomendasi-uji-kelayakan.pdf`);
      document.body.appendChild(fileLink);
      fileLink.click();
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
