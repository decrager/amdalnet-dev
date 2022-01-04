<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <div class="filter-container">
        <h2>Peraturan</h2>
        <el-button
          class="filter-item"
          type="primary"
          icon="el-icon-plus"
          @click="handleCreate"
        >
          {{ 'Tambah Peraturan' }}
        </el-button>
      </div>
      <component-table
        :loading="loading"
        :list="tableData"
        @handleEditForm="handleEditForm($event)"
        @handleView="handleView($event)"
        @handleDelete="handleDelete($event)"
      />
      <!-- <pagination
        v-show="total > 0"
        :total="total"
        :page.sync="listQuery.page"
        :limit.sync="listQuery.limit"
        @pagination="handleFilter"
      />
      <component-dialog
        :component="component"
        :show="show"
        :list-view="listView"
        :list-view-title="listViewTitle"
        @handleCancelComponent="handleCancelComponent"
      /> -->
    </el-card>
  </div>
</template>

<script>
// import Resource from '@/api/resource';
// import Pagination from '@/components/Pagination';
import ComponentTable from './components/ComponentTable.vue';
// import ComponentDialog from './components/ComponentDialog.vue';
// const appParamResource = new Resource('app-params');

export default {
  name: 'AppParamtList',
  components: {
    // Pagination,
    ComponentTable,
    // ComponentDialog,
  },
  data() {
    return {
      tableData: [
        { peraturan: 'Ketetapan Majelis Permusyawaratan Rakyat (Tap MPR)' },
      ],
    };
  },
  created() {
    // this.getList();
  },
  methods: {
    // handleFilter() {
    //   this.getList();
    // },
    // async getList() {
    //   this.loading = true;
    //   const { data, total } = await appParamResource.list(this.listQuery);
    //   this.list = data;
    //   this.total = total;
    //   this.loading = false;
    // },
    // async getListView(name) {
    //   const { data } = await appParamResource.get(name);
    //   this.listView = data;
    // },
    handleCreate() {
      this.$router.push({
        name: 'addPeraturan',
      });
    },
    // handleEditForm(row) {
    //   const currentParam = this.list.find((element) => element.parameter_name === row.parameter_name);
    //   this.$router.push({
    //     name: 'updateParams',
    //     params: { row, appParams: currentParam, tableView: false },
    //   });
    // },
    // handleDelete({ rows }) {
    //   this.$confirm('apakah anda yakin akan menghapus ' + rows.id + '. ?', 'Peringatan', {
    //     confirmButtonText: 'OK',
    //     cancelButtonText: 'Batal',
    //     type: 'warning',
    //   })
    //     .then(() => {
    //       appParamResource
    //         .destroy(rows.id)
    //         .then((response) => {
    //           this.$message({
    //             type: 'success',
    //             message: 'Hapus Selesai',
    //           });
    //           this.getList();
    //         })
    //         .catch((error) => {
    //           console.log(error);
    //         });
    //     })
    //     .catch(() => {
    //       this.$message({
    //         type: 'info',
    //         message: 'Hapus Digagalkan',
    //       });
    //     });
    // },
  },
};
</script>
<style scoped>
h2 {
  margin: 0 0 2rem 0;
}
.el-dialog__body {
  padding-top: 0 !important;
}
</style>
