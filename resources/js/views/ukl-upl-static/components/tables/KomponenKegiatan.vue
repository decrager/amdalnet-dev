<template>
  <div>
    <p style="text-align: center; font-weight:bold;">Tabel Komponen Kegiatan</p>
    <table style="width:100%;">
      <thead>
        <tr>
          <th>No.</th>
          <th style="width:40%;">Komponen Kegiatan</th>
          <th style="width:50%;">Deskripsi Umum</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="(kk, idx) in data">
          <tr :key="'kk_' + idx">
            <td>{{ idx + 1 }}</td>
            <td>{{ kk.kk }}</td>
            <td>{{ kk.description }}</td>
          </tr>
        </template>
      </tbody>
    </table>
    <el-button icon="el-icon-plus" circle style="margin-top:3em;display:block;" @click="kKDialogueVisible = true" />
    <el-dialog
      title="Tambah Komponen Kegiatan"
      :visible.sync="kKDialogueVisible"
      width="40%"
      :before-close="handleClose"
    >
      <el-form label-position="top" :model="tambahKK">
        <el-form-item label="Komponen Kegiatan">
          <el-input v-model="tambahKK.tahap" />
        </el-form-item>
        <el-form-item label="Deskripsi">
          <el-input v-model="tambahKK.KUP" />
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button type="info" @click="handleClose">Batal</el-button>
        <el-button type="primary" @click="addKK">Simpan</el-button>
      </span>
    </el-dialog>

  </div>
</template>
<script>

export default {
  name: 'KomponenKegiatan',
  data() {
    return {
      kKDialogueVisible: false,
      data: [
        { kk: 'Pembangunan Pabrik', description: '' },
      ],
      tambahKK: [],
    };
  },
  methods: {
    addKK() { },
    handleClose() {
      this.kKDialogueVisible = false;
    },
  },
};
</script>
<style>

</style>
