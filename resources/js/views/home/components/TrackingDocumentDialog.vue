<template>
  <el-dialog
    :title="'Lacak Pengajuan Persetujuan Lingkungan'"
    :visible.sync="show"
    :before-close="handleClose"
  >
    <div v-loading="loading" class="form-container">
      <el-form
        ref="form"
        :model="form"
        label-position="top"
        label-width="100%"
        :rules="rules"
      >
        <el-form-item label="Nomor Registrasi Dokumen Lingkungan" prop="registrationNo">
          <el-input v-model="form.registrationNo" />
        </el-form-item>
      </el-form>
    </div>
    <div slot="footer" class="dialog-footer">
      <el-button type="primary" :disabled="loading" @click="handleSubmit"> Lacak </el-button>
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
      loading: false,
    };
  },
  methods: {
    isValidRegistrationNo(registration_no){
      return registration_no !== undefined &&
        registration_no !== null &&
        registration_no.replace(/\s+/g, '').trim() !== '';
    },
    async getProject(){
      this.loading = true;
      await projectResource.list({ registration_no: this.form.registrationNo }).then((res) => {
        if (res.data.length > 0) {
          this.$emit('showTrackingDocumentDetail', res.data[0]);
          // this.handleClose();
        } else {
          this.$message({
            message: 'Tidak ditemukan kegiatan dengan nomor registrasi ' + this.form.registrationNo,
            type: 'error',
            duration: 5 * 1000,
          });
        }
      }).finally(() => {
        this.loading = false;
      });
    },

    handleSubmit() {
      // '';
      /* const { data } = await projectResource.list({
        registration_no: this.form.registrationNo,
      }); */

      this.$refs['form'].validate((valid) => {
        if (!valid){
          return false;
        }

        this.getProject();

        /* if (valid && data.length > 0) {
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
        }*/
      });
    },
    async handleClose() {
      this.form = {};
      this.$refs['form'].resetFields();
      this.$emit('handleClose');
    },
  },
};
</script>

<style>
</style>
