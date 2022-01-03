<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <div class="filter-container">
        <h2>Materi</h2>
        <el-button
          class="filter-item"
          type="primary"
          icon="el-icon-plus"
          @click="handleCreate"
        >
          {{ 'Tambah Materi' }}
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
      /> -->
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
// import Resource from '@/api/resource';
// import Pagination from '@/components/Pagination';
import ComponentTable from './components/ComponentTable.vue';
// import ComponentDialog from './components/ComponentDialog.vue';
// const appParamResource = new Resource('app-params');

export default {
  name: 'Materi',
  components: {
    // Pagination,
    ComponentTable,
    // ComponentDialog,
  },
  data() {
    return {
      tableData: [
        {
          judul:
            'Permenlhk NO P 23- 2018 Perubahan Izin Lingkungan di luar OSS',
          deskripsi: 'Materi Bimtek Hotel Mercure Ancol, 25 Juli 2019',
          tanggal: '27 Oktober 2021',
          link: 'Download',
        },
      ],
    };
  },
  created() {},
  methods: {
    handleCreate() {
      this.$router.push({
        name: 'addMateri',
      });
    },
  },
};
</script>

