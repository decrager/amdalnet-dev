<template>
  <div>
    <p style="text-align: center; font-weight:bold;">Tabel Komponen Lingkungan</p>
    <table>
      <thead>
        <tr>
          <th>No.</th>
          <th>Rona Lingkungan</th>
          <th>Komponen Lingkungan</th>
          <th>Deskripsi Umum</th>
        </tr>
      </thead>
    </table>
    <el-button icon="el-icon-plus" circle style="margin-top:3em;display:block;" @click="kLDialogueVisible = true" />

    <el-dialog
      title="Tambah Komponen Lingkungan"
      :visible.sync="kLDialogueVisible"
      width="40%"
      :before-close="handleClose"
    >
      <el-form label-position="top" :model="tambahKK">
        <el-form-item label="Komponen Kegiatan">
          <el-input v-model="tambahKL.tahap" />
        </el-form-item>
        <el-form-item label="Deskripsi">
          <el-input v-model="tambahKL.KUP" />
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button type="info" @click="handleClose">Batal</el-button>
        <el-button type="primary" @click="addKL">Simpan</el-button>
      </span>
    </el-dialog>
  </div>
</template>
<script>
export default {
  name: 'KomponenLingkungan',
  data() {
    return {
      kLDialogueVisible: false,
      tambahKL: [],
    };
  },
  methods: {
    handleClose() {
      this.kLDialogueVisible = false;
    },
    addKL() {},

  },
};
</script>
