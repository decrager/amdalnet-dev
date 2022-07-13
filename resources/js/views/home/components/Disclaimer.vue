<template>
  <el-dialog
    id="disclaimer"
    title="Pemberitahuan"
    :visible.sync="showDisclaimer"
    :show-close="false"
    :close-on-click-modal="false"
    :destroy-on-close="true"
    width="45%"
  >
    <p class="first-par"><strong>Amdalnet</strong> sudah dapat digunakan untuk pengajuan persetujuan lingkungan rencana kegiatan pemerintah dalam rangka mendukung pelaksanaan
      <strong>UU no 11 Tahun 2020 tentang Cipta Kerja</strong> dan <strong>PP Nomor 22 Tahun 2021 tentang Penyelenggaraan Perlindungan dan Pengelolaan Lingkungan Hidup</strong>.</p>
    <p>Khusus untuk rencana kegiatan pelaku usaha, saat ini masih dalam tahap integrasi dengan OSS-RBA.</p>
    <p>Terima kasih.</p>
    <!--
    <p>Dalam rangka mendukung pelaksanaan <strong>UU No 11 Tahun 2020 tentang Cipta Kerja</strong> dan <strong>PP Nomor 22 Tahun 2021 tentang Penyelenggaraan Perlindungan dan Pengelolaan Lingkungan Hidup</strong>,
    maka saat ini aplikasi <strong>Amdalnet</strong> sedang dalam <span style="text-decoration: underline;">tahap pengembangan</span>.</p>
    <p>Mohon maaf atas ketidaknyamanannya. Terima kasih.</p>
    -->
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
        margin: auto 0.5em;
        padding: 0 1.5em;
        word-break: break-word !important;
      }
      p.first-par {
        padding: 1.5em;
        background: #d5f6d5;
      }
      p:not(:first-child) {
        margin-top: 1em;
      }
    }
  }
}
</style>
