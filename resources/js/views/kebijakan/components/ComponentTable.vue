<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white', border: '0' }"
  >
    <el-table-column type="expand">
      <template slot-scope="scope">
        <div class="d-flex pl-1">
          <p class="mb-1 fw-bold">Taggal Terbit :</p>
          <p class="mb-1 ml-1">{{ scope.row.tanggal }}</p>
        </div>
        <div class="d-flex pl-1">
          <p class="mb-1 fw-bold">Link Download :</p>
          <p class="mb-1 ml-1">{{ scope.row.link }}</p>
        </div>
        <div class="d-flex pl-1">
          <p class="mb-1 fw-bold">Aksi :</p>
          <p class="mb-1 ml-1">
            <el-button
              type="text"
              href="#"
              icon="el-icon-view"
              @click="handleView(scope.row)"
            >
              View
            </el-button>
            <el-button
              type="text"
              href="#"
              icon="el-icon-edit"
              @click="handleEditForm(scope.row)"
            >
              Ubah
            </el-button>
            <el-button
              type="text"
              href="#"
              icon="el-icon-delete"
              @click="handleDelete(scope.row)"
            >
              Hapus
            </el-button>
          </p>
        </div>
      </template>
    </el-table-column>
    <el-table-column label="No." width="70px">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Kebijakan PPU">
      <template slot-scope="scope">
        <span>{{ scope.row.keb_pu }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Jenis PPU">
      <template slot-scope="scope">
        <span>{{ scope.row.jen_pu }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Bidang Kegiatan">
      <template slot-scope="scope">
        <span>{{ scope.row.kegiatan }}</span>
      </template>
    </el-table-column>
    <el-table-column label="Tentang">
      <template slot-scope="scope">
        <span>{{ scope.row.tentang }}</span>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'ComponentTable',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
  },
  methods: {
    handleView(row) {
      this.$emit('handleView', row);
    },
    handleEditForm(row) {
      this.$emit('handleEditForm', row);
    },
    handleDelete(rows) {
      this.$emit('handleDelete', { rows });
    },
  },
};
</script>
<style rel="stylesheet/scss" lang="scss" scoped>
.el-table-column {
  border: none;
}
.d-flex {
  display: flex;
  align-items: center;
}
p {
  margin: 0;
}
.mb-1 {
  margin-bottom: 0.5rem;
}
.pl-1 {
  padding-left: 0.5rem;
}
.ml-1 {
  margin-left: 0.5rem;
}
.fw-bold {
  font-weight: bold;
}
</style>
