<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column label="No." width="54px">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Nama">
      <template slot-scope="scope">
        <span>{{ scope.row.name }}</span>
      </template>
    </el-table-column>

    <el-table-column label="NIK" align="center">
      <template slot-scope="scope">
        <span>{{ scope.row.nik }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Jabatan" align="center">
      <template slot-scope="scope">
        <span>{{ scope.row.position }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Instansi" align="center">
      <template slot-scope="scope">
        <span>{{ scope.row.institution }}</span>
      </template>
    </el-table-column>

    <el-table-column label="File" align="center">
      <template slot-scope="scope">
        <el-button
          v-if="scope.row.cv"
          type="text"
          size="medium"
          icon="el-icon-download"
          style="color: blue"
          @click.prevent="download(scope.row.cv)"
        >
          CV
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'ProfileMemberTable',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
  },
  methods: {
    download(url) {
      window.open(url, '_blank').focus();
    },
  },
};
</script>
