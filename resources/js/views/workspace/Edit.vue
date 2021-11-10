<template>
  <div class="app-container">
    <split-pane split="vertical" :min-percent="20" :default-percent="30" @resize="resize">
      <template slot="paneL">
        <div class="left-container">
          <!-- <el-button
            type="primary"
            icon="el-icon-plus"
            @click="addNode"
          >
            {{ $t('addNode') }}
          </el-button> -->
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
          <iframe :src="padSrc" title="Document" frameborder="0" width="100%">
            need iframe
          </iframe>
        </div>
      </template>
    </split-pane>
  </div>
</template>

<script>
import { VueTreeList, Tree, TreeNode } from 'vue-tree-list';
import SplitPane from 'vue-splitpane';

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
      selectedTreeId: 1,
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
      return 'http://localhost:9001/p/' + this.selectedTreeId;
    },
  },
  async mounted() {
    console.log('props:', this.id, this.project);
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
  },
};
</script>

<style lang="scss">
  .vtl {
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
        padding: 0 5px;
      }
    }
  }
</style>

<style scoped>
  iframe {
    height: calc(100vh - 94px);
  }
  .left-container {
    background-color: #F38181;
    height: 100%;
  }

  .right-container {
    background-color: #FCE38A;
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
