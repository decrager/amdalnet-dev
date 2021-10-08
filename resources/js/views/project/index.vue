<template>
  <div style="padding: 24px" class="app-container">
    <div class="filter-container">
      <el-button
        size="mini"
        class="filter-item"
        type="primary"
        icon="el-icon-plus"
        @click="handleCreate"
      >
        {{ $t('table.add') + ' Kegiatan' }}
      </el-button>
    </div>
    <el-table
      v-loading="loading"
      :data="filtered"
      border
      fit
      highlight-current-row
    >
      <el-table-column align="center" label="Nama Kegiatan" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.project_name }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Lokasi" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.location }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Jenis Dokumen" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.document_type }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" :label="'Deskripsi Kegiatan'" sortable width="200">
        <template slot-scope="scope">
          <span>{{ scope.row.project_description }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Provinsi / Kota" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.state }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Tgl Input" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.input_date }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Tahap" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.step }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Aksi">
        <template slot-scope="scope">
          <el-button
            type="primary"
            size="mini"
            icon="el-icon-edit"
            @click="handleEditForm(scope.row.id)"
          >
            Edit
          </el-button>
          <el-button
            type="danger"
            size="mini"
            icon="el-icon-delete"
            @click="handleDelete(scope.row.id, scope.row.nama_penyusun)"
          >
            Delete
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <el-divider />
    <div style="text-align: center">
      <el-pagination
        background
        layout="prev, pager, next"
        :page-size="pageSize"
        :total="total"
        @current-change="handleCurrentChange"
      />
    </div>
  </div>
</template>

<script>
export default {
  name: 'Project',
  data() {
    return {
      loading: false,
      filtered: [],
      search: '',
      page: 1,
      pageSize: 4,
      total: 0,
    };
  },
  methods: {
    handleCreate() {
      this.$router.push({
        name: 'createProject',
        params: { project: {}},
      });
    },
    handleCurrentChange(val) {
      this.page = val;
    },
    searching() {
      if (!this.search) {
        this.total = this.categories.length;
        return this.categories;
      }
      this.page = 1;
      return this.categories.filter((data) =>
        data.name.toLowerCase().includes(this.search.toLowerCase())
      );
    },
    displayData() {
      this.total = this.searching.length;

      return this.searching.slice(
        this.pageSize * this.page - this.pageSize,
        this.pageSize * this.page
      );
    },
    download(url) {
      window.open(url, '_blank').focus();
    },
    handleEditForm(id) {
      this.$emit('handleEditForm', id);
    },
    handleDelete(id, nama) {
      this.$emit('handleDelete', { id, nama });
    },
  },
};
</script>

