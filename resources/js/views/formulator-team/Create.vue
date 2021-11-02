<template>
  <div class="form-container" style="margin: 24px">
    <el-form
      ref="categoryForm"
      :model="currentFormulatorTeam"
      label-position="top"
      label-width="200px"
      style="max-width: 100%"
    >
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="Nama Team" prop="namaTeam">
            <el-input
              v-model="currentFormulatorTeam.name"
              placeholder="Masukkan Nama Team"
            />
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Lembaga LPJP" prop="lembagalpjp">
            <el-select
              v-model="currentFormulatorTeam.id_lpjp"
              placeholder="Pilih LPJP"
              style="width: 100%"
            >
              <el-option
                v-for="item in membershipStatusOptions"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <div slot="footer" class="dialog-footer">
      <el-button @click="handleCancel()"> Batal </el-button>
      <el-button type="primary" @click="handleSubmit()"> Simpan </el-button>
    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const formulatorResource = new Resource('formulator-teams');

export default {
  name: 'CreateTimPenyusun',
  data() {
    return {
      currentFormulatorTeam: {},
      lpjp: [],
    };
  },
  mounted() {
    console.log(this.$route.params.formulator);
    if (this.$route.params.formulator) {
      this.currentFormulatorTeam = this.$route.params.formulator;
    }
  },
  methods: {
    handleCancel() {
      this.$router.push({ name: 'formulatorTeam' });
    },
    handleSubmit() {
      this.currentFormulatorTeam.cv_penyusun = this.sertifikatFileUpload;
      this.currentFormulatorTeam.file_sertifikat = this.cvFileUpload;

      // make form data because we got file
      const formData = new FormData();

      // eslint-disable-next-line no-undef
      _.each(this.currentFormulatorTeam, (value, key) => {
        formData.append(key, value);
      });

      if (this.currentFormulatorTeam.id !== undefined) {
        formulatorResource
          .updateMultipart(this.currentFormulatorTeam.id, formData)
          .then((response) => {
            this.$message({
              type: 'success',
              message: 'Penyusun Berhasil di Update',
              duration: 5 * 1000,
            });
            this.$router.push('/master-data/formulator');
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        formulatorResource
          .store(formData)
          .then((response) => {
            this.$message({
              message:
                'Penyusun Baru atas nama ' +
                this.currentFormulatorTeam.name +
                ' Berhasil Dibuat',
              type: 'success',
              duration: 5 * 1000,
            });
            this.currentFormulatorTeam = {};
            this.$router.push('/master-data/formulator');
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    handleUploadSertifikat(file, fileList) {
      this.sertifikatFileName = file.name;
      this.sertifikatFileUpload = file.raw;
    },
    handleUploadCv(file, fileList) {
      this.cvFileName = file.name;
      this.cvFileUpload = file.raw;
    },
  },
};
</script>
