<template>
  <div v-if="isShow" v-loading="loading">
    <div
      v-if="statusShow === 'submit-to-pemrakarsa'"
      style="margin-bottom: 8px"
    >
      <label v-if="notesShow">Pesan dari Penyusun:</label>
      <div v-html="notesShow" />
    </div>
    <div style="margin-bottom: 8px">
      <el-radio v-model="status" label="submit">Submit</el-radio>
      <el-radio v-model="status" label="revisi">Revisi</el-radio>
    </div>
    <div v-if="status === 'submit'">
      <p>Unggah Surat Permohonan<span style="color: red">*</span></p>
      <el-upload
        action="#"
        :auto-upload="false"
        :on-change="handleUpload"
        :show-file-list="false"
      >
        <el-button size="small" type="warning"> Upload </el-button>
        <div slot="tip" class="el-upload__tip">
          {{ fileName }}
        </div>
      </el-upload>
      <span v-if="errors.file" style="color: red">{{ errors.file }}</span>
    </div>
    <div v-if="status === 'revisi'">
      <p style="margin-bottom: 0px; padding-bottom: 0px">
        Catatan<span style="color: red">*</span>
      </p>
      <Tinymce v-model="notes" />
      <span v-if="errors.notes" style="color: red">{{ errors.notes }}</span>
    </div>
    <div
      v-if="status === 'revisi' || status === 'submit'"
      style="text-align: right; margin-top: 8px"
    >
      <el-button :loading="loadingSubmit" type="primary" @click="checkSubmit">
        Kirim
      </el-button>
    </div>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';
import Resource from '@/api/resource';
const kaReviewsResource = new Resource('ka-reviews');

export default {
  name: 'KaReviewPemrakarsa',
  components: {
    Tinymce,
  },
  data() {
    return {
      status: null,
      statusShow: null,
      file: null,
      fileName: null,
      notes: null,
      notesShow: null,
      loading: false,
      loadingSubmit: false,
      errors: {},
    };
  },
  computed: {
    isShow() {
      if (this.statusShow === null) {
        return false;
      }

      return !(this.statusShow === 'revisi' || this.statusShow === 'submit');
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
      });
      if (data) {
        this.statusShow = data.status;
        this.notesShow = data.notes;
      }
      this.loading = false;
    },
    handleUpload(file, fileList) {
      if (file.raw.size > 1048576) {
        this.showFileAlert();
        return;
      }

      this.file = file.raw;
      this.fileName = file.name;
    },
    checkSubmit() {
      this.errors = {};

      if (this.status === 'revisi') {
        if (!this.notes) {
          this.errors.notes = 'Catatan Wajib Diisi';
          return;
        }
      } else if (this.status === 'submit') {
        if (!this.file) {
          this.errors.file = 'Surat Permohonan Wajib Diunggah';
          return;
        }
      }

      if (this.status === 'revisi') {
        this.handleRevision();
      } else if (this.status === 'submit') {
        this.handleSubmit();
      }
    },
    async handleRevision() {
      this.loadingSubmit = true;
      await kaReviewsResource.store({
        type: 'pemrakarsa',
        idProject: this.$route.params.id,
        notes: this.notes,
        status: 'revisi',
      });
      this.$message({
        message: 'Data berhasil disimpan',
        type: 'success',
        duration: 5 * 1000,
      });
      this.getData();
      this.loadingSubmit = false;
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      const formData = new FormData();
      formData.append('type', 'pemrakarsa');
      formData.append('idProject', this.$route.params.id);
      formData.append('status', 'submit');
      formData.append('file', this.file);
      await kaReviewsResource.store(formData);
      this.$message({
        message: 'Data berhasil disimpan',
        type: 'success',
        duration: 5 * 1000,
      });
      this.getData();
      this.loadingSubmit = false;
    },
    showFileAlert() {
      this.$alert('Ukuran file tidak boleh lebih dari 1 MB', '', {
        center: true,
      });
    },
  },
};
</script>
