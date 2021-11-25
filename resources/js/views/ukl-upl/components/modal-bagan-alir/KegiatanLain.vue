<template>
  <div class="modal__container">
    <el-dialog
      title="Kegiatan Lain"
      :close-on-click-modal="false"
      :visible.sync="openKegiatanLain"
      :modal-append-to-body="false"
      width="20%"
    >
      <ul v-for="kegiatan in selectedKegiatanLain" :key="kegiatan.id">
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
  name: 'KegiatanLain',
  props: {
    selectedKegiatanLain: {
      type: Object,
      default: () => {
      },
    },
    openKegiatanLain: Boolean,
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
