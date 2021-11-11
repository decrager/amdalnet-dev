<template>
  <div>
    <div class="filter-container">
      <el-button class="filter-item" type="primary" style="font-size: 0.8rem">
        {{ 'Simpan Perubahan' }}
      </el-button>
      <span style="font-size: 0.8rem">
        <em>Terakhir diperbarui beberapa detik yang lalu</em>
      </span>
    </div>
    <el-table
      v-loading="loading"
      :data="list"
      fit
      border
      highlight-current-row
      :header-cell-style="{
        background: '#cdf4b5',
        color: 'black',
        textAlign: 'center',
        border: '0px',
      }"
      style="font-size: 12px"
    >
      <el-table-column label="Dampak Lingkungan yang Dikelola">
        <template slot-scope="scope">
          <span
            :class="{
              'row-title': Boolean(scope.row.sumberDampak == undefined),
            }"
          >
            {{ scope.row.dampakLingkungan }}
          </span>
        </template>
      </el-table-column>

      <el-table-column label="Sumber Dampak">
        <template slot-scope="scope">
          <span>{{
            scope.row.sumberDampak ? scope.row.sumberDampak : ''
          }}</span>
        </template>
      </el-table-column>

      <el-table-column
        label="Indikator Keberhasilan Pengelolaan Lingkungan Hidup"
      >
        <template slot-scope="scope">
          <span>{{
            scope.row.indikatorKeberhasilan
              ? scope.row.indikatorKeberhasilan
              : ''
          }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Pengelolaan Lingkungan Hidup">
        <el-table-column label="Bentuk Pengelolaan Lingkungan Hidup">
          <template slot-scope="scope">
            <span>{{
              scope.row.bentukPengelolaan ? scope.row.bentukPengelolaan : ''
            }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Lokasi Pengelolaan Lingkungan Hidup">
          <template slot-scope="scope">
            <span>{{
              scope.row.lokasiPengelolaan ? scope.row.lokasiPengelolaan : ''
            }}</span>
          </template>
        </el-table-column>

        <el-table-column label="Periode Pengelolaan Lingkungan Hidup">
          <template slot-scope="scope">
            <span>{{
              scope.row.periodePengelolaan ? scope.row.periodePengelolaan : ''
            }}</span>
          </template>
        </el-table-column>
      </el-table-column>

      <el-table-column label="Institusi Pengelolaan Lingkungan Hidup">
        <template slot-scope="scope">
          <span>{{
            scope.row.institusiPengelolaan ? scope.row.institusiPengelolaan : ''
          }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Keterangan">
        <template slot-scope="scope">
          <span>{{ scope.row.keterangan ? scope.row.keterangan : '' }}</span>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
export default {
  name: 'TableRKL',
  data() {
    return {
      loading: false,
      list: [
        {
          dampakLingkungan: 'Pra Konstruksi',
        },
        {
          dampakLingkungan: 'Penurunan Kualitas Udara akibat Pengadaan Lahan',
          sumberDampak: '',
          indikatorKeberhasilan: '',
          bentukPengelolaan: '',
          lokasiPengelolaan: '',
          periodePengelolaan: '',
          institusiPengelolaan: '',
          keterangan: '',
        },
        {
          dampakLingkungan: 'Konstruksi',
        },
        {
          dampakLingkungan:
            'Penurunan Mata Pencaharian Penduduk akibat Mobilisasi Peralatan dan Material Konstruksi',
          sumberDampak: '',
          indikatorKeberhasilan: '',
          bentukPengelolaan: '',
          lokasiPengelolaan: '',
          periodePengelolaan: '',
          institusiPengelolaan: '',
          keterangan: '',
        },
        {
          dampakLingkungan: 'Operasi',
        },
        {
          dampakLingkungan:
            'Penurunan Keberadaan Biota Air akibat Pengoperasian Kawasan Industri dan Sarana Pendukungnya',
          sumberDampak: '',
          indikatorKeberhasilan: '',
          bentukPengelolaan: '',
          lokasiPengelolaan: '',
          periodePengelolaan: '',
          institusiPengelolaan: '',
          keterangan: '',
        },
        {
          dampakLingkungan:
            'Penurunan Kualitas Air akibat Pengoperasian Kawasan Industri dan Sarana Pendukungnya',
          sumberDampak: '',
          indikatorKeberhasilan: '',
          bentukPengelolaan: '',
          lokasiPengelolaan: '',
          periodePengelolaan: '',
          institusiPengelolaan: '',
          keterangan: '',
        },
      ],
    };
  },
  methods: {
    handleEditForm(id) {
      this.$emit('handleEditForm', id);
    },
    handleDelete(id, nama) {
      this.$emit('handleDelete', { id, nama });
    },
  },
};
</script>

<style scoped>
.row-title {
  font-weight: bold;
}
</style>
