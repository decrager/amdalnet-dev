<template>
  <div v-loading="loading">
    <div v-if="status === 'revisi'">
      <p>Catatan dari pemrakarsa:</p>
      <div v-html="notesShow" />
    </div>
    <div v-if="isShowNotes">
      <p>Pesan:</p>
      <Tinymce v-model="notes" />
      <div style="text-align: right; margin-top: 8px">
        <el-button
          :loading="loadingSubmit"
          type="primary"
          @click="handleSubmit"
        >
          Kirim
        </el-button>
      </div>
    </div>
    <div v-else>
      <p>Pesan:</p>
      <div v-html="formulatorNotes" />
    </div>
    <el-alert
      v-if="!isShowNotes"
      :title="`Formulir ${documenttype} telah Dikirim ke Pemrakarsa`"
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
      } else if (this.documenttype === 'ANDAL') {
        return 'andal';
      } else if (this.documenttype === 'RKL RPL') {
        return 'rkl-rpl';
      } else if (this.documenttype === 'UKL UPL') {
        return 'ukl-upl';
      }

      return '';
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
