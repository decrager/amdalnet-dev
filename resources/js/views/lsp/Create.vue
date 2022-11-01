<template>
  <div class="form-container" style="padding: 24px">
    <el-card>
      <el-form
        ref="lspForm"
        :model="currentLsp"
        :rules="lspRules"
        label-position="top"
        label-width="200px"
        style="max-width: 100%"
      >
        <el-row :gutter="32">
          <el-col :span="12">
            <el-form-item label="Nama LSP" prop="lsp_name">
              <el-input
                v-model="currentLsp.lsp_name"
                name="lsp_name"
                placeholder="Nama LSP"
              />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="Nomor Lisensi LSP" prop="license_no">
              <el-input
                v-model="currentLsp.license_no"
                name="license_no"
                placeholder="Nomor Lisensi LSP"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="32">
          <el-col :span="12">
            <el-form-item label="Alamat LSP" prop="address">
              <el-input
                v-model="currentLsp.address"
                name="address"
                placeholder="Alamat LSP"
              />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="Nomor SK" prop="sk_no">
              <el-input
                v-model="currentLsp.sk_no"
                name="sk_no"
                placeholder="Nomor SK"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="32">
          <el-col :span="12">
            <el-form-item label="Provinsi" prop="province">
              <el-select
                v-model="currentLsp.province"
                name="province"
                placeholder="Provinsi"
                clearable
                class="filter-item"
                style="width: 100%"
                @change="changeProvince($event)"
              >
                <el-option
                  v-for="item in provinceOptions"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="Kota" prop="city">
              <el-select
                v-model="currentLsp.city"
                name="city"
                placeholder="Kota"
                clearable
                class="filter-item"
                style="width: 100%"
                @change="changeProvince($event)"
              >
                <el-option
                  v-for="item in cityOptions"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                  :disabled="cityOptions.length == 0"
                />
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="32">
          <el-col :span="12">
            <el-form-item label="Nomor Telepon" prop="phone_no">
              <el-input
                v-model="currentLsp.phone_no"
                name="phone_no"
                placeholder="Nomor Telepon"
              />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="Email" prop="email">
              <el-input
                v-model="currentLsp.email"
                name="email"
                placeholder="Email"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="32">
          <el-col :span="12">
            <el-form-item label="Unggah Bukti Penetapan LSP" prop="lsp_file">
              <div class="upload-form-item" style="display: flex">
                <el-upload
                  class="upload-demo"
                  name="lsp_file"
                  :auto-upload="false"
                  :on-change="handleUploadChange"
                  action="#"
                  :show-file-list="false"
                >
                  <el-button size="small" type="primary">
                    Click to upload
                  </el-button>
                  <div slot="tip" class="el-upload__tip">
                    upload disini
                  </div>
                </el-upload>
                <span v-if="fileName" style="padding-left: 10px">{{
                  certificateName
                }}</span>
                <a
                  v-else-if="currentLsp.lsp_file"
                  :href="currentLsp.lsp_file"
                  style="padding-left: 10px"
                  target="_blank"
                >
                  {{ certificateName }}
                </a>
              </div>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="32">
          <el-col :span="12">
            <el-row :gutter="8">
              <el-col :span="12">
                <el-form-item label="Tanggal Ditetapkan" prop="date_lsp_start">
                  <el-date-picker
                    v-model="currentLsp.date_lsp_start"
                    type="date"
                    name="date_lsp_start"
                    placeholder="yyyy-MM-dd"
                    value-format="yyyy-MM-dd"
                    style="width: 100%"
                  />
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item label="Tanggal Terakhir Berlaku" prop="date_lsp_end">
                  <el-date-picker
                    v-model="currentLsp.date_lsp_end"
                    type="date"
                    name="date_lsp_end"
                    placeholder="yyyy-MM-dd"
                    value-format="yyyy-MM-dd"
                    style="width: 100%"
                  />
                </el-form-item>
              </el-col>
            </el-row>
          </el-col>
        </el-row>
      </el-form>
      <div class="dialog-footer" style="text-align: right">
        <el-button @click="handleCancel"> Batal </el-button>
        <el-button type="primary" @click="handleSubmit"> Simpan </el-button>
      </div>
    </el-card>
  </div>
</template>

<script>
import { validEmail } from '@/utils/validate';
import Resource from '@/api/resource';
const lspResource = new Resource('lsp');
const provinceResource = new Resource('provinces');
const districtResource = new Resource('districts');

