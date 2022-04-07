<template>
  <div class="app-container">
    <div class="filter-container">
      <!-- <h2>Materi</h2> -->
      <el-button
        class="filter-item"
        type="primary"
        icon="el-icon-plus"
        @click="handleCreate"
      >
        {{ 'Tambah Kebijakan' }}
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
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import axios from 'axios';
import ComponentTable from './components/ComponentTable.vue';
export default {
  name: 'Kebijakan',
  components: {
    ComponentTable,
    Pagination,
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
    this.getRegulations();
    this.loading = false;
  },
  methods: {
    handleFilter() {
      this.getAll();
    },
    getAll(search, sort) {
      this.loading = true;
      axios
        .get(
          `/api/policys?page=${this.listQuery.page}&sort=${this.sort}&search=${this.listQuery.search}`
        )
        .then((response) => {
          this.allData = response.data.data.map((x) => {
            x.regulation_name = x.regulation ? x.regulation.name : '-';
            return x;
          });
          this.total = response.data.total;
          this.loading = false;
        });
    },
    getRegulations() {
      axios.get(`/api/regulations`).then((response) => {
        this.regulations = response.data;
      });
    },
    handleCreate() {
      this.$router.push({
        name: 'addKebijakan',
      });
    },
    handleDelete({ rows }) {
      this.$confirm(
        'apakah anda yakin akan menghapus ' + rows.id + '. ?',
        'Peringatan',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Batal',
          type: 'warning',
        }
      )
        .then(() => {
          axios
            .get(`/api/policys/delete/${rows.id}`)
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
