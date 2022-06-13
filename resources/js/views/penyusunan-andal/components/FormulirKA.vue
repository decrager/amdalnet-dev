<template>
  <div class="app-container" style="padding: 24px">
    <el-card v-loading="loading">
      <h2>Formulir Kerangka Acuan</h2>
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
          :href="downloadDocxPath"
          download
        >
          Export to .DOCX
        </a>
      </div>
      <el-row :gutter="20" style="margin-top: 20px">
        <el-col :span="16">
          <div class="grid-content bg-purple" />
          <!-- <iframe
            v-if="showDocument"
            :src="
              'https://docs.google.com/gview?url=' + projects + '&embedded=true'
            "
            width="100%"
            height="723px"
            frameborder="0"
          /> -->
          <iframe
            v-if="showDocument"
            :src="`https://view.officeapps.live.com/op/embed.aspx?src=${projects}`"
            width="100%"
            height="723px"
            frameborder="0"
          />
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
import Comment from '@/views/penyusunan-andal/components/Comment';
import axios from 'axios';
import Docxtemplater from 'docxtemplater';
import PizZip from 'pizzip';
import PizZipUtils from 'pizzip/utils/index.js';
import { saveAs } from 'file-saver';

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
    };
  },
  created() {
    this.getData();
    // this.getDocument();
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
      this.projects = window.location.origin + `/${this.downloadDocxPath}`;
      this.showDocument = true;
      this.loading = false;
    },
    async exportDocxPhpWord() {
      await andalComposingResource.list({
        idProject: this.$route.params.id,
        formulir: 'true',
      });
    },
    downloadDocx() {
      saveAs(this.out, `ka-andal-${this.project_title.toLowerCase()}.docx`);
    },
    exportDocx() {
      PizZipUtils.getBinaryContent(
        '/template_ka_andal.docx',
        (error, content) => {
          if (error) {
            throw error;
          }
          const zip = new PizZip(content);
          const doc = new Docxtemplater(zip, {
            paragraphLoop: true,
            linebreaks: true,
          });
          doc.render({
            metode_studi: this.metode_studi,
            pra_konstruksi: this.pra_konstruksi,
            konstruksi: this.konstruksi,
            operasi: this.operasi,
            pasca_operasi: this.pasca_operasi,
            project_title: this.project_title,
            pic: this.pic,
            description: this.description,
            location_desc: this.location_desc,
            negative: this.negative,
            positive: this.positive,
            penyusun: this.penyusun,
          });

          const out = doc.getZip().generate({
            type: 'blob',
            mimeType:
              'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
          });

          const formData = new FormData();
          formData.append('docx', out);
          formData.append('type', 'formulir');
          formData.append('idProject', this.$route.params.id);

          andalComposingResource
            .store(formData)
            .then((response) => {
              this.showDocument = true;
              this.projects =
                window.location.origin +
                `/storage/formulir/ka-andal-${this.project_title.toLowerCase()}.docx`;
              this.loading = false;
            })
            .catch((error) => {
              console.log(error);
            });

          this.out = out;
        }
      );
    },
    getDocument() {
      this.projects =
        window.location.origin +
        '/storage/formulir/' +
        this.$route.params.id +
        `/storage/formulir/ka-andal-${this.project_title.toLowerCase()}.docx`;
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
          type: 'andal',
        },
      }).then((response) => {
        // const getHeaders = response.headers['content-disposition'].split('; ');
        // const getFileName = getHeaders[1].split('=');
        // const getName = getFileName[1].split('=');
        var fileURL = window.URL.createObjectURL(new Blob([response.data]));
        var fileLink = document.createElement('a');
        fileLink.href = fileURL;
        fileLink.setAttribute(
          'download',
          `ka-andal-${this.project_title.toLowerCase()}.pdf`
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
