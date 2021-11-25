<template>
  <div class="modal__container">
    <el-dialog
      title="Rencana Kegiatan"
      :close-on-click-modal="false"
      :visible.sync="show"
    >
      <span>This is a message</span>
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
    show: Boolean,
  },
  mounted() {
    console.log('show: ' + this.selectedRencanaKegiatan);
  },
};
</script>

<style scoped>
.modal__container {
    background-color: aqua;
}
</style>
