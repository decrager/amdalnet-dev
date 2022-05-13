<template>
  <div class="app-container" style="padding: 24px">
    <el-card v-loading="loading">
      <h3>Perbarui Data {{ formulator.name }}</h3>
      <el-form
        ref="formulatorForm"
        :model="formulator"
        :rules="formulatorRules"
        label-position="top"
        label-width="200px"
        style="max-width: 100%"
      >
        <el-row
          :gutter="32"
          style="display: flex; align-items: center; padding-right: 16px"
        >
          <el-col :sm="24" :md="6" align="center">
            <el-upload class="avatar-uploader" action="#" :disabled="true">
              <!-- eslint-disable-next-line vue/html-self-closing -->
              <img v-if="avatarUrl" :src="avatarUrl" class="avatar" />
              <i v-else class="el-icon-user avatar-uploader-icon" />
            </el-upload>
            <el-upload
              action="#"
              :show-file-list="false"
              :multiple="false"
              :auto-upload="false"
              :on-change="handleUploadAvatar"
            >
              <el-button type="warning" size="mini">Upload</el-button>
            </el-upload>
          </el-col>
          <el-col :sm="24" :md="18">
            <el-row :gutter="32" style="margin-bottom: 14px">
              <el-col :sm="3" :md="3"> <b>Nama:</b> </el-col>
              <el-col :sm="21" :md="21">{{ formulator.name }}</el-col>
            </el-row>
            <el-row :gutter="32" style="margin-bottom: 14px">
              <el-col :sm="3" :md="3"> <b>NIK:</b> </el-col>
              <el-col :sm="21" :md="21">{{ formulator.nik }}</el-col>
            </el-row>
            <el-row :gutter="32" style="margin-bottom: 14px">
              <el-col :sm="3" :md="3"> <b>Alamat:</b> </el-col>
              <el-col :sm="21" :md="21">
                {{ formulator.address
                }}{{ capitalizeArea(formulator.formulator_district)
                }}{{ capitalizeArea(formulator.formulator_province) }}
              </el-col>
            </el-row>
            <el-row :gutter="32" style="margin-bottom: 14px">
              <el-col :sm="3" :md="3"> <b>Telepon:</b> </el-col>
              <el-col :sm="21" :md="21"> {{ formulator.phone }} </el-col>
            </el-row>
            <el-row :gutter="32" style="margin-bottom: 14px">
              <el-col :sm="3" :md="3"> <b>Email:</b> </el-col>
              <el-col :sm="21" :md="21"> {{ formulator.email }} </el-col>
            </el-row>
          </el-col>
        </el-row>
        <el-row
          :gutter="32"
          style="margin-top: 40px; padding-left: 16px; padding-right: 16px"
        >
          <el-col :sm="24" :md="12">
            <el-form-item label="Sertifikasi" prop="membership_status">
              <el-select
                v-model="formulator.membership_status"
                name="membership_status"
                placeholder="Sertifikasi"
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
          <el-col :sm="24" :md="12">
            <el-form-item label="Unggah Sertifikat">
              <el-upload
                :auto-upload="false"
                :on-change="handleUploadSertifikat"
                action="#"
                :show-file-list="false"
              >
                <el-button size="small" type="warning"> Upload </el-button>
                <span>
                  {{ sertifikatFileName }}
                </span>
              </el-upload>
            </el-form-item>
          </el-col>
          <el-col :sm="24" :md="12">
            <el-form-item label="Tanggal Berlaku" prop="date_start">
              <el-date-picker
                v-model="formulator.date_start"
                name="date_start"
                type="date"
                placeholder="Pilih tanggal"
                value-format="yyyy-MM-dd"
                style="width: 100%"
              />
            </el-form-item>
          </el-col>
          <el-col :sm="24" :md="12">
            <el-form-item label="Keahlian Penyusun" prop="expertise">
              <el-select
                v-model="selectedExpertise"
                name="expertise"
                placeholder="Keahlian Penyusun"
                style="width: 100%"
                @change="handleChangeExpertise"
              >
                <el-option
                  v-for="item in expertise"
                  :key="item.value"
                  :label="item.value"
                  :value="item.value"
                />
              </el-select>
            </el-form-item>
            <el-form-item
              v-if="selectedExpertise === 'Ahli Lainnya'"
              label=""
              prop="otherExpertise"
            >
              <el-input
                v-model="formulator.expertise"
                name="otherExpertise"
                placeholder="Isi Keahlian"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <div style="text-align: right">
          <el-button
            :loading="loadingSubmit"
            type="primary"
            @click="checkSubmit"
          >
            Simpan
          </el-button>
        </div>
      </el-form>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const formulatorResource = new Resource('formulators');

