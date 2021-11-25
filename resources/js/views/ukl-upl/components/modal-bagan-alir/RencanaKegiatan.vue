<template>
  <div class="modal__container">
    <el-dialog
      title="Rencana Kegiatan"
      :close-on-click-modal="false"
      :visible.sync="openRencanaKegiatan"
      :modal-append-to-body="false"
      :show-close="false"
      width="20%"
    >
      <ul v-for="rencana in selectedRencanaKegiatan" :key="rencana.id">
        <li>{{ rencana.name }}</li>
      </ul>
      <el-button type="danger" class="button-cancel" style="width:100%;" @click="closeDialog()">
        Tutup
      </el-button>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: 'RencanaKegiatan',
  props: {
    selectedRencanaKegiatan: {
      type: Object,
      default: () => {
      },
    },
    openRencanaKegiatan: Boolean,
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
