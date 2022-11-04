<template>
  <el-table
    v-loading="loading"
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column type="expand">
      <template slot-scope="scope">
        <div class="expand-container">
          <div>
            <!-- <p>
              <b>Provinsi : </b>
              {{ scope.row.data_province ? scope.row.data_province.name : '' }}
            </p> -->
            <!-- <p>
              <b>Kota : </b>
              {{ scope.row.data_district ? scope.row.data_district.name : '' }}
            </p> -->
            <p>
              <b>Nomor Telepon : </b>
              {{ scope.row.phone_no ? scope.row.phone_no : '' }}
            </p>
            <p><b>Email : </b>{{ scope.row.email ? scope.row.email : '' }}</p>
            <!-- <p><b>Tanggal Ditetapkan : </b>{{ formatDate(scope.row.date_lsp_start) }}</p> -->
            <!-- <p><b>Tanggal Terakhir Berlaku : </b>{{ formatDate(scope.row.date_lsp_end) }}</p> -->
          </div>
          <div
            v-if="checkPermission(['manage lsp']) || checkRole(['admin'])"
            class="expand-container__right"
          >
            <el-button
              type="warning"
              size="medium"
              @click="handleEditForm(scope.row.id)"
            >
              Ubah
            </el-button>
            <el-button
              type="danger"
              size="medium"
              @click="handleDelete(scope.row.id, scope.row.name)"
            >
              Hapus
            </el-button>
            <el-button
              type="primary"
              size="medium"
              @click="handleKelolaLSP(scope.row.id)"
            >
              Lihat Penyusun
            </el-button>
          </div>
        </div>
      </template>
    </el-table-column>
    <el-table-column label="No." width="54px" align="left">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 + page * limit - limit }}</span>
      </template>
    </el-table-column>
    <!-- <el-table-column
      align="center"
      label="Nomor Lisensi"
      sortable
      prop="license_no"
    /> -->
    <el-table-column
      align="center"
      label="Nama LSP"
      sortable
      prop="lsp_name"
    />
    <el-table-column align="center" label="Alamat" sortable prop="address" />

    <!-- <el-table-column align="center" label="Bukti Penetapan">
      <template slot-scope="scope">
        <el-button
          type="text"
          size="medium"
          icon="el-icon-download"
          style="color: blue"
          @click.prevent="download(scope.row.lsp_file)"
        >
          Bukti Penetapan
        </el-button>
      </template>
    </el-table-column> -->

    <!-- <el-table-column align="center" label="Status">
      <template slot-scope="scope">
        <el-tag
          :type="
            calculateStatus(scope.row.date_lsp_start, scope.row.date_lsp_end)
              | statusFilter
          "
        >
          {{ calculateStatus(scope.row.date_lsp_start, scope.row.date_lsp_end) }}
        </el-tag>
      </template>
    </el-table-column> -->
  </el-table>
</template>

<script>
import checkPermission from '@/utils/permission';
import checkRole from '@/utils/role';
import moment from 'moment';

export default {
  name: 'LspTable',
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
    page: {
      type: Number,
      default: () => 1,
    },
    limit: {
      type: Number,
      default: () => 10,
    },
  },
  methods: {
    checkPermission,
    checkRole,
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
    handleKelolaLSP(id) {
      // eslint-disable-next-line object-curly-spacing
      this.$router.push({ name: 'lspFormulatorMember', params: { id } });
    },
    formatDate(date) {
      return moment(date).format('DD-MM-YYYY');
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
