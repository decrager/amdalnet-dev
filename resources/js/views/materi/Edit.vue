<template>
  <div class="app-container" style="padding: 24px">
    <el-form
      ref="categoryForm"
      :model="currentParam"
      label-position="top"
      label-width="200px"
      style="max-width: 100%"
    >
      <el-card>
        <el-row :gutter="20">
          <el-col :span="14">
            <div class="form-container">
              <el-form>
                <el-row>
                  <el-form-item label="Judul Materi">
                    <el-input v-model="currentParam.name" type="text" placeholder="Judul Materi" />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Deskripsi">
                    <el-input v-model="currentParam.description" type="textarea" placeholder="Deskripsi" />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Jenis">
                    <el-select
                      v-model="currentParam.jenis"
                      placeholder="Pilih Jenis Materi"
                      style="width: 100%"
                    >
                      <el-option
                        v-for="item in jenis"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                      />
                    </el-select>
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Tanggal Terbit">
                    <el-date-picker
                      v-model="currentParam.raise_date"
                      type="date"
                      placeholder="yyyy-MM-dd"
                      value-format="yyyy-MM-dd"
                      style="width: 100%"
                    />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Upload Dokumen (MAX 10MB)">
                    <el-input
                      v-model="currentParam.link"
                      type="hidden"
                    />
                    <input
                      ref="link"
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
                  @click="saveMateri()"
                >
                  Edit
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
  name: 'AddMateri',
  props: {},
  data() {
    return {
      currentParam: {},
      link: '',
      jenis: [
        {
          label: 'Materi',
          value: 'materi',
        },
        {
          label: 'Panduan',
          value: 'panduan',
        },
      ],
    };
  },
  created() {
    if (this.$route.params.appParams) {
      this.currentParam = this.$route.params.appParams;
    }
  },
  methods: {
    handleFileUpload(e) {
      this.link = this.$refs.link.files[0];

      if (this.link.size > 10485760){
        this.showFileAlert();
        return;
      }
    },

    showFileAlert(){
      this.$alert(`File Yang Diupload Melebihi 10 MB`, {
        confirmButtonText: 'OK',
      });
    },
    async saveMateri() {
      const formData = new FormData();
      formData.append('id', this.currentParam.id);
      formData.append('link', this.link);
      formData.append('name', this.currentParam.name);
      formData.append('description', this.currentParam.description);
      formData.append('description', this.currentParam.jenis);
      formData.append('raise_date', this.currentParam.raise_date);
      formData.append('old_link', this.currentParam.link);

      const headers = { 'Content-Type': 'multipart/form-data' };
      await axios
        .post('api/materials/update', formData, { headers })
        .then(() => {
          this.$message({
            message: 'Materi Berhasil Disimpan',
            type: 'success',
            duration: 5 * 1000,
          });
          this.$router.push({
            name: 'MateriDanKebijakan',
            params: { tabActive: 'materi' },
          });
        })
        .catch((error) => {
          this.errorMessage = error.message;
          this.$message({
            message: 'Ada Kesalahan, Pastikan Data Terisi',
            type: 'error',
            duration: 5 * 1000,
          });
        });
    },
    handleCancel() {
      this.$router.push({
        name: 'MateriDanKebijakan',
        params: { tabActive: 'materi' },
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
