<template>
  <div class="app-container" style="padding: 24px">
    <form enctype="multipart/form-data" @submit.prevent="saveKebijakan">
      <el-card>
        <el-row :gutter="20">
          <el-col :span="14">
            <div class="form-container">
              <el-form>
                <el-row>
                  <el-form-item label="Jenis Peraturan">
                    <el-select
                      v-model="form.regulation_type"
                      placeholder="Jenis Peraturan"
                      style="width: 100%"
                    >
                      <el-option
                        v-for="item in regulations"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id"
                      />
                    </el-select>
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Kebijakan PPU">
                    <el-input
                      v-model="form.regulation_no"
                      type="text"
                      placeholder="Kebijakan PPU"
                    />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Bidang Kegiatan">
                    <el-input
                      v-model="form.field_of_activity"
                      type="text"
                      placeholder="Bidang Kegiatan"
                    />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Tentang">
                    <el-input
                      v-model="form.about"
                      type="textarea"
                      placeholder="Tentang"
                    />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Taggal Terbit">
                    <el-input
                      v-model="form.set"
                      type="date"
                      placeholder="Taggal Terbit"
                    />
                  </el-form-item>
                </el-row>
              </el-form>
              <el-form>
                <el-row>
                  <el-form-item label="Upload Document">
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
                <el-button
                  type="danger"
                  @click="handleCancel"
                >Kembali</el-button>
                <el-button
                  type="primary"
                  icon="el-icon-s-claim"
                  @click="saveKebijakan()"
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
  name: 'AddKebijakan',
  props: {},
  data() {
    return {
      regulations: [],
      form: {
        regulation_no: '',
        regulation_type: null,
        about: '',
        set: '',
        field_of_activity: '',
      },
      link: '',
    };
  },
  created() {
    this.getRegulations();
  },
  methods: {
    getRegulations() {
      axios.get(`/api/regulations`).then((response) => {
        this.regulations = response.data;
      });
    },
    handleFileUpload() {
      this.link = this.$refs.link.files[0];
    },
    async saveKebijakan() {
      const formData = new FormData();
      formData.append('link', this.link);
      formData.append('regulation_no', this.form.regulation_no);
      formData.append('regulation_type', this.form.regulation_type);
      formData.append('about', this.form.about);
      formData.append('set', this.form.set);
      formData.append('field_of_activity', this.form.field_of_activity);

      const headers = { 'Content-Type': 'multipart/form-data' };
      await axios
        .post('api/policys', formData, { headers })
        .then(() => {
          this.$message({
            message: 'Kebijakan Berhasil Disimpan',
            type: 'success',
            duration: 5 * 1000,
          });
          this.$router.push({
            name: 'MateriDanKebijakan',
            params: { tabActive: 'kebijakan' },
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
        params: { tabActive: 'kebijakan' },
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
