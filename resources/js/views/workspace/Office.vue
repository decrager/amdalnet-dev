<template>
  <div class="app-container" style="position: relative; display: flex; flex-direction: row;">
    <div id="etherpad-wrapper" style="height: 100%; width: 100%;">
      <div id="placeholder" />
    </div>
    <div class="uji-collab" style="margin-bottom: 10vh; max-width: 35%; height: 100%; background-color: #eeeee5;">
      <div style="align-content: center; justify-content: center; display: flex; position: absolute; right: 1.5rem; top: 1.7rem;">
        <el-button v-if="!showForm" style="border: 0; color: #363636; background-color: #bababa;" @click="showHide">&lt;</el-button>
        <el-button v-if="showForm" style="border: 0; color: #363636; background-color: #bababa;" @click="showHide">&gt;</el-button>
      </div>
      <div v-if="showForm" style="background-color: #404040; padding-top: 3rem; padding-right: 1rem; padding-left: 1rem; margin-left: 1px; height: 100%; overflow: scroll;">
        <div style="width: 100%;">
          <el-table
            :data="tableData"
            min-height="180"
            max-height="200"
          >
            <el-table-column
              label="Saran/ Pendapat/ Tanggapan"
              width="150"
            >
              <template v-slot="scope">
                <span v-html="scope ? scope.row.description : ''" />
              </template>
            </el-table-column>
            <el-table-column
              prop="page"
              label="Halaman"
              width="150"
            />
            <el-table-column
              label="Perbaikan/ Tanggapan Pemrakarsa"
              width="150"
            >
              <template v-slot="scope">
                <span v-html="scope ? scope.row.reply : ''" />
              </template>
            </el-table-column>
            <el-table-column
              prop="repair_page"
              label="Halaman Perbaikan"
              width="150"
            />
          </el-table>
        </div>
        <div style="background-color: #555555;">
          <div style="display: flex; align-items: center; padding: 5px; justify-content: space-between; flex-direction: row;">
            <div style="display: flex; align-items: center; padding: 5px; justify-content: space-between; width: 100%;">
              <div style="max-width: 50%; padding-right: 10px; color: #fff; font-weight: bold;">
                <span>Halaman</span>
              </div>
              <div style="max-width: 50%;">
                <el-input v-model="rekaps.page" type="number" :class="{ 'is-error': isStageError }" />
              </div>
            </div>
            <div style="display: flex; align-items: center; padding: 5px; justify-content: space-between; width: 100%;">
              <div style="max-width: 50%; padding-right: 10px; color: #fff; font-weight: bold;">
                <span>Halaman Perbaikan</span>
              </div>
              <div style="max-width: 50%;">
                <el-input v-model="rekaps.repair_page" type="number" :class="{ 'is-error': isStageError }" />
              </div>
            </div>
          </div>
        </div>
        <div style="background-color: #555555; padding-top: 5px;">
          <div style="padding: 5px; background-color: #39773b; color: #ffffff; font-weight: bold; text-align: center;">
            <span>Saran/ Pendapat/ Tanggapan</span>
          </div>
          <div style="padding: 5px;">
            <TextEditor
              v-model="rekaps.description"
              output-format="html"
              :menubar="''"
              :image="false"
              :toolbar="['bold italic underline bullist numlist fullscreen']"
              :height="50"
            />
          </div>
        </div>
        <div style="background-color: #555555; padding-top: 1rem;">
          <el-button v-if="showForm" style="border: 0; color: #363636; background-color: #bababa; width: 100%;margin-bottom: 1rem;" @click="addComment">Simpan</el-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import WorkspaceResource from '@/api/workspace';
import TextEditor from '@/components/Tinymce';
import { mapGetters } from 'vuex';
import Resource from '@/api/resource';
const workspaceResource = new WorkspaceResource();
const workspaceCommentResource = new Resource('workspace-comment');

export default {
  components: {
    TextEditor,
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
      tableData: [{
        date: '2016-05-02',
        name: 'Tom',
        state: 'California',
        city: 'Los Angeles',
        address: 'No. 189, Grove St, Los Angeles',
        zip: 'CA 90036',
      }],
    };
  },
  computed: {
    ...mapGetters({
      'userInfo': 'user',
      'userId': 'userId',
    }),
    isPenyusun() {
      return this.userInfo.roles.includes('formulator');
    },
    isPemrakarsa() {
      return this.userInfo.roles.includes('initiator');
    },
    isTUK() {
      return this.userInfo.roles.includes('examiner-substance');
    },
    padSrc() {
      if (this.sessionID) {
        return process.env.MIX_OFFICE_URL + '/auth_session?sessionID=' + this.sessionID + '&padName=' + this.selectedTreeId;
      }
      return '';
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
    console.log('props:', this.$route.params.id, this.project, process.env.MIX_BASE_API);
    this.officeUrl = process.env.MIX_OFFICE_URL;
    this.addOfficeScript();
  },
  methods: {
    clearError() {
      this.isStageError = false;
    },
    resize() {
      console.log('resize');
    },
    showHide() {
      console.log({ route: this.$route });
      this.showForm = !this.showForm;
    },
    addComment() {
      console.log({ rekap: this.rekaps.description });
      console.log({ useriInfo: this.userInfo });
      console.log({ store: this.$store });
      console.log({ route: this.$route });
      console.log({ route: this.workspaceType });
      // this.tableData.push(this.rekaps);
      // this.clearError();
      // if() {}
      this.handleSubmitCommnet();
    },
    async handleSubmitCommnet() {
      const newComment = await workspaceCommentResource.store({
        id_user: this.userInfo.id,
        name: this.userInfo.name,
        id_project: this.$route.params.id,
        page: this.rekaps.page,
        description: this.rekaps.description,
        repair_page: this.userInfo.roles.includes('examiner-substance') ? null : this.rekaps.repair_page,
        reply_to: this.userInfo.roles.includes('examiner-substance') ? null : this.rekaps.page,
        document_type: this.workspaceType,
        filename_document: this.filename,
      });

      this.rekaps.unshift(newComment);
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

<style scoped lang="scss">
.is-error .el-input__inner,
.is-error .el-textarea__inner {
  border-color: #f56c6c;
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
</style>
