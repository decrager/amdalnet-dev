<!-- eslint-disable vue/html-indent -->
<template>
  <div class="app-container" style="padding: 24px">
    <el-card v-loading="loading">
      <h2>Formulir Kerangka Acuan</h2>
      <div>
        <!-- <el-button
          v-if="showDocument"
          :loading="loadingPDF"
          type="danger"
          @click="exportPdf"
        >
          Export to .PDF
        </el-button> -->
        <a
          v-if="showDocument"
          class="btn-pdf"
          :href="urlPdf"
          :download="`ka-andal-${project_title}.pdf`"
        >
          Export to .PDF
        </a>
        <a
          v-if="showDocument"
          class="btn-docx"
          :href="downloadDocxPath"
          :download="`ka-andal-${project_title}.docx`"
        >
          Export to .DOCX
        </a>
      </div>
      <el-row :gutter="20" style="margin-top: 20px">
        <el-col :span="16">
          <div class="grid-content bg-purple" />
          <iframe
            v-if="showDocument"
            :src="urlPdf"
            width="100%"
            height="723px"
            frameborder="0"
          />
          <!-- <iframe
            v-if="showDocument"
            :src="
              'https://docs.google.com/gview?url=' +
              encodeURIComponent(projects) +
              '&embedded=true'
            "
            width="100%"
            height="723px"
            frameborder="0"
          /> -->
          <!-- <iframe
            v-if="showDocument"
            :src="`https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(projects)}`"
            width="100%"
            height="723px"
            frameborder="0"
          /> -->
        </el-col>
        <el-col :span="8">
          <div class="grid-content bg-purple" />
          <Comment />
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const andalComposingResource = new Resource('andal-composing');
const kaReviewResounce = new Resource('ka-reviews');
import Comment from '@/views/penyusunan-andal/components/Comment';

export default {
  components: {
    Comment,
  },
  data() {
    return {
      idProject: 0,
      projects: '',
      loading: false,
      loadingPDF: false,
      projectId: this.$route.params && this.$route.params.id,
      metode_studi: [],
      pra_konstruksi: [],
      konstruksi: [],
      operasi: [],
      pasca_operasi: [],
      project_title: '',
      pic: '',
      description: '',
      location_desc: '',
      positive: [],
      negative: [],
      penyusun: [],
      out: '',
      showDocument: false,
      downloadDocxPath: '',
      urlPdf: '',
    };
  },
  created() {
    this.getData();
  },
  methods: {
    async getData() {
      this.loading = true;
      const data = await andalComposingResource.list({
        idProject: this.$route.params.id,
        formulir: 'true',
        type: 'andal',
      });
      this.downloadDocxPath = data.file_name;
      this.project_title = data.project_title;
      this.projects = this.downloadDocxPath;
      this.urlPdf = data.pdf_url;
      this.showDocument = true;
      this.loading = false;
    },
    async exportPdf() {
      this.loadingPDF = true;
      const fileURL = await kaReviewResounce.list({
        pdf: 'true',
        idProject: this.$route.params.id,
        isAndal: 'true',
      });

      const fileLink = document.createElement('a');
      fileLink.href = fileURL;
      fileLink.setAttribute(
        'download',
        `ka-andal-${this.project_title.toLowerCase()}.pdf`
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
.btn-docx {
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
</style>
