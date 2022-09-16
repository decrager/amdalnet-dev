<template>
  <div class="form-container" style="padding: 24px">
    <el-card>
      <el-form
        ref="lpjpForm"
        :model="currentLpjp"
        :rules="lpjpRules"
        label-position="top"
        label-width="200px"
        style="max-width: 100%"
      >
        <el-row :gutter="32">
          <el-col :span="12">
            <el-form-item label="Nama LPJP" prop="name">
              <el-input
                v-model="currentLpjp.name"
                name="name"
                placeholder="Nama LPJP"
              />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="PIC LPJP" prop="pic">
              <el-input
                v-model="currentLpjp.pic"
                name="pic"
                placeholder="PIC LPJP"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="32">
          <el-col :span="12">
            <el-form-item label="No. Registrasi LPJP" prop="reg_no">
              <el-input
                v-model="currentLpjp.reg_no"
                name="reg_no"
                placeholder="No. Registrasi LPJP"
              />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="Alamat LPJP" prop="address">
              <el-input
                v-model="currentLpjp.address"
                name="address"
                placeholder="Alamat LPJP"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="32">
          <el-col :span="12">
            <el-form-item label="Provinsi" prop="id_prov">
              <el-select
                v-model="currentLpjp.id_prov"
                name="id_prov"
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
            <el-form-item label="Kota" prop="id_district">
              <el-select
                v-model="currentLpjp.id_district"
                name="id_district"
                placeholder="Kota"
                style="width: 100%"
              >
                <el-option
                  v-for="item in cityOptions"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                  :disabled="cityOptions.length == 0"
                />
              </el-select>
              <!-- <el-input v-model="currentLpjp.kota" /> -->
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="32">
          <el-col :span="12">
            <el-form-item label="No. Telp" prop="mobile_phone_no">
              <el-input
                v-model="currentLpjp.mobile_phone_no"
                name="mobile_phone_no"
                placeholder="No. Telp"
              />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="Email" prop="email">
              <el-input
                v-model="currentLpjp.email"
                name="email"
                placeholder="Email"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="32">
          <el-col :span="12">
            <el-form-item label="Dokumen Sertifikat" prop="certificate">
              <!-- <input type="file" @change="handleFileObject"> -->
              <div class="upload-form-item" style="display: flex">
                <el-upload
                  class="upload-demo"
                  name="certificate"
                  :auto-upload="false"
                  :on-change="handleUploadChange"
                  action="#"
                  :show-file-list="false"
                >
                  <el-button size="small" type="primary">
                    Click to upload
                  </el-button>
                  <div slot="tip" class="el-upload__tip">
                    upload sertifikat disini
                  </div>
                </el-upload>
                <span v-if="fileName" style="padding-left: 10px">{{
                  certificateName
                }}</span>
                <a
                  v-else-if="currentLpjp.cert_file"
                  :href="currentLpjp.cert_file"
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
                <el-form-item label="Tgl Ditetapkan" prop="date_start">
                  <el-date-picker
                    v-model="currentLpjp.date_start"
                    type="date"
                    name="date_start"
                    placeholder="yyyy-MM-dd"
                    value-format="yyyy-MM-dd"
                    style="width: 100%"
                  />
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item label="Terakhir Berlaku" prop="date_end">
                  <el-date-picker
                    v-model="currentLpjp.date_end"
                    type="date"
                    name="date_end"
                    placeholder="yyyy-MM-dd"
                    value-format="yyyy-MM-dd"
                    style="width: 100%"
                  />
                </el-form-item>
              </el-col>
            </el-row>
          </el-col>
          <el-col :span="12">
            <el-form-item label="Contact Person" prop="contact_person">
              <el-input
                v-model="currentLpjp.contact_person"
                name="contact_person"
                placeholder="Contact Person"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="32">
          <el-col :span="12">
            <el-form-item label="No. HP" prop="phone_no">
              <el-input
                v-model="currentLpjp.phone_no"
                name="phone_No"
                placeholder="No. Hp"
              />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="Link Web" prop="url_address">
              <el-input
                v-model="currentLpjp.url_address"
                name="urlAddress"
                placeholder="http://braindevs.com"
              />
            </el-form-item>
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
const lpjpResource = new Resource('lpjp');
const provinceResource = new Resource('provinces');
const districtResource = new Resource('districts');

