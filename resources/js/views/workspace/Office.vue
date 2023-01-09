<template>
  <div class="app-container" style="position: relative; display: flex; flex-direction: column;">
    <div v-if="isAbleToComment" class="button-show">
      <div style="padding-bottom: 0.5rem;">
        <el-button @click="showHide">{{ !showForm ? 'Tampilkan Masukan Saran/Tanggapan' : 'Sembunyikan Masukan Saran/Tanggapan' }}</el-button>
        <el-button v-if="isPerbaikan && (isWebForm || fileDocxUrl) && dataPerbaikan" style="float: right;" type="success" @click="showHidePreviewMatriks">{{ !showPrev ? 'Tampilkan Perbaikan Matriks UKL UPL' : 'Sembunyikan Perbaikan Matriks UKL UPL' }}</el-button>
        <!-- <el-button @click="download">Download Rekap Komentar</el-button> -->
        <el-button v-if="isPerbaikan" style="float: right;" type="info" @click="loadPerbaikan">
          <span>Perbaikan Ulang</span>
        </el-button>
      </div>
      <div v-if="showPrev" style="position: absolute; background-color: #404040; left: 0; right: 0; padding-top: 1rem; padding-right: 1rem; padding-left: 1rem; margin-left: 1px; height: 100%;">
        <el-row :gutter="32">
          <el-col :span="20">
            <div style="height: 90%;">
              <!-- <VueDocPreview :value="fileUrl" type="office" /> -->
              <!-- <div id="placeholderMatriksUkl" /> -->
              <iframe
                :src="`https://docs.google.com/gview?url=${encodeURIComponent(
                  fileDocxUrl
                )}&embedded=true`"
                width="100%"
                height="723px"
                frameborder="0"
              />
            </div>
          </el-col>
          <el-col :span="4">
            <div>
              <h4 style="color: white"> Unduh File Perbaikan Matriks UKL UPL </h4>
            </div>
            <a
              class="btn-docx"
              :href="fileDocxUrl"
              :download="`perbaikan-matriks-ukl-upl-${fileName}.docx`"
            >
              Download .Docx
            </a>
            <a
              class="btn-pdf"
              style="margin-top: .3rem;"
              :href="filePdfUrl"
              :download="`perbaikan-matriks-ukl-upl-${fileName}.docx`"
            >
              Download .Pdf
            </a>
          </el-col>
        </el-row>
      </div>
      <div v-if="showForm" style="position: absolute; background-color: #404040; left: 0; right: 0; padding-top: 1rem; padding-right: 1rem; padding-left: 1rem; margin-left: 1px; height: 100%;">
        <div style="width: 100%; height: 100%; overflow-x: scroll; margin-bottom: 1rem;">
          <table class="table table-bordered">
            <thead>
              <th style="width: 1%;">No</th>
              <th style="width: 35%;">Saran/Pendapat/Tanggapan</th>
              <th style="width: 35%;">Perbaikan/Tanggapan Pemrakarsa</th>
              <th style="width: 15%;">Aksi</th>
            </thead>
            <tbody>
              <EmptyComment v-if="comments.length < 1" />
              <Comment
                v-for="(comnt, index) in comments"
                :id="comnt.id"
                :key="index"
                :comments="comments"
                :no="comnt.no"
                :page="comnt.page"
                :suggest="comnt.suggest"
                :page-fix="comnt.pageFix"
                :response="comnt.response"
                :checktuk="isTUK"
                @handleSaveComment="handleSaveComment"
                @handleDeleteComment="handleDeleteComment"
              />
              <NewComment
                v-if="isTUK"
                :comments="comments"
                :next-comment="nextComment"
                :checktuk="isTUK"
                @handleAddComment="handleAddComment"
              />
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div id="etherpad-wrapper" style="height: 100%; width: 100%;">
      <div id="placeholder" />
    </div>
  </div>
</template>

<script>
import WorkspaceResource from '@/api/workspace';
import { mapGetters } from 'vuex';
import Resource from '@/api/resource';
import Comment from '../comment-recap/Comment.vue';
import NewComment from '../comment-recap/NewComment.vue';
import EmptyComment from '../comment-recap/EmptyComment.vue';
// import VueDocPreview from 'vue-doc-preview';
const workspaceResource = new WorkspaceResource();
const workspaceCommentResource = new Resource('workspace-comment');
const projectResource = new Resource('projects');
import Axios from 'axios';

export default {
  components: {
    Comment,
    NewComment,
    EmptyComment,
    // VueDocPreview,
  },
  props: {
    project: {
      type: Object,
      default: null,
    },
    filename: {
      type: String,
      default: 'sample.docx',
    },
    createTime: {
      type: String,
      default: '00:00:00',
    },
    version: {
      type: Number,
      default: 0,
    },
    workspaceType: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      input: '',
      comment: null,
      activeNames: '',
      officeUrl: '',
      selectedTreeId: 1,
      sessionID: null,
      loading: false,
      docEditor: null,
      fileDocxUrl: null,
      filePdfUrl: null,
      isStageError: false,
      idCat: null,
      fileName: null,
      dataPerbaikan: false,
      rekaps: [],
      showForm: false,
      showPrev: false,
      comments: [
        // {
        //   no: 1,
        //   page: 1,
        //   suggest: 'Kurang Mantab',
        //   pageFix: 1,
        //   response: 'Masa sih',
        // },
        // {
        //   no: 2,
        //   page: 2,
        //   suggest: 'Kurang Mantab Gan',
        //   pageFix: 2,
        //   response: 'Masa sih bro',
        // },
      ],
    };
  },
  computed: {
    ...mapGetters({
      userInfo: 'user',
      userId: 'userId',
      markingStatus: 'markingStatus',
    }),
    isAbleToComment() {
      const status = [
        // uklupl workflow state
        // 'uklupl-mt.matrix-ukl',
        // 'uklupl-mt.matrix-upl',
        'uklupl-mt.sent',
        'uklupl-mt.adm-review',
        'uklupl-mt.examination-invitation-drafting',
        'uklupl-mt.examination-invitation-sent',
        'uklupl-mt.returned-examination',
        'uklupl-mt.examination',
        'uklupl-mt.examination-meeting',
        'uklupl-mt.ba-drafting',
        'uklupl-mt.ba-signed',
        'uklupl-mt.recommendation-drafting',
        'uklupl-mt.recommendation-signed',
        // amdal workflow state
        'amdal.form-ka-submitted',
        'amdal.form-ka-adm-review',
        'amdal.form-ka-adm-returned',
        'amdal.form-ka-adm-approved',
        'amdal.form-ka-examination-invitation-drafting',
        'amdal.form-ka-examination-invitation-sent',
        'amdal.form-ka-examination',
        'amdal.form-ka-examination-meeting',
        'amdal.form-ka-returned',
        'amdal.form-ka-approved',
        'amdal.form-ka-ba-drafting',
        'amdal.form-ka-ba-signed',
      ];
      return status.includes(this.markingStatus);
    },
    isPenyusun() {
      return this.userInfo.roles.includes('formulator');
    },
    isPemrakarsa() {
      return this.userInfo.roles.includes('initiator');
    },
    isTUK() {
      return this.userInfo.roles.some((role) => role.includes('examiner'));
    },
    padSrc() {
      if (this.sessionID) {
        return process.env.MIX_OFFICE_URL + '/auth_session?sessionID=' + this.sessionID + '&padName=' + this.selectedTreeId;
      }
      return '';
    },
    nextComment() {
      const lastComment = this.comments[this.comments.length - 1];
      return parseInt(lastComment?.no ?? 0) + 1;
    },
    isPerbaikan() {
      if (this.$route.query.perbaikan) {
        return true;
      } else {
        return false;
      }
    },
    isWebForm() {
      if (this.$route.query.isFixFormulirUklUpl || this.$route.query.isFixFormulirUklUpl) {
        return true;
      } else {
        return false;
      }
    },
  },
  watch: {
    activeNames: function(val) {
      console.log({ activeName: val });
      console.log({ data: this.$store.getters });
      this.activeName = val[1];
    },
  },
  async mounted() {
    this.getMarking();
    if (this.isWebForm() || this.fileDocxUrl) {
      this.handleCetakMatriks();
    }
    this.handleCetakMatriks();
    this.getPerbaikan();
    this.officeUrl = process.env.MIX_OFFICE_URL;
  },
  async created() {
    const idProject = this.$route.query.idProject;
    if (idProject) {
      const data = await Axios.get(
        `/api/dokumen-ukl-upl/${idProject}`
      );

      const datas = data.data;
      this.filenames = datas.file_name;
      this.createTimes = datas.create_time;
      this.versions = datas.versi_doc;
      this.addOfficeScript(datas);
    } else {
      this.addOfficeScript();
    }
    this.loadWorkspaceType();
    this.loadFileName();
    this.getComments();
  },
  methods: {
    async download() {
      // const formData = new FormData();
      Axios
        .get(
          `/api/workspace-comment?download=true&id_project=${this.$route.params.id}&document_type=${this.workspaceType}`,
          {
            responseType: 'blob',
          }
        )
        .then((response) => {
          const fileUrl = window.URL.createObjectURL(response.data);
          const fileLink = document.createElement('a');
          fileLink.href = fileUrl;
          fileLink.setAttribute('download', 'rekap.docx');
          document.body.appendChild(fileLink);
          fileLink.click();
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    },
    async getMarking() {
      await this.$store.dispatch('getMarking', this.$route.params.id);
    },
    loadWorkspaceType() {
      if (localStorage.getItem('workspaceType')) {
        this.workspaceType = localStorage.getItem('workspaceType');
      } else {
        localStorage.setItem('workspaceType', this.workspaceType);
      }
    },
    loadPerbaikan() {
      const that = this;
      this.$alert('Anda akan dialihkan ke menu <b> Formulir UKL UPL </b>', 'Peringatan', {
        confirmButtonText: 'Confirm',
        center: true,
        dangerouslyUseHTMLString: true,
      }).then(res => {
        that.$router.push({
          path: `/uklupl/${this.$route.params.id}/formulir`,
          query: {
            perbaikan: true,
            refresh: true,
          },
        });
      });
    },
    loadFileName() {
      if (localStorage.getItem('fileName')) {
        this.filename = localStorage.getItem('fileName');
      } else {
        localStorage.setItem('fileName', this.filename);
      }
    },
    async handleAddComment(comment) {
      const result = await workspaceCommentResource.store({
        id_user: this.userInfo.id,
        id_project: this.$route.params.id,
        page: comment.page,
        suggest: comment.suggest,
        page_fix: comment.pageFix,
        response: comment.response,
        document_type: this.workspaceType,
      });
      comment.id = result.id;
      this.comments.push(comment);
    },
    async handleSaveComment(comment) {
      await workspaceCommentResource.update(comment.id, {
        page: comment.page,
        suggest: comment.suggest,
        page_fix: comment.pageFix,
        response: comment.response,
      });
      const found = this.comments.find((value, index) => {
        return value.id === comment.id;
      });
      found.no = comment.no;
      found.page = comment.page;
      found.pageFix = comment.pageFix;
      found.response = comment.response;
      found.suggest = comment.suggest;
    },
    async handleDeleteComment(id) {
      await workspaceCommentResource.destroy(id);
      const newComments = this.comments.filter((val) => val.id !== id);
      this.comments = newComments;
    },
    async getComments(){
      const comments = await workspaceCommentResource.list({
        id_user: this.userInfo.roles.some((role) => role.includes('examiner')) ? this.userInfo.id : null,
        id_project: this.$route.params.id,
        document_type: this.workspaceType,
      });
      comments.map((value, index) => {
        value.no = ++index;
        value.pageFix = value.page_fix;
      });
      this.comments = comments;
    },
    clearError() {
      this.isStageError = false;
    },
    resize() {
      console.log('resize');
    },
    showHide() {
      this.showForm = !this.showForm;
    },
    showHidePreviewMatriks() {
      this.showPrev = !this.showPrev;
    },
    handleTemplateUploadChange(file, fileList) {
      // add file to multipart
      this.loading = true;
      const formData = new FormData();
      formData.append('file', file.raw);

      workspaceResource
        .importTemplate(formData)
        .then(response => {
          console.log(response);
          this.$message({
            message: 'Berhasil Load Template',
            type: 'success',
            duration: 5 * 1000,
          });
          this.loading = false;
        })
        .catch(error => {
          console.log(error);
          this.loading = false;
        });
    },

    beforeTemplateUpload(file) {
      console.log('file', file.type);
      // const isJPG = file.type === 'image/jpeg';
      const isLt2M = file.size / 1024 / 1024 < 2;

      // if (!isJPG) {
      //   this.$message.error('Avatar picture must be JPG format!');
      // }
      // if (!isLt2M) {
      //   this.$message.error('Avatar picture size can not exceed 2MB!');
      // }
      return isLt2M;
    },

    addOfficeScript(data) {
      const officeScript = document.createElement('script');
      console.log('x', process.env.MIX_OFFICE_URL, process.env.MIX_ETHERPAD_URL);
      officeScript.setAttribute('src', process.env.MIX_OFFICE_URL + '/web-apps/apps/api/documents/api.js');
      document.head.appendChild(officeScript);
      officeScript.onload = () => {
        this.createOfficeEditor(data);
      };
    },

    createOfficeEditor(data) {
      console.log('create office');
      let filename = this.filename;
      if (this.$route.params.filename || data) {
        filename = this.$route.params.filename === undefined ? data.file_name : this.$route.params.filename;
      }

      let createTime = this.createTime;
      if (this.$route.params.createTime || data) {
        createTime = this.$route.params.createTime === undefined ? data.create_time : this.$route.params.createTime;
      }

      let version = this.version;
      if (this.$route.params.versi || data) {
        version = this.$route.params.versi === undefined ? data.versi_doc : this.$route.params.versi;
      }

      workspaceResource
        .getConfig(this.$route.params.id, filename, createTime, version)
        .then(resp => {
          console.log(resp);
          this.docEditor = new window.DocsAPI.DocEditor('placeholder', resp);
        });
    },

    async getPerbaikan() {
      const data = await projectResource.list({
        id_project: this.$route.params.id,
        perbaikan: true,
      });
      this.dataPerbaikan = data.perbaikan;
    },

    async handleCetakMatriks() {
      const data = await Axios.get(
        `/api/matriks-ukl-upl/${this.$route.params.id}?perbaikanMatriksUkl=true`
      );
      this.fileDocxUrl = data.data.docx_url;
      this.fileName = data.data.file_name;
      this.filePdfUrl = data.data.pdf_url;
      console.log(this.fileDocxUrl);
    },

    etherpadAuth() {
      workspaceResource
        .sessionInit()
        .then(response => {
          console.log(response, response.data.sessionID);
          this.sessionID = response.data.sessionID;
        });
    },
  },
};
</script>

<style lang="scss">
  body {
    overflow: hidden;
  }
  .vtl {
    .vtl-tree-margin {
      margin-left: 1em;
    }
    .vtl-drag-disabled {
      background-color: #d0cfcf;
      &:hover {
        background-color: #d0cfcf;
      }
    }
    .vtl-disabled {
      background-color: #d0cfcf;
    }
    .vtl-node {
      .vtl-node-content {
        padding: 0 3px;
      }
      .vtl-node-main {
        font-size: 14px;
      }
    }
  }
</style>

<style lang="scss">
  .is-error .el-input__inner,
  .is-error .el-textarea__inner {
    border-color: #f56c6c;
  }
  .is-click .el-table__cell {
    background-color: #ffd93a;
  }
  .custom-highlight-row{
    background-color: pink
  }
  iframe {
    height: calc(100vh - 94px);
  }
  .app-container {
    height: 100vh;
    width: 100%;
  }

  .left-container {
    /* background-color: #F38181; */
    height: 100%;
    // overflow: scroll;
  }

  .right-container {
    /* background-color: #FCE38A; */
    height: 100%;
  }

  .icon {
    &:hover {
      cursor: pointer;
    }
  }
  .muted {
    color: gray;
    font-size: 80%;
  }
  .table {
    width: 100%;
    margin-bottom: 1rem;
    background-color: white;
  }
  .table td, .table th {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
    border-right: 1px solid #dee2e6;
  }
  .table thead th {
    vertical-align: center;
    border-bottom: 2px solid #dee2e6;
    border-left: 1px solid #dee2e6;
    background-color: #143b17;
    color: white;
  }
  .btn-docx,
  .btn-pdf {
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