export default {
  name: 'FormulatorCertificate',
  data() {
    const validateExpertise = (rule, value, callback) => {
      if (this.selectedExpertise !== 'Ahli Lainnya' && !value) {
        callback(new Error('Keahlian Wajib Dipilih'));
      } else {
        callback();
      }
    };
    const validateOtherExpertise = (rule, value, callback) => {
      if (
        this.selectedExpertise === 'Ahli Lainnya' &&
        !this.formulator.expertise
      ) {
        callback(new Error('Keahlian Wajib Diisi'));
      } else {
        callback();
      }
    };

    return {
      loading: false,
      loadingSubmit: false,
      formulator: {},
      avatarUrl: null,
      sertifikatFileName: null,
      selectedExpertise: null,
      membershipStatusOptions: [
        {
          value: 'KTPA',
          label: 'Ketua Tim Penyusun Amdal (KTPA)',
        },
        {
          value: 'ATPA',
          label: 'Anggota Tim Penyusun Amdal (ATPA)',
        },
      ],
      expertise: [
        {
          value: 'Ahli Mutu Udara',
        },
        {
          value: 'Ahli Mutu Air',
        },
        {
          value: 'Ahli Mutu Tanah',
        },
        {
          value: 'Ahli Keanekaragaman Hayati',
        },
        {
          value: 'Ahli Kehutanan',
        },
        {
          value: 'Ahli Sosial',
        },
        {
          value: 'Ahli Kesehatan Masyarakat',
        },
        {
          value: 'Ahli Transportasi',
        },
        {
          value: 'Ahli Geologi',
        },
        {
          value: 'Ahli Hidrogeologi',
        },
        {
          value: 'Ahli Hidrologi',
        },
        {
          value: 'Ahli Kelautan',
        },
        {
          value: 'Ahli Lainnya',
        },
      ],
      formulatorRules: {
        expertise: [
          {
            required: true,
            trigger: 'blur',
            validator: validateExpertise,
          },
        ],
        otherExpertise: [
          {
            required: 'true',
            trigger: 'blur',
            validator: validateOtherExpertise,
          },
        ],
        date_start: [
          {
            required: true,
            trigger: 'blur',
            message: 'Tanggal Berlaku Wajib Dipilih',
          },
        ],
        membership_status: [
          {
            required: true,
            trigger: 'blur',
            message: 'Sertifikasi Wajib Dipilih',
          },
        ],
      },
    };
  },
  created() {
    this.getData();
  },
  methods: {
    async getData() {
      this.loading = true;
      this.formulator = await formulatorResource.get(this.$route.params.id);
      this.formulator.avatar = await formulatorResource.list({
        avatar: 'true',
        email: this.formulator.email,
      });
      const checkExpertise = this.expertise.find(
        (x) => x.value === this.formulator.expertise
      );
      // === CHECK EXPERTISE === //
      if (checkExpertise) {
        this.selectedExpertise = this.formulator.expertise;
      } else {
        this.selectedExpertise = 'Ahli Lainnya';
      }

      // === SET FILE TO NULL === //
      this.formulator.file_sertifikat = null;
      this.formulator.cv_penyusun = null;
      this.loading = false;
    },
    checkSubmit() {
      this.$refs.formulatorForm.validate((valid) => {
        if (valid) {
          this.handleSubmit();
        }
      });
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      const formData = new FormData();
      formData.append('sertifikasi', 'true');
      formData.append('id', this.$route.params.id);
      formData.append('avatarFile', this.formulator.avatarFile);
      formData.append('membership_status', this.formulator.membership_status);
      formData.append('file_sertifikat', this.formulator.file_sertifikat);
      formData.append('date_start', this.formulator.date_start);
      formData.append('expertise', this.formulator.expertise);

      await formulatorResource.store(formData);
      this.$message({
        type: 'success',
        message: 'Penyusun Berhasil di Update',
        duration: 5 * 1000,
      });
      this.$router.push({ name: 'formulator' });
    },
    handleUploadAvatar(file) {
      if (file.raw.size > 1048576) {
        this.showFileAlert();
        return;
      }

      this.avatarUrl = URL.createObjectURL(file.raw);
      this.formulator.avatarFile = file.raw;
    },
    capitalizeArea(value) {
      if (value === undefined || value === null) {
        return '';
      }

      return ', ' + this.capitalize(value.name);
    },
    capitalize(mySentence) {
      const words = mySentence.split(' ');

      const newWords = words
        .map((word) => {
          return (
            word.toLowerCase()[0].toUpperCase() +
            word.toLowerCase().substring(1)
          );
        })
        .join(' ');
      return newWords;
    },
    handleUploadSertifikat(file, fileList) {
      if (file.raw.size > 1048576) {
        this.showFileAlert();
        return;
      }

      this.sertifikatFileName = file.name;
      this.formulator.file_sertifikat = file.raw;
    },
    showFileAlert() {
      this.$alert('File Yang Diupload Melebihi 1 MB', {
        confirmButtonText: 'OK',
      });
    },
    handleChangeExpertise(data) {
      if (data !== 'Ahli Lainnya') {
        this.formulator.expertise = data;
      } else {
        this.formulator.expertise = null;
      }
    },
  },
};
</script>

<style>
.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 50%;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.avatar-uploader .el-upload:hover {
  border-color: #409eff;
}
.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  line-height: 178px;
  text-align: center;
}
.avatar {
  width: 178px;
  height: 178px;
  display: block;
}
</style>
