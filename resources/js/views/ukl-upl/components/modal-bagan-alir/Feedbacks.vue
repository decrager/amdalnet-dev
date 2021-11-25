<template>
  <div class="modal__container">
    <el-dialog
      title="Saran Tanggapan dan Pendapat Masyarakat"
      :close-on-click-modal="false"
      :visible.sync="openFeedback"
      :modal-append-to-body="false"
      width="20%"
    >
      <ul v-for="feedback in selectedFeedback" :key="feedback.id">
        <span>Kekhawatiran</span>
        <li>{{ feedback.convern }}</li>
        <span>Harapan</span>
        <li>{{ feedback.expectation }}</li>
      </ul>
      <el-button type="danger" class="button-cancel" style="width:100%;" @click="closeDialog()">
        Tutup
      </el-button>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: 'Feedbacks',
  props: {
    selectedFeedback: {
      type: Object,
      default: () => {
      },
    },
    openFeedback: Boolean,
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
