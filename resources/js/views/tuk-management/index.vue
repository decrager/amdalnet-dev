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
          {{ 'Tambah TUK' }}
        </el-button>
      </div>
      <TukTable
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
import TukTable from '@/views/tuk-management/components/TukTable.vue';
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
const tukManagementResource = new Resource('tuk-management');

export default {
  name: 'TUKManagement',
  components: {
    TukTable,
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
      const { data, total } = await tukManagementResource.list(this.listQuery);
      this.list = data;
      this.total = total;
      this.loading = false;
    },
    handleSubmitRoute() {
      this.$router.push({ name: 'createTuk' });
    },
    handleDelete({ id, nama }) {
      this.$confirm(
        'Apakah anda yakin akan menghapus ' + nama + '. ?',
        'Peringatan',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Batal',
          type: 'warning',
        }
      )
        .then(() => {
          tukManagementResource
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
