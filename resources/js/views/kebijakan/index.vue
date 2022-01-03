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
          {{ 'Tambah Kebijakan' }}
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
  name: 'Kebijakan',
  components: {
    // Pagination,
    ComponentTable,
    // ComponentDialog,
  },
  data() {
    return {
      tableData: [
        {
          keb_pu: 'UU RI No. 32 Tahun 2009',
          jen_pu: 'Ketetapan Majelis Permusyawaratan Rakyat (Tap MPR)',
          kegiatan: 'Dokumen Lingkungan',
          tentang:
            'Undang - undang republik Indonesia tentang Perlindungan dan Pengelolaan Lunkung hidup',
          tanggal: '4 November 2021',
          link: 'Download',
        },
      ],
    };
  },
  created() {},
  methods: {
    handleCreate() {
      this.$router.push({
        name: 'addKebijakan',
      });
    },
  },
};
</script>

