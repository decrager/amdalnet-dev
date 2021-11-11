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
      <el-table-column label="Dampak Lingkungan yang Dipantau">
        <el-table-column label="Jenis Dampak yang Timbul">
          <template slot-scope="scope">
            <span
              :class="{
                'row-title': Boolean(scope.row.indikator == undefined),
              }"
            >
              {{ scope.row.jenisDampak }}
            </span>
          </template>
        </el-table-column>

        <el-table-column label="Indikator/Parameter">
          <template slot-scope="scope">
            <span>
              {{ scope.row.indikator ? scope.row.indikator : '' }}
            </span>
          </template>
        </el-table-column>

        <el-table-column label="Sumber Dampak">
          <template slot-scope="scope">
            <span>
              {{ scope.row.sumberDampak ? scope.row.sumberDampak : '' }}
            </span>
          </template>
        </el-table-column>
      </el-table-column>

      <el-table-column label="Bentuk Pemantauan Lingkungan Hidup">
        <el-table-column label="Metode Pengumpulan & Analisis Data">
          <template slot-scope="scope">
            <span>
              {{
                scope.row.metodePengumpulan ? scope.row.metodePengumpulan : ''
              }}
            </span>
          </template>
        </el-table-column>

        <el-table-column label="Lokasi Pantau">
          <template slot-scope="scope">
            <span>
              {{ scope.row.lokasiPantau ? scope.row.lokasiPantau : '' }}
            </span>
          </template>
        </el-table-column>

        <el-table-column label="Waktu & Frekuensi">
          <template slot-scope="scope">
            <span>
              {{ scope.row.waktuNFrekuensi ? scope.row.waktuNFrekuensi : '' }}
            </span>
          </template>
        </el-table-column>
      </el-table-column>

      <el-table-column label="Institusi Pemantauan Lingkungan Hidup">
        <el-table-column label="Pelaksana">
          <template slot-scope="scope">
            <span>
              {{ scope.row.pelaksan ? scope.row.pelaksana : '' }}
            </span>
          </template>
        </el-table-column>

        <el-table-column label="Pengawas">
          <template slot-scope="scope">
            <span>
              {{ scope.row.pengawas ? scope.row.pengawas : '' }}
            </span>
          </template>
        </el-table-column>

        <el-table-column label="Penerima Laporan">
          <template slot-scope="scope">
            <span>
              {{ scope.row.penerimaLaporan ? scope.row.penerimaLaporan : '' }}
            </span>
          </template>
        </el-table-column>
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
          jenisDampak: 'Pra Konstruksi',
        },
        {
          jenisDampak: 'Penurunan Kualitas Udara akibat Pengadaan Lahan',
          indikator: '',
          sumberDampak: '',
          metodePengumpulan: '',
          lokasiPantau: '',
          waktuNFrekuensi: '',
          pelaksana: '',
          pengawas: '',
          penerimaLaporan: '',
          keterangan: '',
        },
        {
          jenisDampak: 'Konstruksi',
        },
        {
          jenisDampak:
            'Penurunan Mata Pencaharian Penduduk akibat Mobilisasi Peralatan dan Material Konstruksi',
          indikator: '',
          sumberDampak: '',
          metodePengumpulan: '',
          lokasiPantau: '',
          waktuNFrekuensi: '',
          pelaksana: '',
          pengawas: '',
          penerimaLaporan: '',
          keterangan: '',
        },
        {
          jenisDampak: 'Operasi',
        },
        {
          jenisDampak:
            'Penurunan Keberadaan Biota Air akibat Pengoperasian Kawasan Industri dan Sarana Pendukungnya',
          indikator: '',
          sumberDampak: '',
          metodePengumpulan: '',
          lokasiPantau: '',
          waktuNFrekuensi: '',
          pelaksana: '',
          pengawas: '',
          penerimaLaporan: '',
          keterangan: '',
        },
        {
          jenisDampak:
            'Penurunan Kualitas Air akibat Pengoperasian Kawasan Industri dan Sarana Pendukungnya',
          indikator: '',
          sumberDampak: '',
          metodePengumpulan: '',
          lokasiPantau: '',
          waktuNFrekuensi: '',
          pelaksana: '',
          pengawas: '',
          penerimaLaporan: '',
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
