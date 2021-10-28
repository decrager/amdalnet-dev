<template>
  <div class="app-container">
    <aside align="center">
      TAHAPAN PRAKONSTRUKSI
    </aside>
    <aside align="center">
      TAHAPAN KONSTRUKSI
    </aside>
    <aside align="center">
      TAHAPAN OPERASI
    </aside>
    <aside align="center">
      TAHAPAN PASCAOPERASI
    </aside>
  </div>
</template>

<script>

const defaultForm = {
};

export default {
  name: 'SumberDampak',
  data() {
    return {
      postForm: Object.assign({}, defaultForm),
      dataLeft: [],
      dataRight: [],
    };
  },
  mounted() {
    this.dataLeft = [
      { label: 'Barcode', value: '1610922263CT2021' },
      { label: 'Jenis Dokumen', value: 'UKL-UPL' },
      { label: 'Nama Kegiatan', value: 'Kegiatan Pembangunan Gedung Jl Pramuka, Jakarta Timur' },
      { label: 'Bidang Usaha/Kegiatan', value: 'Bidang Multisektor' },
      { label: 'Skala/Besaran', value: 'Ha/m/m3/liter' },
      { label: 'Alamat', value: 'Jl. Ir. H. Djuanda No. 78 Bandung' },
      { label: 'Lokasi', value: 'Gedung Jl Pramuka, Jakarta Timur' },
      { label: 'Deskripsi Lokasi', value: 'Pembangunan Gedung Jl Pramuka, Jakarta Timur untuk memberikan manfaat yang besar bagi kemajuan ekonomi suatu daerah dengan terbukanya peluang usaha baru.' },
    ];
    this.dataRight = [
      { label: 'Pemrakarsa', value: 'PT. Wilujeng Sumping' },
      { label: 'Penanggung Jawab', value: 'Suyang Hartanto' },
      { label: 'Alamat Pemrakarsa', value: 'Jl. Ir. H. Djuanda No. 78 Bandung' },
      { label: 'No Telepon Pemrakarsa', value: '081223608250' },
      { label: 'Email Pemrakarsa', value: 'pemrakarsa001@gmail.com' },
      { label: 'Provinsi/Kota', value: 'Bandung' },
      { label: 'Kewenangan', value: 'Pusat' },
      { label: 'Deskripsi Kegiatan', value: 'Saat ini pemrakarsa telah memiliki izin lokasi yang dikeluarkan oleh Gubernur DKI Nomor xxx/123/345.0.0.1/20xx tanggal 01 Januari 2000 untuk keperluan pembangunan Gedung pada lahan seluas ± 2.000 m2. contoh' },
    ];
  },
  methods: {

  },
};
</script>
