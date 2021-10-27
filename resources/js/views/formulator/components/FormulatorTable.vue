<template>
  <el-table
    v-loading="loading"
    :data="list"
    border
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column align="center" label="Nama Penyusun" width="150px">
      <template slot-scope="scope">
        <span>{{ scope.row.name }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="No. Registrasi" width="150px">
      <template slot-scope="scope">
        <span>{{ scope.row.reg_no }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="No. Sertifikasi" width="150px">
      <template slot-scope="scope">
        <span>{{ scope.row.cert_no }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Status Keanggotaan" width="180px">
      <template slot-scope="scope">
        <span>{{ scope.row.membership_status }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Affiliasi" width="150px">
      <template slot-scope="scope">
        <span>{{ scope.row.id_institution }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Tgl. Ditetapkan" width="150px">
      <template slot-scope="scope">
        <span>{{ scope.row.date_start }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Terakhir Berlaku" width="150px">
      <template slot-scope="scope">
        <span>{{ scope.row.date_end }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="LSP Penerbit" width="150px">
      <template slot-scope="scope">
        <span>{{ scope.row.id_lsp }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Status" width="150px">
      <template slot-scope="scope">
        <el-tag :type="calculateStatus(scope.row.date_start, scope.row.date_end) | statusFilter">
          {{ calculateStatus(scope.row.date_start, scope.row.date_end) }}
        </el-tag>
      </template>
    </el-table-column>

    <el-table-column label="Sertifikat" width="150px">
      <template slot-scope="scope">
        <el-button
          type="text"
          size="medium"
          icon="el-icon-download"
          style="color:blue;"
          @click.prevent="download(scope.row.cert_file)"
        >
          Sertifikat
        </el-button>
        <el-button
          type="text"
          size="medium"
          icon="el-icon-download"
          style="color:blue;"
          @click.prevent="download(scope.row.cv_file)"
        >
          CV
        </el-button>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Aksi" fixed="right" width="150px">
      <template slot-scope="scope">
        <el-button
          type="primary"
          size="mini"
          icon="el-icon-edit"
          @click="handleEditForm(scope.row.id)"
        >
          Ubah
        </el-button>
        <el-button
          type="danger"
          size="mini"
          icon="el-icon-delete"
          @click="handleDelete(scope.row.id, scope.row.name)"
        >
          Hapus
        </el-button>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'FormulatorTable',
  filters: {
    statusFilter(status) {
      const statusMap = {
        Aktif: 'success',
        NonAktif: 'danger',
      };
      return statusMap[status];
    },
  },
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
  },
  methods: {
    calculateStatus(awal, akhir) {
      const tglAwal = new Date(awal);
      const tglAkhir = new Date(akhir);
      if (
        new Date().getTime() >= tglAwal.getTime() &&
        new Date().getTime() <= tglAkhir.getTime()
      ) {
        return 'Aktif';
      } else {
        return 'NonAktif';
      }
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
