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
        :list="allData"
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
    </el-card>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import axios from 'axios';
import ComponentTable from './components/ComponentTable.vue';

export default {
  name: 'AppParamtList',
  components: {
    Pagination,
    ComponentTable,
  },
  data() {
    return {
      loading: true,
      allData: [],
      total: 0,
      listQuery: {
        page: 1,
        limit: 10,
      },
      sort: 'DESC',
    };
  },
  created() {
    this.getAll();
  },
  methods: {
    handleFilter() {
      this.getAll();
    },
    getAll(search, sort) {
      this.loading = true;
      axios
        .get(
          `/api/peraturan?page=${this.listQuery.page}&sort=${this.sort}`
        )
        .then((response) => {
          this.allData = response.data.data;
          this.total = response.data.total;
          this.loading = false;
        });
    },
    handleCreate() {
      this.$router.push({
        name: 'addPeraturan',
      });
    },
    handleDelete({ rows }) {
      this.$confirm('apakah anda yakin akan menghapus ' + rows.id + '. ?', 'Peringatan', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Batal',
        type: 'warning',
      })
        .then(() => {
          axios.get(`/api/peraturan/delete/${rows.id}`)
            .then((response) => {
              this.$message({
                type: 'success',
                message: 'Hapus Selesai',
              });
              this.getAll();
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
