<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <el-form
        ref="formulatorForm"
        v-loading="loading"
        :model="currentFormulator"
        :rules="formulatorRules"
        label-position="top"
        label-width="200px"
        style="max-width: 100%"
      >
        <el-row :gutter="32">
          <el-col :sm="24" :md="12">
            <el-form-item label="NIK" prop="nik">
              <el-input
                v-model="currentFormulator.nik"
                name="nik"
                type="number"
                placeholder="NIK"
              />
            </el-form-item>
          </el-col>
          <el-col :sm="24" :md="12">
            <el-form-item label="Nama Penyusun" prop="name">
              <el-input
                v-model="currentFormulator.name"
                name="name"
                placeholder="Nama Penyusun"
              />
            </el-form-item>
          </el-col>
          <el-col :sm="24" :md="12">
            <el-form-item label="Email" prop="email">
              <el-input
                v-model="currentFormulator.email"
                name="email"
                placeholder="Email"
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
                v-model="currentFormulator.expertise"
                name="otherExpertise"
                placeholder="Isi Keahlian"
              />
            </el-form-item>
          </el-col>
          <el-col :sm="24" :md="12">
            <el-form-item prop="phone" label="Nomor Telepon">
              <el-input
                v-model.number="currentFormulator.phone"
                name="phone"
                placeholder="Nomor Telepon"
              >
                <template slot="prepend">+62</template>
              </el-input>
            </el-form-item>
          </el-col>
          <el-col :sm="24" :md="12">
            <el-form-item label="Nomor Registrasi" prop="reg_no">
              <el-input
                v-model="currentFormulator.reg_no"
                name="reg_no"
                placeholder="Nomor Registrasi"
              />
            </el-form-item>
          </el-col>
          <el-col :sm="24" :md="12">
            <el-form-item label="CV Penyusun" prop="cvPenyusun">
              <el-upload
                class="upload-demo"
                :auto-upload="false"
                :on-change="handleUploadCv"
                action="#"
                :show-file-list="false"
              >
                <el-button size="small" type="primary"> Upload </el-button>
                <span>{{ cvFileName }}</span>
              </el-upload>
            </el-form-item>
          </el-col>
          <el-col :sm="24" :md="12">
            <el-form-item label="Sertifikasi" prop="membership_status">
              <el-select
                v-model="currentFormulator.membership_status"
                name="membership_status"
                placeholder="Pilih"
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
          <el-col v-if="currentFormulator.isCertified" :span="12">
            <el-form-item label="Unggah Sertifikat" prop="certificate">
              <el-upload
                class="upload-demo"
                :auto-upload="false"
                :on-change="handleUploadSertifikat"
                action="#"
                :show-file-list="false"
              >
                <el-button size="small" type="primary"> Upload </el-button>
                <span>
                  {{ sertifikatFileName }}
                </span>
              </el-upload>
            </el-form-item>
          </el-col>
          <el-col :sm="24" :md="24">
            <el-form-item>
              <el-checkbox
                v-model="currentFormulator.isCertified"
                name="isCertified"
                @change="handleChangeIsCertified"
              >
                Memiliki Sertifikat Kompetensi AMDAL
              </el-checkbox>
            </el-form-item>
          </el-col>
          <el-col v-if="currentFormulator.isCertified" :sm="24" :md="12">
            <el-form-item label="Tanggal Berlaku" prop="date_start">
              <el-date-picker
                v-model="currentFormulator.date_start"
                name="date_start"
                type="date"
                placeholder="Pilih tanggal"
                value-format="yyyy-MM-dd"
                style="width: 100%"
              />
            </el-form-item>
          </el-col>
        </el-row>
      </el-form>
      <div style="text-align: right">
        <el-button
          :loading="loadingSubmit"
          type="primary"
          @click="handleSubmit"
        >
          Simpan
        </el-button>
      </div>
    </el-card>
  </div>
</template>

<script>
import { validEmail } from '@/utils/validate';
import Resource from '@/api/resource';
const formulatorResource = new Resource('formulators');

