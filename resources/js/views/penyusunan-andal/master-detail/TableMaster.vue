<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    border
    highlight-current-row
    :header-cell-style="{
      background: 'rgb(58, 176, 111)',
      color: 'white',
      border: '0px',
    }"
    style="font-size: 12px"
  >
    <el-table-column
      label="No"
      width="50px"
      header-align="center"
      align="center"
    >
      <template slot-scope="scope">
        <span>
          {{ scope.$index + 1 }}
        </span>
      </template>
    </el-table-column>

    <el-table-column label="Jenis Dampak yang Timbul">
      <template slot-scope="scope">
        <span>
          {{ scope.row.name }}
        </span>
      </template>
    </el-table-column>

    <el-table-column label="" align="center" width="150px">
      <template slot-scope="scope">
        <el-button
          type="text"
          @click="showDetail(scope.row.stage, scope.row.id)"
        >
          Lihat
        </el-button>
        <el-button
          type="text"
          @click="showDetail(scope.row.stage, scope.row.id)"
        >
          Edit
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'TableMaster',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
  },
  methods: {
    showDetail(stage, id) {
      this.$emit('showDetail', { stage, id });
    },
  },
};
</script>
