<template>
  <el-table
    v-loading="loading"
    :header-cell-style="{ background: '#099C4B', color: 'white' }"
    :data="list"
    border
    fit
    highlight-current-row
  >
    <el-table-column align="center" label="No." width="80">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Tipe Dokumen">
      <template slot-scope="scope">
        <el-input
          v-model="scope.row.name"
          class="edit-input"
          size="small"
        />
      </template>
    </el-table-column>

    <el-table-column align="left" label="File">
      <template slot-scope="scope">
        <el-button
          icon="el-icon-document-copy"
          type="primary"
          size="mini"
          @click="checkDocFile('docfile' + scope.$index)"
        >Upload</el-button>
        <span>{{ scope.row.fileDoc ? scope.row.fileDoc.name : 'No File Selected..' }} </span>
        <!-- <span>{{ scope.row.fileDoc || 'No File Selected..' }} </span> -->
        <input
          :id="'docfile' + scope.$index"
          type="file"
          style="display: none"
          @change="checkDocFileSure('docfile' + scope.$index, scope.$index)"
        >
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'SupportTable',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
  },
  data() {
    return {};
  },
  methods: {
    checkDocFile(idx) {
      const id = '#' + idx;
      document.querySelector(id).click();
    },
    checkDocFileSure(id, x) {
      this.list[x].file = document.querySelector('#' + id).files[0].name;
      this.list[x].fileDoc = document.querySelector('#' + id).files[0];

      console.log(this.list[x].fileDoc);
    },
  },
};
</script>
