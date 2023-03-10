<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <div class="filter-container">
        <el-button
          v-if="checkPermission(['manage env approve']) || checkRole(['admin'])"
          class="filter-item"
          type="primary"
          icon="el-icon-plus"
          @click="handleCreate"
        >
          {{ 'Tambah Persetujuan Lingkungan' }}
        </el-button>
        <el-row :gutter="32">
          <el-col :sm="24" :md="10">
            <el-input
              v-model="listQuery.search"
              suffix-icon="el-icon search"
              placeholder="Pencarian..."
              @input="inputSearch"
            >
              <el-button
                slot="append"
                icon="el-icon-search"
                @click="handleSearch"
              />
            </el-input>
          </el-col>
        </el-row>
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
import checkPermission from '@/utils/permission';
import checkRole from '@/utils/role';
import Pagination from '@/components/Pagination';
import axios from 'axios';
import ComponentTable from './components/ComponentTable.vue';

export default {
  name: 'DaftarIzins',
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
        search: null,
      },
      timeoutId: null,
      optionValue: null,
      sort: 'DESC',
    };
  },
  created() {
    this.getAll();
  },
  methods: {
    checkPermission,
    checkRole,
    handleFilter() {
      this.getAll();
    },
    getAll(search, sort) {
      this.loading = true;
      axios
        .get(
          `/api/environmental-approval?page=${this.listQuery.page}&sort=${this.sort}&search=${this.listQuery.search}`
        )
        .then((response) => {
          this.allData = response.data.data;
          this.total = response.data.total;
          this.loading = false;
        });
    },
    handleCreate() {
      this.$router.push({
        name: 'AddDaftarPersetujuan',
      });
    },
    handleDelete({ rows }) {
      this.$confirm(
        'apakah anda yakin akan menghapus ' + rows.template_type + '. ?',
        'Peringatan',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Batal',
          type: 'warning',
        }
      )
        .then(() => {
          axios
            .get(`/api/environmental-approval/delete/${rows.id}`)
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
    inputSearch(val) {
      if (this.timeoutId) {
        clearTimeout(this.timeoutId);
      }
      this.timeoutId = setTimeout(() => {
        this.listQuery.page = 1;
        this.listQuery.limit = 10;
        this.getAll();
      }, 500);
    },
    async handleSearch() {
      this.listQuery.page = 1;
      this.listQuery.limit = 10;
      await this.getAll();
      this.listQuery.search = null;
    },
  },
};
</script>
