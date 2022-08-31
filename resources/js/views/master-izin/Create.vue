<!-- eslint-disable vue/html-self-closing -->
<template>
  <div class="app-container" style="padding: 24px">
    <el-form
      ref="permitForm"
      v-loading="loading"
      :model="form"
      :rules="formRules"
      label-position="top"
      label-width="200px"
      style="max-width: 100%"
    >
      <el-card>
        <el-row :gutter="20">
          <el-col :span="14">
            <div class="form-container">
              <el-row style="margin-bottom: 10px">
                <el-form-item label="Nama Pemrakarsa" prop="pemarkasa_name">
                  <el-input
                    v-model="form.pemarkasa_name"
                    type="text"
                    placeholder="Nama Pemrakarsa"
                    name="pemarkasa_name"
                  />
                </el-form-item>
              </el-row>
              <el-row style="margin-bottom: 10px">
                <el-form-item label="Kewenangan" prop="authority">
                  <el-select
                    v-model="form.authority"
                    placeholder="Kewenangan"
                    style="width: 100%"
                    name="authority"
                  >
                    <el-option
                      v-for="(item, i) in kewenangan"
                      :key="i"
                      :label="item.name.toUpperCase()"
                      :value="item.name"
                    />
                  </el-select>
                </el-form-item>
              </el-row>
              <el-row style="margin-bottom: 10px">
                <el-form-item label="Nama Usaha/Kegiatan" prop="kegiatan_name">
                  <el-input
                    v-model="form.kegiatan_name"
                    type="textarea"
                    placeholder="Nama Usaha/Kegiatan"
                    name="kegiatan_name"
                  />
                </el-form-item>
              </el-row>
              <el-row style="margin-bottom: 10px">
                <el-form-item
                  label="Jenis Dokumen Lingkungan"
                  prop="document_type"
                >
                  <el-select
                    v-model="form.document_type"
                    placeholder="Pilih Jenis Dokumen Lingkungan"
                    style="width: 100%"
                    name="document_type"
                  >
                    <el-option
                      v-for="(document, idx) in document_type"
                      :key="idx"
                      :label="document.val"
                      :value="document.val"
                    />
                  </el-select>
                </el-form-item>
              </el-row>
              <el-row style="margin-bottom: 10px">
                <el-form-item label="Nomor SK" prop="sk_number">
                  <el-input
                    v-model="form.sk_number"
                    type="text"
                    placeholder="Nomor SK"
                    name="sk_number"
                  />
                </el-form-item>
              </el-row>
              <el-row style="margin-bottom: 10px">
                <el-form-item label="Tanggal Berlaku SK" prop="date">
                  <el-date-picker
                    v-model="form.date"
                    type="date"
                    placeholder="yyyy-MM-dd"
                    value-format="yyyy-MM-dd"
                    style="width: 100%"
                    name="date"
                  />
                </el-form-item>
              </el-row>
              <el-row style="margin-bottom: 10px">
                <el-form-item label="Penerbit SK" prop="publisher">
                  <el-input
                    v-model="form.publisher"
                    type="text"
                    placeholder="Penerbit SK"
                    name="publisher"
                  />
                </el-form-item>
              </el-row>
              <el-row style="margin-bottom: 10px">
                <el-form-item label="File" prop="file">
                  <el-upload
                    class="upload-demo"
                    :auto-upload="false"
                    :on-change="handleUploadFile"
                    action="#"
                    :show-file-list="false"
                    name="file"
                  >
                    <el-button size="small" type="primary"> Upload </el-button>
                    <span>
                      {{ fileName }}
                    </span>
                  </el-upload>
                </el-form-item>
              </el-row>
              <div class="" style="margin-top: 0.5rem; text-align: right">
                <el-button
                  :loading="loading"
                  type="danger"
                  @click="handleCancel"
                >
                  Kembali
                </el-button>
                <el-button
                  :loading="loading"
                  type="primary"
                  icon="el-icon-s-claim"
                  @click="saveIjinLingkungan"
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
  name: 'AddIzin',
  props: {},
  data() {
    const validateFile = (rule, value, callback) => {
      if (!this.form.file) {
        callback(new Error('File Wajib Diunggah'));
      } else {
        callback();
      }
    };

    return {
      loading: false,
      form: {
        pemarkasa_name: '',
        authority: '',
        kegiatan_name: '',
        sk_number: '',
        date: '',
        publisher: '',
        file: null,
      },
      fileName: '',
      document_type: [
        {
          val: 'Amdal',
        },
        {
          val: 'UKL-UPL',
        },
        {
          val: 'DELH',
        },
        {
          val: 'DPLH',
        },
        {
          val: 'Perubahan PL',
        },
      ],
      kewenangan: [
        { name: 'pusat' },
        { name: 'provinsi' },
        { name: 'kab/kota' },
      ],
      formRules: {
        pemarkasa_name: [
          {
            required: true,
            trigger: 'blur',
            message: 'Nama Wajib Diisi',
          },
        ],
        authority: [
          {
            required: true,
            trigger: 'blur',
            message: 'Kewenangan Wajib Dipilih',
          },
        ],
        kegiatan_name: [
          {
            required: true,
            trigger: 'blur',
            message: 'Nama Usaha/Kegiatan Wajib Diisi',
          },
        ],
        document_type: [
          {
            required: true,
            trigger: 'blur',
            message: 'Jenis Dokumen Wajib Dipilih',
          },
        ],
        sk_number: [
          {
            required: true,
            trigger: 'blur',
            message: 'Nomor SK Wajib Diisi',
          },
        ],
        date: [
          {
            required: true,
            trigger: 'blur',
            message: 'Tanggal Berlaku Wajib Diisi',
          },
        ],
        publisher: [
          {
            required: true,
            trigger: 'blur',
            message: 'Penerbit Wajib Diisi',
          },
        ],
        file: [
          {
            required: true,
            trigger: 'blur',
            validator: validateFile,
          },
        ],
      },
    };
  },
  methods: {
    handleUploadFile(file, fileList) {
      if (file.raw.size > 10485760) {
        this.$alert('File Yang Diupload Melebihi 10 MB', {
          confirmButtonText: 'OK',
        });
        return;
      }

      this.fileName = file.name;
      this.form.file = file.raw;
    },
    saveIjinLingkungan() {
      this.$refs.permitForm.validate((valid) => {
        if (valid) {
          this.loading = true;
          const formData = new FormData();
          formData.append('file', this.form.file);
          formData.append('pemarkasa_name', this.form.pemarkasa_name);
          formData.append('authority', this.form.authority);
          formData.append('kegiatan_name', this.form.kegiatan_name);
          formData.append('sk_number', this.form.sk_number);
          formData.append('date', this.form.date);
          formData.append('publisher', this.form.publisher);
          if (this.form.document_type) {
            formData.append('document_type', this.form.document_type);
          }

          const headers = { 'Content-Type': 'multipart/form-data' };
          axios
            .post('api/environmental-permit', formData, { headers })
            .then(() => {
              this.$message({
                message: 'Ijin Berhasil Disimpan',
                type: 'success',
                duration: 5 * 1000,
              });
              this.$router.push({
                name: 'DaftarIzins',
              });
              this.loading = false;
            })
            .catch((error) => {
              this.errorMessage = error.message;
              this.$message({
                message: error.message,
                type: 'error',
                duration: 5 * 1000,
              });
              this.loading = false;
            });
        } else {
          return false;
        }
      });
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
