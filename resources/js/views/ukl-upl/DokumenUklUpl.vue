<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <h2>Formulir Kerangka Acuan</h2>
      <div>
        <el-button type="danger" @click="exportPdf">Export to .PDF</el-button>
        <el-button type="primary" @click="exportDocx">Export to .Docx</el-button>
      </div>
    </el-card>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      idProject: 0,
    };
  },
  mounted() {
    this.setProjectId;
  },
  methods: {
    setProjectId(){
      const id = this.$route.params && this.$route.params.id;
      this.idProject = id;
    },
    exportPdf() {
      const id = this.$route.params && this.$route.params.id;
      axios({
        url: `form-ka/${id}/pdf`,
        method: 'GET',
        responseType: 'blob',
      }).then((response) => {
        var fileURL = window.URL.createObjectURL(new Blob([response.data]));
        var fileLink = document.createElement('a');
        fileLink.href = fileURL;
        fileLink.setAttribute('download', 'file.pdf');
        document.body.appendChild(fileLink);
        fileLink.click();
      });
    },
    exportDocx() {
      const id = this.$route.params && this.$route.params.id;
      axios({
        url: `form-ka/${id}/docx`,
        method: 'GET',
        responseType: 'blob',
      }).then((response) => {
        console.log();
        const getHeaders = response.headers['content-disposition'].split('; ');
        const getFileName = getHeaders[1].split('=');
        const getName = getFileName[1].split('=');
        var fileURL = window.URL.createObjectURL(new Blob([response.data]));
        var fileLink = document.createElement('a');
        fileLink.href = fileURL;
        fileLink.setAttribute('download', `${getName}`);
        document.body.appendChild(fileLink);
        fileLink.click();
      });
    },
  },
};
</script>
