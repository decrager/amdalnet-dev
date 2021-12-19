<template>
  <el-dialog :title="'Pelayanan Publik'" :visible.sync="show">
    <div class="form-container">
      <el-form
        ref="form"
        :model="form"
        label-position="left"
        label-width="150px"
        style="max-width: 500px"
        :rules="rules"
      >
        <el-form-item label="Nama" prop="name">
          <el-input v-model="form.name" />
        </el-form-item>
        <el-form-item label="Email" prop="email">
          <el-input v-model="form.email" />
        </el-form-item>
        <el-form-item label="Pertanyaan" prop="question">
          <el-input v-model="form.question" type="textarea" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="cancel"> Cancel </el-button>
        <el-button type="primary" @click="handleSubmit"> Confirm </el-button>
      </div>
    </div>
  </el-dialog>
</template>

<script>
import Resource from '@/api/resource';
const pubQuRes = new Resource('public-questions');

export default {
  name: 'PublicQuestion',
  props: {
    show: Boolean,
  },
  data() {
    return {
      form: {},
      rules: {
        name: [
          { required: true, message: 'Mohon Masukan Nama Anda', trigger: 'blur' },
        ],
        email: [
          { required: true, message: 'Mohon Masukan Email Anda', trigger: 'blur' },
        ],
        question: [
          { required: true, message: 'Mohon Isi Pertanyaan Anda', trigger: 'blur' },
          { min: 10, message: 'Pertanyaan Anda Terlalu Sedikit', trigger: 'blur' },
        ],
      },
    };
  },
  methods: {
    cancel() {
      this.form = {};
      this.$refs['form'].resetFields();
      this.$emit('cancel');
    },
    handleSubmit() {
      console.log(this.form);

      this.$refs['form'].validate((valid) => {
        if (valid) {
          pubQuRes
            .store(this.form)
            .then(response => {
              this.$message({
                message: 'Terima Kasih Telah Mengajukan Pertanyaan, Jawaban Akan Kami Kirimkan Ke Email Yang Diajukan Oleh Penanya',
                type: 'success',
                duration: 5 * 1000,
              });
              this.cancel();
            })
            .catch(error => {
              console.log(error);
            });
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
  },
};
</script>

<style>
</style>
