<template>
  <div class="app-container" style="padding: 24px">
    <form
      enctype="multipart/form-data"
      @submit.prevent="saveVideoTutorial"
    >
      <el-card>
        <el-row :gutter="20">
          <el-col :span="14">
            <div class="form-container">
              <el-form>
                <el-row>
                  <el-form-item label="Nama Video"><small style="color: red">*</small>
                    <el-input v-model="name" type="text" placeholder="Nama Video" />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item :class="{ 'is-error': errors.tutorial_type }">
                    <label style="color: #606266">Jenis Tutorial<small style="color: red">*</small></label>
                    <el-select v-model="value" placeholder="Jenis Tutorial" style="width:100%">
                      <el-option
                        v-for="item in form.tutorial_type"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                      />
                    </el-select>
                    <small v-if="errors.tutorial_type" style="color: #f56c6c">Jenis Tutorial Wajib Diisi</small>
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item :class="{ 'is-error': errors.video_upload || errors.video_maks }">
                    <label style="color: #606266">Video (Maksimal 50 MB)<small style="color: red">*</small></label>
                    <input
                      ref="file"
                      type="file"
                      class="el-input__inner"
                      @change="handleFileUpload()"
                    >
                    <small v-if="errors.video_upload" style="color: #f56c6c">Video Wajib Diisi</small>
                    <small v-if="errors.video_maks" style="color: #f56c6c">Ukuran File Video Terlalu Besar</small>
                  </el-form-item>
                </el-row>
              </el-form>
              <div class="" style="margin-top: 0.5rem; text-align: right">
                <el-button type="danger" @click="handleCancel">Kembali</el-button>
                <el-button
                  type="primary"
                  icon="el-icon-s-claim"
                  @click="checkSubmit"
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
  name: 'CreateVideoTutorial',
  props: {},
  data() {
    return {
      file: '',
      name: '',
      errors: {
        tutorial_type: null,
        video_upload: null,
        video_maks: null,
      },
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
  mounted() {},
  methods: {
    handleFileUpload() {
      this.file = this.$refs.file.files[0];
    },
    checkSubmit() {
      this.resetErrors();

      let errors = 0;

      if (!this.value) {
        errors++;
        this.errors.tutorial_type = true;
      }

      if (!this.file) {
        errors++;
        this.errors.video_upload = true;
      } else if (this.$refs.file.files[0].size > 1024 * 1024 * 50) {
        errors++;
        this.errors.video_maks = true;
      }

      if (errors === 0) {
        this.saveVideoTutorial();
      }
    },
    async saveVideoTutorial() {
      console.log(this.value);
      const formData = new FormData();
      formData.append('url_video', this.file);
      formData.append('name', this.name);
      formData.append('tutorial_type', this.value);
      const headers = { 'Content-Type': 'multipart/form-data' };
      await axios
        .post('api/tutorial-video', formData, { headers })
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
    resetErrors() {
      this.errors = {
        tutorial_type: null,
        video_upload: null,
        video_maks: null,
      };
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

<style>
.is-error {
  border-color: #f56c6c;
}
</style>
