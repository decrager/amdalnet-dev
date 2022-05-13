<template>
  <el-dialog
    :title="'Lihat Progres Dokumen Lingkungan'"
    :visible.sync="show"
    :before-close="handleClose"
  >
    <div class="form-container">
      <el-form
        ref="form"
        :model="form"
        label-position="top"
        label-width="100%"
        :rules="rules"
      >
        <el-form-item label="Masukkan Nomor Registrasi Dokumen Lingkungan" prop="registrationNo">
          <el-input v-model="form.registrationNo" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button type="primary" @click="handleSubmit"> Track </el-button>
      </div>
    </div>
  </el-dialog>
</template>

<script>
import Resource from '@/api/resource';
const projectResource = new Resource('projects');

export default {
  name: 'TrackingDocumentDialog',
  props: {
    show: Boolean,
  },
  data() {
    return {
      form: {},
      rules: {
        registrationNo: [
          { required: true, message: 'Mohon Masukan Nomor Registrasi', trigger: 'blur' },
        ],
      },
    };
  },
  methods: {
    isValidRegistrationNo(registration_no){
      return registration_no !== undefined &&
        registration_no !== null &&
        registration_no.replace(/\s+/g, '').trim() !== '';
    },
    async handleSubmit() {
      const { data } = await projectResource.list({
        registration_no: this.form.registrationNo,
      });
      this.$refs['form'].validate((valid) => {
        if (valid && data.length > 0) {
          if (this.isValidRegistrationNo(data[0].registration_no)) {
            this.$emit('showTrackingDocumentDetail', data[0]);
          } else {
            this.$message({
              message: 'Tidak ditemukan kegiatan dengan nomor registrasi ' + this.form.registrationNo,
              type: 'error',
              duration: 5 * 1000,
            });
          }
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    async handleClose() {
      this.$emit('handleClose');
      this.form = {};
    },
  },
};
</script>

<style>
</style>
