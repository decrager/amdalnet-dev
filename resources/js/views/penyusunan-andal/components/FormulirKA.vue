<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <h2>Formulir Kerangka Acuan</h2>
      <div>
        <el-button type="danger" @click="exportPdf">Export to .PDF</el-button>
        <el-button type="primary" @click="exportDocx">
          Export to .DOCX
        </el-button>
      </div>
      <el-row :gutter="20" style="margin-top: 20px">
        <el-col :span="16">
          <div class="grid-content bg-purple" />
          <iframe
            :src="
              'https://docs.google.com/gview?url=' + projects + '&embedded=true'
            "
            width="100%"
            height="723px"
            frameborder="1"
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
    };
  },
  created() {
    this.getData();
    // this.getDocument();
  },
  methods: {
    async getData() {
      const data = await andalComposingResource.list({
        idProject: this.$route.params.id,
        formulir: 'true',
      });
      this.metode_studi = data.metode_studi;
      this.konstruksi = data.konstruksi;
      this.pra_konstruksi = data.pra_konstruksi;
      this.operasi = data.operasi;
      this.pasca_operasi = data.pasca_operasi;
      this.project_title = data.project_title;
      this.pic = data.pic;
      this.description = data.description;
      this.location_desc = data.location_desc;
      this.negative = data.negative;
      this.positive = data.positive;
      this.penyusun = data.penyusun;
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

          saveAs(out, this.$route.params.id + '-form-ka-andal.docx');

          // const formData = new FormData();
          // formData.append('docx', out);
          // formData.append('type', 'formulir');
          // formData.append('idProject', this.$route.params.id);

          // andalComposingResource
          //   .store(formData)
          //   .then((response) => {})
          //   .catch((error) => {
          //     console.log(error);
          //   });
        }
      );
    },
    getDocument() {
      this.projects =
        window.location.origin +
        '/storage/formulir/' +
        this.$route.params.id +
        '-form-ka-andal.docx';
    },
    exportPdf() {
      const id = this.$route.params && this.$route.params.id;
      axios({
        url: `form-ka/${id}/pdf`,
        method: 'GET',
        responseType: 'blob',
      }).then((response) => {
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
