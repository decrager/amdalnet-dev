<template>
  <div class="app-container">
    <split-pane split="vertical" :min-percent="20" :default-percent="30" @resize="resize">
      <template slot="paneL">
        <div class="left-container">
          <el-upload
            class="avatar-uploader"
            action="#"
            :show-file-list="false"
            :on-change="handleTemplateUploadChange"
            :before-upload="beforeTemplateUpload"
            :auto-upload="false"
          >
            <el-button
              type="primary"
              icon="el-icon-upload"
              :loading="loading"
            >
              {{ $t('uploadTemplate') }}
            </el-button>
          </el-upload>
          <vue-tree-list
            :model="data"
            default-tree-node-name="new node"
            default-leaf-node-name="new leaf"
            :default-expanded="true"
            @click="onClick"
            @change-name="onChangeName"
            @delete-node="onDel"
            @add-node="onAddNode"
          >
            <template v-slot:leafNameDisplay="slotProps">
              <span>
                {{ slotProps.model.name }} <span class="muted">#{{ slotProps.model.id }}</span>
              </span>
            </template>
            <span slot="addTreeNodeIcon" class="icon">
              <i class="el-icon-document" />
            </span>
            <span slot="addLeafNodeIcon" class="icon">
              <i class="el-icon-plus" />
            </span>
            <span slot="editNodeIcon" class="icon">
              <i class="el-icon-edit" />
            </span>
            <span slot="delNodeIcon" class="icon">
              <i class="el-icon-delete" />
            </span>
            <span slot="leafNodeIcon" class="icon">
              <!-- <i class="el-icon-minus" /> -->
            </span>
            <span slot="treeNodeIcon" class="icon">
              <!-- <i class="el-icon-plus" /> -->
            </span>
          </vue-tree-list>
          <!-- <el-button
            type="primary"
            icon="tree-table"
            @click="getNewTree"
          >
            {{ $t('getTree') }}
          </el-button> -->
          <!-- <pre>
            {{ newTree }}
          </pre> -->
        </div>
      </template>
      <template slot="paneR">
        <div class="right-container">
          <div id="placeholder" />
        </div>
      </template>
    </split-pane>
  </div>
</template>

<script>
import { VueTreeList, Tree, TreeNode } from 'vue-tree-list';
import SplitPane from 'vue-splitpane';
import WorkspaceResource from '@/api/workspace';

const workspaceResource = new WorkspaceResource();

export default {
  components: {
    VueTreeList,
    SplitPane,
  },
  props: {
    project: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      newTree: {},
      officeUrl: '',
      selectedTreeId: 1,
      sessionID: null,
      loading: false,
      docEditor: null,
      data: new Tree([
        {
          name: 'Kata Pengantar',
          id: 1,
          pid: 0,
          dragDisabled: true,
          addTreeNodeDisabled: true,
          addLeafNodeDisabled: true,
          editNodeDisabled: true,
          delNodeDisabled: true,
        },
        {
          name: 'Identitas Penanggung Jawab',
          id: 2,
          pid: 0,
        },
        {
          name: 'Deskripsi Rencana Usaha',
          id: 3,
          pid: 0,
          children: [
            {
              name: 'Nama Usaha / Kegiatan Usaha',
              id: 31,
              pid: 3,
            },
            {
              name: 'Lokasi Usaha dan atau Kegiatan',
              id: 32,
              pid: 3,
            },
            {
              name: 'Skala/Besaran Rencana Usaha atau Kegiatan',
              id: 33,
              pid: 3,
              children:
              [
                {
                  name: 'Penggunaan Lahan',
                  id: 331,
                  pid: 33,
                },
                {
                  name: 'Persetujuan Teknis',
                  id: 332,
                  pid: 33,
                },
                {
                  name: 'Jenis Pelayanan dan Kapasitas Usaha',
                  id: 333,
                  pid: 33,
                },
                {
                  name: 'Peralatan dan Kegiatan Usaha',
                  id: 334,
                  pid: 33,
                },
              ],
            },
            {
              name: 'Garis Besar Komponen Rencana Usaha atau Kegiatan',
              id: 34,
              pid: 3,
            },
          ],
        },
        {
          name: 'Matriks UKL-UPL',
          id: 4,
          pid: 0,
        },
        {
          name: 'Surat Pernyataan',
          id: 5,
          pid: 0,
        },
        {
          name: 'Daftar Pustaka',
          id: 6,
          pid: 0,
        },
      ]),
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

    onDel(node) {
      console.log(node);
      node.remove();
    },

    onChangeName(params) {
      console.log(params);
    },

    onAddNode(params) {
      console.log(params);
    },

    onClick(params) {
      console.log(params);
      this.selectedTreeId = params.id;
    },

    addNode() {
      var node = new TreeNode({ name: 'new node', isLeaf: false });
      if (!this.data.children) {
        this.data.children = [];
      }
      this.data.addChildren(node);
    },

    getNewTree() {
      var vm = this;
      function _dfs(oldNode) {
        var newNode = {};

        for (var k in oldNode) {
          if (k !== 'children' && k !== 'parent') {
            newNode[k] = oldNode[k];
          }
        }

        if (oldNode.children && oldNode.children.length > 0) {
          newNode.children = [];
          for (var i = 0, len = oldNode.children.length; i < len; i++) {
            newNode.children.push(_dfs(oldNode.children[i]));
          }
        }
        return newNode;
      }

      vm.newTree = _dfs(vm.data);
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
          this.data = new Tree(response);
          console.log(this.data);
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
      officeScript.setAttribute('src', 'http://localhost/web-apps/apps/api/documents/api.js');
      document.head.appendChild(officeScript);
      officeScript.onload = () => {
        // this.createOfficeEditor();
        const config = {
          'width': '100%',
          'height': '100%',
          'type': 'desktop',
          'document': {
            'fileType': 'docx',
            'key': '172.23.0.1http___localhost_example_files_172.23.0.1_UKL_20UPL_20SPBU_20-_20Edit_20Nafila_edit_20FM.docx1637464264620',
            'title': 'UKL UPL SPBU - Edit Nafila_edit FM.docx',
            'url': 'http://localhost/example/download?fileName=UKL%20UPL%20SPBU%20-%20Edit%20Nafila_edit%20FM.docx&useraddress=172.23.0.1',
          },
          'documentType': 'word',
          'editorConfig': {
            'user': {
              'group': 'ukl/upl',
              'id': '123456x',
              'name': 'ryan ryvees',
            },
            'customization': {
              'compactHeader': true,
              'compactToolbar': true,
              'compatibleFeatures': true,
              'toolbarHideFileName': true,
              'toolbarNoTabs': true,
              'hideRightMenu': true,
              'hideRulers': true,
              'help': false,
              'macros': false,
              'plugins': false,
              'reviewDisplay': 'markup',
              'customer': {
                'address': 'Jakarta, KLHK',
                'info': '',
                'logo': 'http://localhost:8000/images/logo-amdal-white.png',
                'mail': 'admin@amdalnet.dev',
                'name': 'AMDALNET',
                'www': 'example.com',
              },
              'logo': {
                'image': 'http://localhost:8000/images/logo-amdal-white.png',
                'imageEmbedded': 'http://localhost:8000/images/logo-amdal-white.png',
                'url': '',
              },
            },
            'callbackUrl': 'http://localhost/example/track?filename=UKL%20UPL%20SPBU%20-%20Edit%20Nafila_edit%20FM.docx&useraddress=172.23.0.1',
          },
        };
        this.docEditor = new window.DocsAPI.DocEditor('placeholder', config);
      };
    },

    createOfficeEditor() {
      console.log('create office');
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
