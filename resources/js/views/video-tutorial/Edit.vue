<template>
  <div class="app-container" style="padding: 24px">
    <el-form
      enctype="multipart/form-data"
      :model="currentParam"
      @submit.prevent="saveVideoTutorial"
    >
      <el-card>
        <el-row :gutter="20">
          <el-col :span="14">
            <div class="form-container">
              <el-form>
                <el-row>
                  <el-form-item label="Nama Video"><small style="color: red">*</small>
                    <el-input v-model="currentParam.name" type="text" placeholder="Nama Video" />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Jenis Tutorial">
                    <el-select v-model="currentParam.tutorial_type" placeholder="Jenis Tutorial" style="width:100%">
                      <el-option
                        v-for="item in form.tutorial_type"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                        :selected="currentParam.tutorial_type === item.value"
                      />
                    </el-select>
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Video">
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
                  @click="saveVideoTutorial()"
                >
                  Simpan
                </el-button>
              </div>
            </div>
          </el-col>
        </el-row>
      </el-card>
    </el-form>
  </div>
</template>
<script>
import axios from 'axios';
export default {
  name: 'CreateVideoTutorial',
  props: {},
  data() {
    return {
      file: '',
      name: '',
      currentParam: {},
      form: {
        tutorial_type: [
          {
            value: 'Landing Page',
            label: 'Landing Page',
          },
          {
            value: 'Registrasi',
            label: 'Registrasi',
          },
          {
            value: 'Penapisan',
            label: 'Penapisan',
          },
          {
            value: 'Asistensi Pelingkupan',
            label: 'Asistensi Pelingkupan',
          },
          {
            value: 'Penilaian Dokumen Lingkungan Hidup',
            label: 'Penilaian Dokumen Lingkungan Hidup',
          },
          {
            value: 'Lainnya',
            label: 'Lainnya',
          },
        ],
      },
      value: '',
    };
  },
  created() {
    if (this.$route.params.appParams) {
      this.currentParam = this.$route.params.appParams;
    }
  },
  methods: {
    handleFileUpload() {
      if (this.$refs.file.files[0].size > 1024 * 1024 * 200) {
        this.$alert('File yang anda upload melebihi 200 Mb', {
          center: true,
        });
      }
      this.file = this.$refs.file.files[0];
    },
    async saveVideoTutorial() {
      const formData = new FormData();
      formData.append('url_video', this.file);
      formData.append('name', this.currentParam.name);
      formData.append('tutorial_type', this.currentParam.tutorial_type);
      formData.append('old_link', this.currentParam.link);
      formData.append('id', this.currentParam.id);
      const headers = { 'Content-Type': 'multipart/form-data' };
      await axios
        .post('api/tutorial-video/update', formData, { headers })
        .then(() => {
          this.$message({
            message: 'Tutorial Berhasil Disimpan',
            type: 'success',
            duration: 5 * 1000,
          });
          this.$router.push({
            name: 'VideoTutorial',
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
        name: 'VideoTutorial',
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
