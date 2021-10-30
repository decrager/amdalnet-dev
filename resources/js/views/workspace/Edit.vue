<template>
  <div class="app-container">
    <split-pane split="vertical" :min-percent="20" :default-percent="30" @resize="resize">
      <template slot="paneL">
        <div class="left-container" />
        <el-button
          type="primary"
          icon="el-icon-plus"
          @click="addNode"
        >
          {{ $t('addNode') }}
        </el-button>
        <vue-tree-list
          :model="data"
          default-tree-node-name="new node"
          default-leaf-node-name="new leaf"
          :default-expanded="false"
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
          <span slot="leafNodeIcon" class="icon">🍃</span>
          <span slot="treeNodeIcon" class="icon">🌲</span>
        </vue-tree-list>
        <el-button
          type="primary"
          icon="tree-table"
          @click="getNewTree"
        >
          {{ $t('getTree') }}
        </el-button>
        <pre>
          {{ newTree }}
        </pre>
      </template>
      <template slot="paneR">
        <iframe src="http://localhost:9001/p/xx" title="Document" frameborder="0" width="100%">
          need iframe
        </iframe>
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
  data() {
    return {
      newTree: {},
      data: new Tree([
        {
          name: 'Bagian Pengantar',
          id: 1,
          pid: 0,
          dragDisabled: true,
          addTreeNodeDisabled: true,
          addLeafNodeDisabled: true,
          editNodeDisabled: true,
          delNodeDisabled: true,
        },
        {
          name: 'Bagian Penanggung Jawab',
          id: 2,
          pid: 0,
          disabled: true,
        },
        {
          name: 'Rencana Usaha Kegiatan',
          id: 3,
          pid: 0,
        },
        {
          name: 'Matriks UKL-UPL',
          id: 4,
          pid: 0,
        },
        {
          name: 'Pernyataan',
          id: 5,
          pid: 0,
        },
      ]),
    };
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

<style>
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
  }
</style>

<style scoped>
  iframe {
    height: 80vh
  }
  .left-container {
    background-color: #F38181;
    height: 100%;
  }

  .right-container {
    background-color: #FCE38A;
    height: 200px;
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
