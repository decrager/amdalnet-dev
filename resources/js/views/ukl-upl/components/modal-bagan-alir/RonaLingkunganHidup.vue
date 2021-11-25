<template>
  <div class="modal__container">
    <el-dialog
      title="Rona Lingkungan Hidup"
      :close-on-click-modal="false"
      :visible.sync="openRona"
      :modal-append-to-body="false"
      width="20%"
    >
      <ul v-for="kegiatan in selectedRona" :key="kegiatan.id">
        <li>{{ kegiatan.name }}</li>
      </ul>
      <el-button type="danger" class="button-cancel" style="width:100%;" @click="closeDialog()">
        Tutup
      </el-button>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: 'RonaLingkunganHidup',
  props: {
    selectedRona: {
      type: Object,
      default: () => {
      },
    },
    openRona: Boolean,
  },
  methods: {
    closeDialog() {
      this.openRencanaKegiatan = false;
    },
  },
};
</script>

<style scoped>
</style>
