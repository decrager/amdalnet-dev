<template>
  <div>
    <el-button
      type="success"
      size="small"
      icon="el-icon-check"
      @click="handleSaveForm()"
    > Simpan Perubahan </el-button>

    <table style="width:100%;">
      <thead>
        <tr>
          <th rowspan="2">No.</th>
          <th rowspan="2">Sumber Dampak</th>
          <th rowspan="2">Jenis Dampak</th>
          <th rowspan="2">Besaran Dampak</th>
          <th colspan="3">Standar Pengelolaan Lingkungan Hidup</th>
          <th rowspan="2">Institusi Pengelolaan Lingkungan Hidup</th>
          <th rowspan="2">Keterangan</th>
        </tr>
        <tr>
          <th>Bentuk</th>
          <th>Lokasi</th>
          <th>Periode</th>
        </tr>
      </thead>
      <tbody />
    </table>

  </div>
</template>
<script>
export default {
  name: 'MatriksUklUpl',
  methods: {
    handleSaveForm(){
      return;
    },
  },
};
</script>
