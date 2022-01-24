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
                  <el-form-item label="Nama Pemrakarsa">
                    <el-input type="text" placeholder="Nama Pemrakarsa" />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Nama Usaha/Kegiatan">
                    <el-input type="textarea" placeholder="Nama Usaha/Kegiatan" />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Nomor SK">
                    <el-input type="text" placeholder="Nomor SK" />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Tanggal Berlaku SK">
                    <el-date-picker
                      v-model="form.raise_date"
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
                  <el-form-item label="Penerbit SK">
                    <el-input type="text" placeholder="Penerbit SK" />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="File">
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
// import axios from 'axios';

export default {
  name: 'AddIzin',
  props: {},
  data() {
    return {
      form: {
        raise_date: '',
        link: '',
      },
      link: '',
    };
  },
  mounted() {},
  methods: {
    handleFileUpload() {
      this.link = this.$refs.link.files[0];
    },
    async saveMateri() {
      // const formData = new FormData();
      // formData.append('link', this.link);
      // formData.append('name', this.form.name);
      // formData.append('description', this.form.description);
      // formData.append('raise_date', this.form.raise_date);

      // const headers = { 'Content-Type': 'multipart/form-data' };
      // await axios
      //   .post('api/materials', formData, { headers })
      //   .then(() => {
      //     this.$message({
      //       message: 'Materi Berhasil Disimpan',
      //       type: 'success',
      //       duration: 5 * 1000,
      //     });
      //     this.$router.push({
      //       name: 'MateriDanKebijakan',
      //       params: { tabActive: 'materi' },
      //     });
      //   })
      //   .catch((error) => {
      //     this.errorMessage = error.message;
      //     this.$message({
      //       message: 'Ada Kesalahan, Pastikan Data Terisi',
      //       type: 'error',
      //       duration: 5 * 1000,
      //     });
      //   });
      alert('submit');
    },
    handleCancel() {
      this.$router.push({
        name: 'DaftarIzins',
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
