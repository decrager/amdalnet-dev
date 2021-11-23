<template>
  <el-dialog
    title="Tambah Komponen Lingkungan"
    :visible.sync="show"
    width="40%"
    :before-close="closeFAddKL"
  >

    <el-form label-position="top" :model="ronaAwal">
      <el-form-item label="Tahap Kegiatan">
        <el-input v-model="ronaAwal.tahap" />
      </el-form-item>
      <el-form-item label="Kegiatan Utama/Pendukung">
        <el-input v-model="ronaAwal.KUP" />
      </el-form-item>
      <el-form-item label="Komponen Kegiatan">
        <el-input v-model="ronaAwal.KK" />
      </el-form-item>
      <el-form-item label="Rona Lingkungan">
        <el-input v-model="ronaAwal.RL" />
      </el-form-item>
      <el-form-item label="Definisi">
        <el-input v-model="ronaAwal.definisi" />
      </el-form-item>
      <el-form-item label="Umum">
        <el-input v-model="ronaAwal.umum" />
      </el-form-item>
      <el-form-item label="Khusus">
        <el-input v-model="ronaAwal.khusus" />
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button type="info" @click="handleClose">Batal</el-button>
      <el-button type="primary" @click="addKL">Simpan</el-button>
    </span>
  </el-dialog>
</template>
<script>
export default {
  name: 'AddRonaAwalDialog',
  props: {
    show: Boolean,
  },
  data() {
    return {
      ronaAwal: {},
    };
  },
  methods: {
    handleClose() {
      this.$emit('handleCloseAddRonaAwal');
    },
  },
};
</script>

