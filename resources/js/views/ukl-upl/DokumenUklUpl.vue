<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <h2>Formulir Kerangka Acuan</h2>
      <div>
        <el-button type="danger" @click="exportPdf">Export to .PDF</el-button>
        <el-button type="primary" @click="exportDocx">Export to .DOCX</el-button>
      </div>
      <el-row :gutter="20" style="margin-top: 20px">
        <el-col :span="16"><div class="grid-content bg-purple" />
          <iframe :src="'https://docs.google.com/gview?url='+projects+'&embedded=true'" width="100%" height="723px" frameborder="1" />
        </el-col>
        <el-col :span="8"><div class="grid-content bg-purple" />

          <el-input
            v-model="message"
            type="textarea"
            :rows="5"
            placeholder="Tulis Masukan"
          />
          <div style="margin-top: 5px; display: flex; align-content: flex-end; justify-content: end;">
            <el-button type="primary" icon="el-icon-s-promotion" @click="saveComment">Kirim</el-button>
          </div>
          <div v-if="comments" style="margin-top: 10px;">
            <h3>Komentar Terbaru : </h3>
            <img v-if="loading" src="images/loader.gif" alt="Loading...." class="loader">
            <el-card v-for="comment in commentsData" :key="comment.id" shadow="always" style="margin-top: 10px;">
              <div class="heading__comment">
                <div class="img__comment">
                  <img :src="comment.avatar" onerror="this.src='no-avatar.png'" alt="Avatar">
                </div>
                <div class="name__comment">
                  <span style="font-weight: bold">{{ comment.name }}</span><br>
                  <span><i>{{ formatDate(comment.date) }}</i></span>
                </div>
              </div>
              <p>{{ comment.comment }}</p>
            </el-card>
          </div>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import axios from 'axios';
import dayjs from 'dayjs';
import Docxtemplater from 'docxtemplater';
import PizZip from 'pizzip';
import PizZipUtils from 'pizzip/utils/index.js';
import { saveAs } from 'file-saver';

export default {
  data() {
    return {
      idProject: 0,
      projects: '',
      commentsData: [],
      comments: [],
      message: null,
      userId: 0,
      errorComment: '',
      docContents: [],
      loading: false,
      projectId: this.$route.params && this.$route.params.id,
    };
  },
  mounted() {
    this.setProjectId;
    this.getDocument();
    this.getComment();
    this.getUserId();
    this.getDocContent();
  },
  http: {
    headers: {
      'X-CSRF-TOKEN': window.csrf,
    },
  },
  methods: {
    formatDate(value) {
      if (value) {
        dayjs.locale('id');
        return dayjs(String(value)).format('DD-MMMM-YYYY');
      }
    },
    async getComment() {
      this.loading = true;
      await axios.get('api/ukl-upl-comment/' + this.projectId)
        .then((response) => {
          this.commentsData = response.data.data;
          this.loading = false;
          this.comments = 1;
        });
    },
    getUserId() {
      axios.get('api/user')
        .then((response) => {
          this.userId = response.data.data;
        });
    },
    saveComment() {
      const idProject = this.$route.params && this.$route.params.id;
      if (this.message != null && this.message !== ' ') {
        this.errorComment = null;
        axios.post('api/ukl-upl-comment', {
          id_project: parseInt(idProject),
          id_user: this.userId.id,
          comment: this.message,
        }).then(res => {
          if (res.data.status) {
            this.commentsData.push({ 'name': this.userId.name, 'date': this.formatDate(new Date()), 'comment': this.message, 'avatar': this.commentsData.avatar || 'no-avatar.png' });
            this.message = null;
          }
        });
      } else {
        this.errorComment = 'Please enter a comment to save';
      }
    },
    getDocument() {
      const id = this.$route.params && this.$route.params.id;
      axios.get('api/projects/' + id)
        .then((result) => {
          this.projects = window.location.origin + '/storage/formulir/form-ka-' + result.data.project_title + '.docx';
        });
    },
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
    getDocContent() {
      axios.get('api/ka-docx/' + this.projectId)
        .then((response) => {
          this.docContents = response.data;
        });
    },
    exportDocx() {
      PizZipUtils.getBinaryContent(
        '/template_KA.docx',
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
            project_title: this.docContents.project_title,
            pic: this.docContents.pic,
            description: this.docContents.description.innerHTML,
            location_desc: this.docContents.location_desc.innerHTML,
          });

          const out = doc.getZip().generate({
            type: 'blob',
            mimeType: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
          });

          saveAs(out, 'form-ka-' + this.docContents.project_title + '.docx');
        }
      );
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
  flex: 1
}
.heading__comment {
  display: flex;
  column-gap: 15px;
}
.heading__comment.img__comment {
  flex: .5
}
.heading__comment.name__comment {
  flex: 2
}
.img__comment img {
  width: 32px;
  border-radius: 50%;
  border: 2px solid #099C4B;
}
</style>
