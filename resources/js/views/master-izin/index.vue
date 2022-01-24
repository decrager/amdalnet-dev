<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <div class="filter-container">
        <!-- <h2>Materi</h2> -->
        <el-button
          class="filter-item"
          type="primary"
          icon="el-icon-plus"
          @click="handleCreate"
        >
          {{ 'Tambah Izin' }}
        </el-button>
      </div>
      <!-- <el-table :data="tableData" border style="width: 100%">
        <el-table-column prop="no" label="No" width="70" />
        <el-table-column prop="judul" label="Judul Materi" />
        <el-table-column prop="deskripsi" label="Deskripsi" />
        <el-table-column prop="tanggal" label="Tanggal Terbit" />
        <el-table-column prop="download" label="Download" width="180">
          <template>
            <el-button type="text" href="#" icon="el-icon-edit">
              Ubah
            </el-button>
            <el-button type="text" href="#" icon="el-icon-delete">
              Hapus
            </el-button>
          </template>
        </el-table-column>
      </el-table> -->
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
      <!-- <component-dialog
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
import Pagination from '@/components/Pagination';
import axios from 'axios';
import ComponentTable from './components/ComponentTable.vue';

export default {
  name: 'DaftarIzin',
  components: {
    Pagination,
    ComponentTable,
    // ComponentDialog,
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
    };
  },
  created() {
    this.getAll();
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
          `/api/environmental-permit?page=${this.listQuery.page}&sort=${this.sort}`
        )
        .then((response) => {
          this.allData = response.data.data;
          this.total = response.data.total;
          this.loading = false;
        });
    },
    handleCreate() {
      this.$router.push({
        name: 'addMateri',
      });
    },
    handleDelete({ rows }) {
      this.$confirm('apakah anda yakin akan menghapus ' + rows.id + '. ?', 'Peringatan', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Batal',
        type: 'warning',
      })
        .then(() => {
          axios.get(`/api/materials/delete/${rows.id}`)
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