export default {
  name: 'CreatePenyusun',
  data() {
    const validateEmail = (rule, value, callback) => {
      if (value) {
        if (!validEmail(value)) {
          callback(new Error('Email Tidak Valid'));
          return;
        }
      }

      callback();
    };
    const validateOtherExpertise = (rule, value, callback) => {
      if (
        this.selectedExpertise === 'Ahli Lainnya' &&
        !this.currentFormulator.expertise
      ) {
        callback(new Error('Keahlian Wajib Diisi'));
      } else {
        callback();
      }
    };
    const validateDateStart = (rule, value, callback) => {
      if (this.currentFormulator.isCertified && !value) {
        callback(new Error('Tanggal Berlaku Wajib Dipilih'));
      } else {
        callback();
      }
    };
    const validateCertificate = (rule, value, callback) => {
      if (
        this.currentFormulator.isCertified &&
        !this.currentFormulator.file_sertifikat
      ) {
        callback(new Error('Sertifikat Wajib Diunggah'));
      } else {
        callback();
      }
    };
    const validateMembershipStatus = (rule, value, callback) => {
      if (this.currentFormulator.isCertified && !value) {
        callback(new Error('Sertifikasi Wajib Dipilih'));
      } else {
        callback();
      }
    };

    return {
      loading: false,
      loadingSubmit: false,
      currentFormulator: {},
      sertifikatFileUpload: null,
      sertifikatFileName: null,
      cvFileUpload: null,
      cvFileName: null,
      selectedExpertise: null,
      formulatorRules: {
        reg_no: [
          {
            required: true,
            trigger: 'blur',
            message: 'Nomor Registrasi Wajib Diisi',
          },
        ],
        name: [
          { required: true, trigger: 'blur', message: 'Nama Wajib Diisi' },
        ],
        email: [
          {
            trigger: 'blur',
            validator: validateEmail,
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
            validator: validateDateStart,
          },
        ],
        certificate: [
          {
            required: this.$route.name === 'createFormulator',
            trigger: 'blur',
            validator:
              this.$route.name === 'createFormulator'
                ? validateCertificate
                : null,
          },
        ],
        membership_status: [
          {
            required: true,
            trigger: 'blur',
            validator: validateMembershipStatus,
          },
        ],
      },
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
    };
  },
  methods: {
    handleSubmit() {
      this.$refs.formulatorForm.validate((valid) => {
        if (valid) {
          this.loadingSubmit = true;
          // make form data because we got file
          const formData = new FormData();

          // eslint-disable-next-line no-undef
          _.each(this.currentFormulator, (value, key) => {
            if (
              !(
                !this.currentFormulator.isCertified &&
                (key === 'date_start' || key === 'membership_status')
              )
            ) {
              formData.append(key, value);
            }
          });

          formulatorResource
            .store(formData)
            .then((response) => {
              if (response.error) {
                this.$message({
                  message: response.error,
                  type: 'error',
                  duration: 5 * 1000,
                });
                this.loadingSubmit = false;

                return false;
              }

              this.$message({
                message:
                  'Penyusun Baru atas nama ' +
                  this.currentFormulator.name +
                  ' Berhasil Dibuat',
                type: 'success',
                duration: 5 * 1000,
              });
              this.currentFormulator = {};
              this.$router.push('/master-data/formulator');
            })
            .catch((error) => {
              console.log(error);
            });
          this.loadingSubmit = false;
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    handleUploadSertifikat(file, fileList) {
      if (file.raw.size > 5242880) {
        this.showFileAlert('5');
        return;
      }

      this.sertifikatFileName = file.name;
      this.currentFormulator.file_sertifikat = file.raw;
    },
    handleUploadCv(file, fileList) {
      if (file.raw.size > 1048576) {
        this.showFileAlert('1');
        return;
      }

      this.cvFileName = file.name;
      this.currentFormulator.cv_penyusun = file.raw;
    },
    handleChangeExpertise(data) {
      if (data !== 'Ahli Lainnya') {
        this.currentFormulator.expertise = data;
      } else {
        this.currentFormulator.expertise = null;
      }
    },
    handleChangeIsCertified(data) {
      if (!data) {
        this.currentFormulator.date_start = null;
        this.currentFormulator.file_sertifikat = null;
        this.sertifikatFileName = null;
        this.currentFormulator.membership_status = null;
      }
    },
    showFileAlert(val) {
      this.$alert(`File Yang Diupload Melebihi ${val} MB`, {
        confirmButtonText: 'OK',
      });
    },
  },
};
</script>
