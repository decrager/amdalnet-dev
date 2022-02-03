<template>
  <div class="app-container" style="padding: 24px">
    <form enctype="multipart/form-data" @submit.prevent="saveApprovalLingkungan">
      <el-card>
        <el-row :gutter="20">
          <el-col :span="14">
            <div class="form-container">
              <el-form>
                <el-row>
                  <el-form-item label="Jenis Template">
                    <el-input
                      v-model="form.template_type"
                      type="text"
                      placeholder="Jenis Template"
                    />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Download/File">
                    <input
                      ref="file"
                      type="file"
                      class="el-input__inner"
                      @change="handleFileUpload()"
                    >
                  </el-form-item>
                </el-row>
              </el-form>
              <div class="" style="margin-top: 0.5rem; text-align: right">
                <el-button type="danger" @click="handleCancel">Kembali</el-button>
                <el-button
                  type="primary"
                  icon="el-icon-s-claim"
                  @click="saveApprovalLingkungan()"
                >
                  Simpan
                </el-button>
              </div>
            </div>
          </el-col>
        </el-row>
      </el-card>
    </form>
  </div>
</template>
<script>
import axios from 'axios';
export default {
  name: 'AddUklMenengah',
  props: {},
  data() {
    return {
      form: {
        template_type: '',
        description: '',
      },
      file: '',
    };
  },
  mounted() {},
  methods: {
    handleFileUpload() {
      this.file = this.$refs.file.files[0];
    },
    async saveApprovalLingkungan() {
      const formData = new FormData();
      formData.append('file', this.file);
      formData.append('template_type', this.form.template_type);
      formData.append('description', this.form.description);
      const headers = { 'Content-Type': 'multipart/form-data' };
      await axios
        .post('api/environmental-approval', formData, { headers })
        .then(() => {
          this.$message({
            message: 'Ijin Berhasil Disimpan',
            type: 'success',
            duration: 5 * 1000,
          });
          this.$router.push({
            name: 'UklRendah',
          });
        })
        .catch((error) => {
          this.errorMessage = error.message;
          this.$message({
            message: error.message,
            type: 'error',
            duration: 5 * 1000,
          });
        });
    },
    handleCancel() {
      this.$router.push({
        name: 'UklRendah',
      });
    },
  },
};
</script>
<style scoped>
.el-form-item {
  margin-bottom: 0 !important;
}
.edit {
  background-color: #f19e02;
  padding: 0.5rem;
  display: inline-block;
  color: #fff;
}
.delete {
  background-color: #f50103;
  padding: 0.5rem;
  display: inline-block;
  color: #fff;
}
.filter-item {
  float: right;
  margin-bottom: 1rem;
  margin-top: 2rem;
  background-color: #3ab06f;
  color: #fff;
  font-weight: bold;
}
.batal {
  background-color: gray;
  color: #fff;
}
.parentButton {
  justify-content: right;
  display: flex;
  margin-top: 1rem;
}
</style>
