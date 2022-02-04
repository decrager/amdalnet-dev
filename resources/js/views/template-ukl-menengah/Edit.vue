<template>
  <div class="app-container" style="padding: 24px">
    <form enctype="multipart/form-data" @submit.prevent="saveTemplateUklUplMenengah">
      <el-card>
        <el-row :gutter="20">
          <el-col :span="14">
            <div class="form-container">
              <el-form>
                <el-row>
                  <el-form-item label="Jenis Template">
                    <el-input
                      v-model="currentParam.template_type"
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
              <el-form>
                <el-row>
                  <el-input
                    v-model="currentParam.id"
                    type="hidden"
                  />
                  <el-input
                    v-model="currentParam.file"
                    type="hidden"
                  />
                </el-row>
              </el-form>
              <div class="" style="margin-top: 0.5rem; text-align: right">
                <el-button type="danger" @click="handleCancel">Kembali</el-button>
                <el-button
                  type="primary"
                  icon="el-icon-s-claim"
                  @click="saveTemplateUklUplMenengah()"
                >
                  Edit
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
  name: 'EditUklMenengah',
  props: {},
  data() {
    return {
      currentParam: {},
      file: '',
    };
  },
  created() {
    if (this.$route.params.appParams) {
      this.currentParam = this.$route.params.appParams;
    }
  },
  methods: {
    handleFileUpload() {
      this.file = this.$refs.file.files[0];
    },
    async saveTemplateUklUplMenengah() {
      const formData = new FormData();
      formData.append('id', this.currentParam.id);
      formData.append('file', this.file);
      formData.append('old_file', this.currentParam.file);
      formData.append('template_type', this.currentParam.template_type);

      const headers = { 'Content-Type': 'multipart/form-data' };
      await axios
        .post('api/template-ukl-upl-medium-low/update', formData, { headers })
        .then(() => {
          this.$message({
            message: 'Template UKL UPL Berhasil Disimpan',
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
