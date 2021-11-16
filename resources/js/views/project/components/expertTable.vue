<template>
  <el-table
    :key="reload"
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

    <el-table-column align="center" label="Nama">
      <template slot-scope="scope">
        <el-input
          v-model="scope.row.name"
          class="edit-input"
          size="mini"
        />
      </template>
    </el-table-column>

    <el-table-column align="left" label="Keahlian">
      <template slot-scope="scope">
        <el-input
          v-model="scope.row.expertise"
          class="edit-input"
          size="mini"
        />
      </template>
    </el-table-column>

    <el-table-column align="left" label="Email">
      <template slot-scope="scope">
        <el-input
          v-model="scope.row.email"
          class="edit-input"
          size="mini"
        />
      </template>
    </el-table-column>

    <el-table-column align="left" label="cv">
      <template slot-scope="scope">
        <el-button
          icon="el-icon-document-copy"
          type="primary"
          size="mini"
          @click="checkDocFile('docfile' + scope.$index)"
        >Upload</el-button>
        <span>{{ scope.row.file ? scope.row.file : 'No File Selected..' }} </span>
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
  name: 'FormulatorTable',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
  },
  data() {
    return {
      reload: 0,
    };
  },
  computed: {
    getMembershipOption(){
      return this.$store.getters.membershipOptions;
    },
    getFormulators(){
      return this.$store.getters.formulators;
    },
  },
  methods: {
    checkDocFile(idx) {
      const id = '#' + idx;
      document.querySelector(id).click();
    },
    checkDocFileSure(id, x) {
      this.list[x].file = document.querySelector('#' + id).files[0].name;
      this.list[x].cv_penyusun = document.querySelector('#' + id).files[0];

      this.reload++;
    },
  },
};
</script>
