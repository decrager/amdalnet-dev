<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <div class="filter-container">
        <el-button
          class="filter-item"
          type="primary"
          icon="el-icon-plus"
          @click="handleSubmitRoute"
        >
          {{ 'Tambah Pegawai TUK' }}
        </el-button>
      </div>
      <EmployeeTable
        :list="list"
        :loading="loading"
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
import EmployeeTable from '@/views/employee/components/EmployeeTable.vue';
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
const employeeTukResource = new Resource('employee-tuk');

export default {
  name: 'Employee',
  components: {
    EmployeeTable,
    Pagination,
  },
  data() {
    return {
      list: [],
      loading: false,
      listQuery: {
        page: 1,
        limit: 10,
        type: 'list',
      },
      total: 0,
    };
  },
  created() {
    this.getData();
  },
  methods: {
    handleFilter() {
      this.getData();
    },
    async getData() {
      this.loading = true;
      const { data, total } = await employeeTukResource.list(this.listQuery);
      this.list = data;
      this.total = total;
      this.loading = false;
    },
    handleSubmitRoute() {
      this.$router.push({ name: 'createEmployeeTuk' });
    },
    handleDelete({ id, nama }) {
      this.$confirm(
        'apakah anda yakin akan menghapus ' + nama + '. ?',
        'Peringatan',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Batal',
          type: 'warning',
        }
      )
        .then(() => {
          employeeTukResource
            .destroy(id)
            .then((response) => {
              this.$message({
                type: 'success',
                message: 'Hapus Selesai',
              });
              this.getData();
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
