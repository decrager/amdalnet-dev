<template>
  <el-table
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
    style="margin-top: 15px"
  >
    <el-table-column align="center" label="No" width="50px">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Lampiran">
      <template slot-scope="scope">
        <span>{{ scope.row.name }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="File">
      <template slot-scope="scope">
        <a :href="scope.row.file" download>Unduh File PDF</a>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'Lampiran',
  data() {
    return {
      list: [],
    };
  },
};
</script>
