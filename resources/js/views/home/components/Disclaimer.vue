<template>
  <el-dialog
    id="disclaimer"
    title="Disclaimer"
    :visible.sync="showDisclaimer"
    :close-on-click-modal="false"
    :destroy-on-close="true"
    width="45%"
  >

    <p>Dalam rangka mendukung pelaksanaan <strong>UU No 11 Tahun 2020 tentang Cipta Kerja</strong> dan <strong>PP Nomor 22 Tahun 2021 tentang Penyelenggaraan Perlindungan dan Pengelolaan Lingkungan Hidup</strong>, maka saat ini aplikasi <strong>Amdalnet</strong> sedang dalam <span style="text-decoration: underline;">tahap pengembangan dan uji coba</span>.</p>
    <p>Mohon maaf atas ketidaknyamanannya. Terima kasih.</p>
    <span slot="footer" class="dialog-footer">
      <el-button type="primary" @click="handleClose">Tutup</el-button>
    </span>
  </el-dialog>
</template>
<script>
export default {
  name: 'Disclaimer',
  data() {
    return {
      showDisclaimer: true,
    };
  },
  mounted() {
    console.log('disclaimer!');
  },
  methods: {
    handleClose(){
      this.showDisclaimer = false;
      console.log('disclaimer is closing');
      this.$emit('closeDialog', true);
    },
  },
};
</script>
<style lang="scss">
#disclaimer {
  .el-dialog {

    div.el-dialog__header{
      span.el-dialog__title {
        font-weight: 900 !important;
      }
    }
    div.el-dialog__body{
      p {
        color: #303030;
        font-size: 130%;
        line-height: 1.8em ;
        margin: auto 1.5em;
        word-break: break-word !important;
      }
      p:not(:first-child) {
        margin-top: 1em;
      }
    }
  }
}
</style>
