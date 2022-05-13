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
          <p class="mb-1 ml-1">{{ formatDateStr(scope.row.set) }}</p>
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

    <el-table-column label="Kebijakan PPU" prop="regulation_no" sortable />
    <el-table-column label="Jenis PPU" prop="regulation_name" sortable />
    <el-table-column
      label="Bidang Kegiatan"
      prop="field_of_activity"
      sortable
    />
    <el-table-column label="Tentang" prop="about" sortable />
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
      const currentParam = this.list.find(
        (element) => element.parameter_name === row.parameter_name
      );
      this.$router.push({
        name: 'editKebijakan',
        params: { row, appParams: currentParam },
      });
    },
    handleDelete(rows) {
      this.$emit('handleDelete', { rows });
    },
    formatDateStr(date) {
      const today = new Date(date);
      var bulan = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
      ];
      const year = today.getFullYear();
      const month = today.getMonth();
      const day = today.getDate();
      const monthID = bulan[month];
      const finalDate = `${day} ${monthID} ${year}`;
      return finalDate;
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