export default {
  name: 'CreateLpjp',
  props: {
    lpjp: {
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
      currentLpjp: {},
      fileUpload: null,
      fileName: null,
      provinceOptions: [],
      cityOptions: [],
      lpjpRules: {
        name: [
          { required: true, trigger: 'blur', message: 'Nama Wajib Diisi' },
        ],
        pic: [{ required: true, trigger: 'blur', message: 'PIC Wajib Diisi' }],
        reg_no: [
          {
            required: true,
            trigger: 'blur',
            message: 'No. Registrasi Wajib Diisi',
          },
        ],
        address: [
          {
            required: true,
            trigger: 'blur',
            message: 'Alamat Wajib Diisi',
          },
        ],
        id_prov: [
          {
            required: true,
            trigger: 'blur',
            message: 'Provinsi Wajib Dipilih',
          },
        ],
        id_district: [
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
            message: 'No. HP Wajib Diisi',
          },
        ],
        email: [
          {
            trigger: 'blur',
            validator: validateEmail,
          },
        ],
        certificate: [
          {
            required: this.$route.name === 'createLpjp',
            trigger: 'blur',
            validator:
              this.$route.name === 'createLpjp' ? validateCertificate : null,
          },
        ],
        date_start: [
          {
            required: true,
            trigger: 'blur',
            message: 'Tanggal Ditetapkan Wajib Diisi',
          },
        ],
        date_end: [
          {
            required: true,
            trigger: 'blur',
            message: 'Tanggal Terakhir Berlaku Wajib Diisi',
          },
        ],
        contact_person: [
          {
            required: true,
            trigger: 'blur',
            message: 'Contact Person Wajib Diisi',
          },
        ],
        mobile_phone_no: [
          {
            required: true,
            trigger: 'blur',
            message: 'No. Telp Wajib Diisi',
          },
        ],
        url_address: [
          {
            required: true,
            trigger: 'blur',
            message: 'Link Web Wajib Diisi',
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

      if (this.currentLpjp.cert_file) {
        return 'Sertifikat';
      }

      return '';
    },
  },
  created() {
    if (this.$route.params.lpjp) {
      this.currentLpjp = this.$route.params.lpjp;
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
      if (this.currentLpjp.id_prov) {
        this.getDistricts(this.currentLpjp.id_prov);
      }
    },
    async getDistricts(idProv) {
      const { data } = await districtResource.list({ idProv });
      this.cityOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },
    handleCancel() {
      this.$router.push({ name: 'lpjp' });
    },
    handleUploadChange(file, fileList) {
      this.fileName = file.name;
      this.fileUpload = file.raw;
    },
    handleSubmit() {
      this.$refs.lpjpForm.validate((valid) => {
        if (valid) {
          this.currentLpjp.file = this.fileUpload;

          // make form data because we got file
          const formData = new FormData();

          // eslint-disable-next-line no-undef
          _.each(this.currentLpjp, (value, key) => {
            formData.append(key, value);
          });

          if (this.currentLpjp.id !== undefined) {
            lpjpResource
              .updateMultipart(this.currentLpjp.id, formData)
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
                    message: 'LPJP berhasil diupdate',
                    duration: 5 * 1000,
                  });
                  this.$router.push('/master-data/lpjp');
                }
              })
              .catch((error) => {
                console.log(error);
              });
          } else {
            lpjpResource
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
                      'LPJP ' +
                      this.currentLpjp.nama +
                      ' berhasil ditambahkan.',
                    type: 'success',
                    duration: 5 * 1000,
                  });
                  this.currentLpjp = {};
                  this.$router.push('/master-data/lpjp');
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
