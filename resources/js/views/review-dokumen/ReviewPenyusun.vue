<template>
  <div v-loading="loading">
    <div v-if="status === 'revisi'">
      <p>Catatan dari pemrakarsa:</p>
      <div v-html="notesShow" />
    </div>
    <div v-if="isShowNotes">
      <div
        style="
          display: flex;
          justify-content: space-between;
          align-items: center;
        "
      >
        <p>Pesan:</p>
        <el-button :loading="loadingSubmit" type="primary" @click="checkSubmit">
          Kirim
        </el-button>
      </div>
      <Tinymce v-model="notes" />
    </div>
    <div v-else>
      <p>Pesan:</p>
      <div v-html="formulatorNotes" />
    </div>
    <el-alert
      v-if="!isShowNotes"
      :title="`${formulirOrDokumen} ${documenttype} telah Dikirim ke Pemrakarsa`"
      type="success"
      description="Terimakasih"
      show-icon
      center
      :closable="false"
    />
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';
import Resource from '@/api/resource';
const kaReviewsResource = new Resource('ka-reviews');

export default {
  name: 'ReviewPenyusun',
  components: {
    Tinymce,
  },
  props: {
    documenttype: {
      type: String,
      default: () => '',
    },
  },
  data() {
    return {
      notes: null,
      status: null,
      notesShow: null,
      loading: false,
      loadingSubmit: false,
      formulatorNotes: null,
      success: false,
    };
  },
  computed: {
    isShow() {
      return this.status === null || this.status === 'revisi';
    },
    isShowNotes() {
      if (this.status === null || this.status === 'revisi') {
        return true;
      }

      return false;
    },
    getDocumentType() {
      if (this.documenttype === 'Kerangka Acuan') {
        return 'ka';
      } else if (this.documenttype === 'ANDAL RKL RPL') {
        return 'andal-rkl-rpl';
      } else if (this.documenttype === 'UKL UPL') {
        return 'ukl-upl';
      }

      return '';
    },
    formulirOrDokumen() {
      if (
        this.documenttype === 'Kerangka Acuan' ||
        this.documenttype === 'UKL UPL'
      ) {
        return 'Formulir';
      } else {
        return 'Dokumen';
      }
    },
  },
  created() {
    this.getData();
  },
  methods: {
    async getData() {
      this.loading = true;
      const data = await kaReviewsResource.list({
        idProject: this.$route.params.id,
        documentType: this.getDocumentType,
      });
      if (data) {
        this.status = data.status;
        this.notesShow = data.notes;
        this.formulatorNotes = data.formulator_notes;
      }
      this.loading = false;
    },
    checkSubmit() {
      this.$confirm('Apakah anda yakin ? Data yang sudah disimpan, tidak dapat diubah lagi.', 'Warning', {
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak',
        type: 'warning',
      })
        .then(() => {
          this.handleSubmit();
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: 'Kirim data dibatalkan',
          });
        });
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      await kaReviewsResource.store({
        idProject: this.$route.params.id,
        notes: this.notes,
        type: 'penyusun',
        documentType: this.getDocumentType,
      });
      this.notes = null;
      this.getData();
      this.loadingSubmit = false;
    },
  },
};
</script>
