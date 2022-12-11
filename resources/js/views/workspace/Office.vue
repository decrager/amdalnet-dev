<template>
  <div class="app-container" style="position: relative; display: flex; flex-direction: column;">
    <div v-if="isAbleToComment" class="uji-collab">
      <div style="padding-bottom: 0.5rem;">
        <el-button @click="showHide">{{ !showForm ? 'Tampilkan Masukan Saran/Tanggapan' : 'Sembunyikan Masukan Saran/Tanggapan' }}</el-button>
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
              <Comment
                v-for="(comnt, index) in comments"
                :id="comnt.id"
                :key="index"
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
const workspaceResource = new WorkspaceResource();
const workspaceCommentResource = new Resource('workspace-comment');

export default {
  components: {
    Comment,
    NewComment,
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
      isStageError: false,
      idCat: null,
      rekaps: [],
      showForm: false,
      comments: [
        {
          no: 1,
          page: 1,
          suggest: 'Kurang Mantab',
          pageFix: 1,
          response: 'Masa sih',
        },
        {
          no: 2,
          page: 2,
          suggest: 'Kurang Mantab Gan',
          pageFix: 2,
          response: 'Masa sih bro',
        },
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
        'uklupl-mt.returned-examination',
        'uklupl-mt.matrix-ukl',
        'uklupl-mt.matrix-upl',
        'uklupl-mt.sent',
        'uklupl-mt.adm-review',
        'uklupl-mt.examination-invitation-drafting',
        'uklupl-mt.examination-invitation-sent',
        'uklupl-mt.examination',
        'uklupl-mt.examination-meeting',
        'uklupl-mt.ba-drafting',
        'uklupl-mt.ba-signed',
        'uklupl-mt.recommendation-drafting',
      ];
      console.log({ markingStatus: this.markingStatus });
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
    this.officeUrl = process.env.MIX_OFFICE_URL;
    this.addOfficeScript();
  },
  created() {
    this.loadWorkspaceType();
    this.getComments();
  },
  methods: {
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

    addOfficeScript() {
      const officeScript = document.createElement('script');
      console.log('x', process.env.MIX_OFFICE_URL, process.env.MIX_ETHERPAD_URL);
      officeScript.setAttribute('src', process.env.MIX_OFFICE_URL + '/web-apps/apps/api/documents/api.js');
      document.head.appendChild(officeScript);
      officeScript.onload = () => {
        this.createOfficeEditor();
      };
    },

    createOfficeEditor() {
      console.log('create office');
      let filename = this.filename;
      if (this.$route.params.filename) {
        filename = this.$route.params.filename;
      }

      let createTime = this.createTime;
      if (this.$route.params.createTime) {
        createTime = this.$route.params.createTime;
      }
      workspaceResource
        .getConfig(this.$route.params.id, filename, createTime)
        .then(resp => {
          console.log(resp);
          this.docEditor = new window.DocsAPI.DocEditor('placeholder', resp);
        });
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
</style>
