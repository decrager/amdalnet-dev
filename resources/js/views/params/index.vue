<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <div class="filter-container">
        <h2>Daftar Parameter Aplikasi</h2>
        <el-button
          class="filter-item"
          type="warning"
          icon="el-icon-plus"
          @click="handleCreate"
        >
          {{ 'Tambah Params' }}
        </el-button>
      </div>
      <component-table
        :loading="loading"
        :list="list"
        @handleEditForm="handleEditForm($event)"
        @handleView="handleView($event)"
        @handleDelete="handleDelete($event)"
      />
      <pagination
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
      />
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination';
import ComponentTable from './components/ComponentTable.vue';
import ComponentDialog from './components/ComponentDialog.vue';
const appParamResource = new Resource('app-params');

export default {
  name: 'AppParamtList',
  components: {
    Pagination,
    ComponentTable,
    ComponentDialog,
  },
  data() {
    return {
      list: [],
      listView: [],
      listViewTitle: '',
      loading: true,
      listQuery: {
        page: 1,
        limit: 10,
      },
      total: 0,
      show: false,
      componentOptions: [],
      component: {},
    };
  },
  created() {
    this.getList();
  },
  methods: {
    // handleSubmitComponent() {
    //   if (this.component.id !== undefined) {
    //     appParamResource
    //       .updateMultipart(this.component.id, this.component)
    //       .then((response) => {
    //         this.$message({
    //           type: 'success',
    //           message: 'Komponen Berhasil Diupdate',
    //           duration: 5 * 1000,
    //         });
    //         this.show = false;
    //         this.component = {};
    //         this.getList();
    //       })
    //       .catch((error) => {
    //         console.log(error);
    //       });
    //   } else {
    //     appParamResource
    //       .store(this.component)
    //       .then((response) => {
    //         this.$message({
    //           message: 'Komponen ' + this.component.name + ' Berhasil Dibuat',
    //           type: 'success',
    //           duration: 5 * 1000,
    //         });
    //         this.show = false;
    //         this.component = {};
    //         this.getList();
    //       })
    //       .catch((error) => {
    //         console.log(error);
    //       });
    //   }
    // },
    handleFilter() {
      this.getList();
    },
    async getList() {
      this.loading = true;
      const { data, total } = await appParamResource.list(this.listQuery);
      this.list = data;
      this.total = total;
      this.loading = false;
    },
    async getListView(name) {
      const { data } = await appParamResource.get(name);
      console.log(data);
      this.listView = data;
    },
    handleCreate() {
      this.$router.push({
        name: 'addParams',
        params: {},
      });
    },
    handleEditForm(row) {
      // this.component = this.list.find((element) => element.id === id);
      // this.show = true;
      this.$router.push({
        name: 'updateParams',
        params: {},
      });
    },
    handleView(row) {
      this.getListView(row.parameter_name);
      this.listViewTitle = row.parameter_name;
      this.show = true;
    },
    handleCancelComponent(){
      this.show = false;
    },
    handleDelete({ rows }) {
      this.$confirm('apakah anda yakin akan menghapus ' + rows.parameter_name + '. ?', 'Peringatan', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Batal',
        type: 'warning',
      })
        .then(() => {
          appParamResource
            .destroy(rows.parameter_name)
            .then((response) => {
              this.$message({
                type: 'success',
                message: 'Hapus Selesai',
              });
              this.getList();
            })
            .catch((error) => {
              console.log(error);
            });
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: 'Hapus Digagalkan',
          });
        });
    },
  },
};
</script>
<style scoped>
  h2 {margin: 0 0 2rem 0;}
  .el-dialog__body{padding-top: 0 !important;}
</style>
