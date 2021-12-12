<template>
  <el-table
    v-loading="loading"
    :data="informations"
    :show-header="false"
    style="width: 100%"
    fit
    :span-method="arraySpanMethod"
    :row-class-name="tableRowClassName"
  >
    <el-table-column prop="title" label="Title">
      <template slot-scope="scope">
        <span
          :class="{
            'small-desc': scope.row.wider && scope.row.type === 'description',
          }"
          v-html="scope.row.title"
        />
      </template>
    </el-table-column>
    <el-table-column label="description">
      <template slot-scope="scope">
        <span>{{ scope.row.description }}</span>
      </template>
    </el-table-column>
  </el-table>
</template>

<style>
.el-table .success-row {
  background: #f0f9eb;
}
.small-desc {
  font-size: 12px;
}
</style>

<script>
import Resource from '@/api/resource';
const skklResource = new Resource('skkl');

export default {
  name: 'Information',
  data() {
    return {
      informations: [],
      idProject: this.$route.params.id,
      loading: false,
    };
  },
  created() {
    this.getInformations();
  },
  methods: {
    tableRowClassName({ row, rowIndex }) {
      if (rowIndex % 2 === 0) {
        return 'success-row';
      }
      return '';
    },
    async getInformations() {
      this.loading = true;
      const data = await skklResource.list({
        information: 'true',
        idProject: this.idProject,
      });
      this.informations = data;
      this.loading = false;
    },
    arraySpanMethod({ row, column, rowIndex, columnIndex }) {
      if (row.wider === true) {
        return [1, 2];
      }
    },
  },
};
</script>
