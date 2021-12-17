<template>
  <div>
    <el-button
      type="success"
      size="small"
      icon="el-icon-check"
      @click="handleSaveForm()"
    > Simpan Perubahan </el-button>

    <el-date-picker
      v-model="tanggal_rapat"
      type="date"
      placeholder="Tanggal Rapat"
    />

    <el-time-select
      v-model="waktu_rapat"
      :picker-options="{
        start: '06:30',
        step: '00:15',
        end: '22:30'
      }"
      placeholder="Waktu Rapat"
    >

      <el-input v-model="tempat_rapat" placeholder="Tempat Rapat" />

    </el-time-select>
  </div>
</template>
<script>
export default {
  name: 'FormBeritaAcara',
  data() {
    return {
      tanggal_rapat: '',
      waktu_rapat: '',
      tempat_rapat: '',

    };
  },
  methods: {
    handleSaveForm() {

    },
  },
};
</script>
