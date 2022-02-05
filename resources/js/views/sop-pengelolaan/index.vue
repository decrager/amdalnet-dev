<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <div class="filter-container">
        <el-button
          class="filter-item"
          type="primary"
          icon="el-icon-plus"
          @click="handleCreate"
        >
          {{ 'Tambah SOP Pengelolaan' }}
        </el-button>
      </div>
      <component-table
        :loading="loading"
        :list="allData"
        @handleEditForm="handleEditForm($event)"
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
  name: 'SopPengelolaan',
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
      optionValue: null,
      sort: 'DESC',
      keyword: '',
      limit: 10,
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
          `/api/template-ukl-upl-medium-low?type=&keyword=${this.keyword}&page=${this.listQuery.page}&sort=${this.sort}&limit=${this.limit}`
        )
        .then((response) => {
          this.allData = response.data.data;
          this.total = response.data.total;
          this.loading = false;
        });
    },
    handleCreate() {
      this.$router.push({
        name: 'CreateSopPengelolaan',
      });
    },
    handleDelete({ rows }) {
      this.$confirm('apakah anda yakin akan menghapus ' + rows.template_type + '. ?', 'Peringatan', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Batal',
        type: 'warning',
      })
        .then(() => {
          axios.get(`/api/template-ukl-upl-medium-low/delete/${rows.id}`)
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
  },
};
</script>

