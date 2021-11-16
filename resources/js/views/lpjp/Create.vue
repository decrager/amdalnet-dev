<template>
  <div class="form-container" style="padding: 24px">
    <el-form
      ref="categoryForm"
      :model="currentLpjp"
      label-position="top"
      label-width="200px"
      style="max-width: 100%"
    >
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="Nama LPJP" prop="namaLpjp">
            <el-input v-model="currentLpjp.name" placeholder="Nama LPJP" />
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="PIC LPJP" prop="picLpjp">
            <el-input v-model="currentLpjp.pic" placeholder="PIC LPJP" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="No. Registrasi LPJP" prop="nomorRegistrasi">
            <el-input
              v-model="currentLpjp.reg_no"
              placeholder="No. Registrasi LPJP"
            />
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Alamat LPJP" prop="alamatLpjp">
            <el-input v-model="currentLpjp.address" placeholder="Alamat LPJP" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="Provinsi" prop="provinsi">
            <el-select
              v-model="currentLpjp.id_prov"
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
          <el-form-item label="Kota" prop="kota">
            <el-select
              v-model="currentLpjp.id_district"
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
          <el-form-item label="No. Telp" prop="noTelp">
            <el-input
              v-model="currentLpjp.mobile_phone_no"
              placeholder="No. Telp"
            />
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Email" prop="email">
            <el-input v-model="currentLpjp.email" placeholder="Email" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="Dokumen Sertifikat" prop="fileSertifikat">
            <!-- <input type="file" @change="handleFileObject"> -->
            <el-upload
              class="upload-demo"
              :auto-upload="false"
              :on-change="handleUploadChange"
              action="#"
              :show-file-list="false"
            >
              <el-button size="small" type="primary">
                Click to upload
              </el-button>
              <span style="padding-left: 10px">{{
                fileName || currentLpjp.cert_file
              }}</span>
              <div slot="tip" class="el-upload__tip">
                upload sertifikat disini
              </div>
            </el-upload>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-row :gutter="8">
            <el-col :span="12">
              <el-form-item label="Tgl Ditetapkan" prop="tglAwal">
                <el-date-picker
                  v-model="currentLpjp.date_start"
                  type="date"
                  placeholder="yyyy-MM-dd"
                  value-format="yyyy-MM-dd"
                  style="width: 100%"
                />
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="Terakhir Berlaku" prop="terakhirBerlaku">
                <el-date-picker
                  v-model="currentLpjp.date_end"
                  type="date"
                  placeholder="yyyy-MM-dd"
                  value-format="yyyy-MM-dd"
                  style="width: 100%"
                />
              </el-form-item>
            </el-col>
          </el-row>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Contact Person" prop="contactPerson">
            <el-input
              v-model="currentLpjp.contact_person"
              placeholder="Contact Person"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :span="12">
          <el-form-item label="No. HP" prop="noHp">
            <el-input v-model="currentLpjp.phone_no" placeholder="No. Hp" />
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Link Web" prop="linkWeb">
            <el-input
              v-model="currentLpjp.url_address"
              placeholder="http://braindevs.com"
            />
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
    return {
      currentLpjp: {},
      fileUpload: null,
      fileName: null,
      provinceOptions: [],
      cityOptions: [],
    };
  },
  mounted() {
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
    },
    async getDistricts(idProv) {
      const { data } = await districtResource.list({ idProv });
      this.cityOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },
    handleCancel() {
      this.$router.push('/master/lpjp');
    },
    handleUploadChange(file, fileList) {
      this.fileName = file.name;
      this.fileUpload = file.raw;
    },
    handleSubmit() {
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
            this.$message({
              type: 'success',
              message: 'LPJP Details has been updated successfully',
              duration: 5 * 1000,
            });
            this.$router.push('/master-data/lpjp');
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        lpjpResource
          .store(formData)
          .then((response) => {
            this.$message({
              message:
                'New LPJP ' +
                this.currentLpjp.nama +
                ' has been created successfully.',
              type: 'success',
              duration: 5 * 1000,
            });
            this.currentLpjp = {};
            this.$router.push('/master-data/lpjp');
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
  },
};
</script>
