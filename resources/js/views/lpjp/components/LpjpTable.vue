<template>
  <el-table
    v-loading="loading"
    :data="list"
    border
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column align="center" label="No. Registrasi">
      <template slot-scope="scope">
        <span>{{ scope.row.reg_no }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Nama Perusahaan">
      <template slot-scope="scope">
        <span>{{ scope.row.name }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Alamat">
      <template slot-scope="scope">
        <span>{{ scope.row.address }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Provinsi">
      <template slot-scope="scope">
        <span>{{ scope.row.id_prov }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Kota">
      <template slot-scope="scope">
        <span>{{ scope.row.id_district }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Email">
      <template slot-scope="scope">
        <span>{{ scope.row.email }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Tgl Awal">
      <template slot-scope="scope">
        <span>{{ scope.row.date_start }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Tgl Akhir">
      <template slot-scope="scope">
        <span>{{ scope.row.date_end }}</span>
      </template>
    </el-table-column>

    <el-table-column align="center" label="Sertifikat">
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
        <!-- <div>
          <i class="el-icon-download" />
          <span>{{ 'Sertifikat' }}</span>
        </div> -->
      </template>
    </el-table-column>

    <el-table-column align="center" label="Status">
      <template slot-scope="scope">
        <el-tag :type="calculateStatus(scope.row.date_start, scope.row.date_end) | statusFilter">
          {{ calculateStatus(scope.row.date_start, scope.row.date_end) }}
        </el-tag>
        <!-- <span>{{
          calculateStatus(scope.row.tgl_awal, scope.row.tgl_akhir)
        }}</span> -->
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
  name: 'LpjpTable',
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
