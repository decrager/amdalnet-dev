<template>
  <div class="app-container">
    <div id="placeholder" />
  </div>
</template>

<script>
import WorkspaceResource from '@/api/workspace';

const workspaceResource = new WorkspaceResource();

export default {
  components: {
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
  },
  data() {
    return {
      officeUrl: '',
      selectedTreeId: 1,
      sessionID: null,
      loading: false,
      docEditor: null,
    };
  },
  computed: {
    padSrc() {
      console.log('src:', process.env.MIX_OFFICE_URL, this.selectedTreeId);
      if (this.sessionID) {
        return process.env.MIX_OFFICE_URL + '/auth_session?sessionID=' + this.sessionID + '&padName=' + this.selectedTreeId;
      }
      return '';
    },
  },
  async mounted() {
    console.log('props:', this.$route.params.id, this.project, process.env.MIX_BASE_API);
    // console.log(process.env.MIX_OFFICE_URL);
    this.officeUrl = process.env.MIX_OFFICE_URL;
    this.addOfficeScript();
  },
  methods: {
    resize() {
      console.log('resize');
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
      officeScript.setAttribute('src', 'https://amdalnet.braindevs.com/oods/web-apps/apps/api/documents/api.js');
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

      workspaceResource
        .getConfig(this.$route.params.id, filename)
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
  iframe {
    height: calc(100vh - 94px);
  }
  .app-container {
    height: 100vh;
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
