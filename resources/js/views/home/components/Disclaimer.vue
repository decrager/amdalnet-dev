<template>
  <el-dialog
    v-if="mobile === false"
    id="disclaimer"
    title="Pemberitahuan"
    :visible.sync="showDisclaimer"
    :show-close="false"
    :close-on-click-modal="false"
    :destroy-on-close="true"
    width="45%"
  >

    <p class="first-par">Dalam rangka mendukung pelaksanaan UU Nomor 11 Tahun 2020 tentang Cipta Kerja dan PP Nomor 22 Tahun 2021 tentang Penyelenggaraan Perlindungan dan Pengelolaan Lingkungan Hidup, Amdalnet sudah dapat digunakan untuk pengajuan persetujuan lingkungan mulai dari Penapisan Otomatis Jenis Dokumen Lingkungan, Asistensi Pelingkupan, dan Amdal Digital Workspace.</p>
    <p>Integrasi Amdal dengan OSS-RBA telah dilakukan untuk rencana usaha dan/atau kegiatan tingkat resiko Menengah Rendah dan Rendah, sedangkan bagi rencana usaha dan/atau kegiatan baru dengan pendekatan tunggal/terpadu untuk tingkat resiko Menengah Tinggi dan Tinggi dapat diproses dengan registrasi secara mandiri melalui Amdalnet setelah mendapatkan NIB dari OSS.</p>
    <p>Terima kasih.</p>
    <!--
    <p>Dalam rangka mendukung pelaksanaanUU No 11 Tahun 2020 tentang Cipta Kerja</strong> dan <strong>PP Nomor 22 Tahun 2021 tentang Penyelenggaraan Perlindungan dan Pengelolaan Lingkungan Hidup</strong>,
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
      mobile: false,
    };
  },
  mounted() {
    console.log('disclaimer!');
    if (window.innerWidth <= 400) {
      this.mobile = true;
    }
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