export default {
  name: 'CreateLsp',
  props: {
    lsp: {
      type: Object,
      default: null,
    },
  },
  data() {
    const validateEmail = (rule, value, callback) => {
      if (value) {
        if (!validEmail(value)) {
          callback(new Error('Email Tidak Valid'));
          return;
        } else {
          callback();
          return;
        }
      }

      callback(new Error('Email Wajib Diisi'));
    };

    const validateCertificate = (rule, value, callback) => {
      if (!this.fileUpload) {
        callback(new Error('Sertifikat Wajib Diunggah'));
      } else {
        callback();
      }
    };

    return {
      currentLsp: {},
      fileUpload: null,
      fileName: null,
      provinceOptions: [],
      cityOptions: [],
      lspRules: {
        lsp_name: [
          { required: true, trigger: 'blur', message: 'Nama Wajib Diisi' },
        ],
        license_no: [
          { required: true, trigger: 'blur', message: 'Lisensi LSP Wajib Diisi' },
        ],
        address: [
          {
            required: true,
            trigger: 'blur',
            message: 'Alamat Wajib Diisi',
          },
        ],
        sk_no: [
          {
            required: true,
            trigger: 'blur',
            message: 'Nomor SK Wajib Diisi',
          },
        ],
        province: [
          {
            required: true,
            trigger: 'blur',
            message: 'Provinsi Wajib Dipilih',
          },
        ],
        city: [
          {
            required: true,
            trigger: 'blur',
            message: 'Kota/Kabupaten Wajib Dipilih',
          },
        ],
        phone_no: [
          {
            required: true,
            trigger: 'blur',
            message: 'Nomor Telepon Wajib Diisi',
          },
        ],
        email: [
          {
            required: true,
            message: 'Email Wajib Diisi',
            trigger: 'blur',
            validator: validateEmail,
          },
        ],
        lsp_file: [
          {
            required: this.$route.name === 'createLsp',
            trigger: 'blur',
            validator:
              this.$route.name === 'createLsp' ? validateCertificate : null,
          },
        ],
        date_lsp_start: [
          {
            required: true,
            trigger: 'blur',
            message: 'Tanggal Ditetapkan Wajib Diisi',
          },
        ],
        date_lsp_end: [
          {
            required: true,
            trigger: 'blur',
            message: 'Tanggal Terakhir Berlaku Wajib Diisi',
          },
        ],
      },
    };
  },
  computed: {
    certificateName() {
      if (this.fileName) {
        return this.fileName;
      }

      if (this.currentLsp.lsp_file) {
        return 'Sertifikat';
      }

      return '';
    },
  },
  created() {
    if (this.$route.params.lsp) {
      this.currentLsp = this.$route.params.lsp;
    }

    this.getProvinces();
  },
  methods: {
    async changeProvince(value) {
      // change all district by province
      this.getDistricts(value);
    },
    async getProvinces() {
      const { data } = await provinceResource.list({});
      this.provinceOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
      if (this.currentLsp.province) {
        this.getDistricts(this.currentLsp.province);
      }
    },
    async getDistricts(idProv) {
      const { data } = await districtResource.list({ idProv });
      this.cityOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },
    handleCancel() {
      this.$router.push({ name: 'lsp' });
    },
    handleUploadChange(file, fileList) {
      this.fileName = file.name;
      this.fileUpload = file.raw;
    },
    handleSubmit() {
      this.$refs.lspForm.validate((valid) => {
        if (valid) {
          this.currentLsp.file = this.fileUpload;

          // make form data because we got file
          const formData = new FormData();

          // eslint-disable-next-line no-undef
          _.each(this.currentLsp, (value, key) => {
            formData.append(key, value);
          });

          if (this.currentLsp.id !== undefined) {
            lspResource
              .updateMultipart(this.currentLsp.id, formData)
              .then((response) => {
                if (response.errors) {
                  this.$message({
                    message: response.errors,
                    type: 'error',
                    duration: 5 * 1000,
                  });
                } else {
                  this.$message({
                    type: 'success',
                    message: 'LSP berhasil diupdate',
                    duration: 5 * 1000,
                  });
                  this.$router.push('/master-data/lsp');
                }
              })
              .catch((error) => {
                console.log(error);
              });
          } else {
            lspResource
              .store(formData)
              .then((response) => {
                if (response.errors) {
                  this.$message({
                    message: response.errors,
                    type: 'error',
                    duration: 5 * 1000,
                  });
                } else {
                  this.$message({
                    message:
                      'LSP ' +
                      this.currentLsp.nama +
                      ' berhasil ditambahkan.',
                    type: 'success',
                    duration: 5 * 1000,
                  });
                  this.currentLsp = {};
                  this.$router.push('/master-data/lsp');
                }
              })
              .catch((error) => {
                console.log(error);
              });
          }
        } else {
          return false;
        }
      });
    },
  },
};
</script>
