<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <!-- <el-table-column type="expand">
      <template slot-scope="scope">
        <div class="expand-container">
          <div>
            <p><b>Status Keanggotaan: </b>{{ scope.row.membership_status }}</p>
            <p><b>Affiliasi: </b>{{ scope.row.id_institution }}</p>
            <p><b>Tgl. Ditetapkan: </b>{{ scope.row.date_start }}</p>
            <p><b>Terakhir Berlaku: </b>{{ scope.row.date_end }}</p>
            <p><b>LSP Penerbit: </b>{{ scope.row.id_lsp }}</p>
          </div>
          <div class="expand-container__right">
            <el-button
              type="text"
              href="#"
              icon="el-icon-edit"
              @click="handleEditForm(scope.row.id)"
            >
              Ubah
            </el-button>
            <el-button
              type="text"
              href="#"
              icon="el-icon-delete"
              @click="handleDelete(scope.row.id, scope.row.name)"
            >
              Hapus
            </el-button>
          </div>
        </div>
      </template>
    </el-table-column> -->

    <el-table-column align="center" label="Nama">
      <template slot-scope="scope">
        <span>{{ scope.row.name }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="No. Registrasi">
      <template slot-scope="scope">
        <span>{{ scope.row.reg_no }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="No. Sertifikat">
      <template slot-scope="scope">
        <span>{{ noCertificate(scope.row.cert_no) }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Sertifikasi">
      <template slot-scope="scope">
        <span>{{
          scope.row.membership_status ? scope.row.membership_status : '-'
        }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Status">
      <template slot-scope="scope">
        <el-tag
          :type="
            calculateStatus(scope.row.date_start, scope.row.date_end)
              | statusFilter
          "
        >
          {{ calculateStatus(scope.row.date_start, scope.row.date_end) }}
        </el-tag>
      </template>
    </el-table-column>

    <el-table-column label="File">
      <template slot-scope="scope">
        <el-button
          type="text"
          size="medium"
          icon="el-icon-download"
          style="color: blue"
          @click.prevent="download(scope.row.cert_file)"
        >
          Sertifikat
        </el-button>
        <el-button
          type="text"
          size="medium"
          icon="el-icon-download"
          style="color: blue"
          @click.prevent="download(scope.row.cv_file)"
        >
          CV
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
    noCertificate(no) {
      if (no === null || no === undefined || no === 'null') {
        return '-';
      } else {
        return no;
      }
    },
  },
};
</script>

<style scoped>
.expand-container {
  display: flex;
  justify-content: space-around;
}
.expand-container div {
  width: 50%;
  padding: 1rem 3rem;
}
.expand-container__right {
  text-align: right;
}
</style>
