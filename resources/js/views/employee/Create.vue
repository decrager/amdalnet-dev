<template>
  <div class="app-container" style="padding: 24px">
    <el-card v-loading="loading">
      <el-row :gutter="32">
        <el-col :md="12" :sm="24">
          <div class="form-group">
            <label>Status</label>
            <div style="display: flex">
              <el-radio
                v-model="currentData.status"
                label="PNS"
                :class="{ 'is-error': errors.status }"
              >
                PNS
              </el-radio>
              <el-radio
                v-model="currentData.status"
                label="Non PNS"
                :class="{ 'is-error': errors.status }"
              >
                Non PNS
              </el-radio>
            </div>
            <small v-if="errors.status" style="color: #f56c6c">
              <span v-for="(error, index) in errors.status" :key="index">{{
                error
              }}</span>
            </small>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :md="12" :sm="24">
          <div class="form-group">
            <label>NIK</label>
            <el-input
              v-model="currentData.nik"
              :class="{ 'is-error': errors.nik }"
            />
            <small v-if="errors.nik" style="color: #f56c6c">
              <span v-for="(error, index) in errors.nik" :key="index">{{
                error
              }}</span>
            </small>
          </div>
        </el-col>
        <el-col :md="12" :sm="24">
          <div class="form-group">
            <label>NIP</label>
            <el-input
              v-model="currentData.nip"
              :class="{ 'is-error': errors.nip }"
            />
            <small v-if="errors.nip" style="color: #f56c6c">
              <span v-for="(error, index) in errors.nip" :key="index">{{
                error
              }}</span>
            </small>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :md="12" :sm="24">
          <div class="form-group">
            <label>Nama</label>
            <el-input
              v-model="currentData.name"
              :class="{ 'is-error': errors.name }"
            />
            <small v-if="errors.name" style="color: #f56c6c">
              <span v-for="(error, index) in errors.name" :key="index">{{
                error
              }}</span>
            </small>
          </div>
        </el-col>
        <el-col :md="12" :sm="24">
          <div class="form-group">
            <label>Instansi</label>
            <el-input
              v-model="currentData.institution"
              :class="{ 'is-error': errors.institution }"
            />
            <small v-if="errors.institution" style="color: #f56c6c">
              <span v-for="(error, index) in errors.institution" :key="index">{{
                error
              }}</span>
            </small>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :md="12" :sm="24">
          <div class="form-group">
            <label>Email</label>
            <el-input
              v-model="currentData.email"
              :class="{ 'is-error': errors.email }"
            />
            <small v-if="errors.email" style="color: #f56c6c">
              <span v-for="(error, index) in errors.email" :key="index">{{
                error
              }}</span>
            </small>
          </div>
        </el-col>
        <el-col :md="12" :sm="24">
          <div class="form-group">
            <label>Jabatan</label>
            <el-input
              v-model="currentData.position"
              :class="{ 'is-error': errors.position }"
            />
            <small v-if="errors.position" style="color: #f56c6c">
              <span v-for="(error, index) in errors.position" :key="index">{{
                error
              }}</span>
            </small>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :md="12" :sm="24">
          <div class="form-group">
            <label>No. Telepon</label>
            <el-input
              v-model="currentData.phone"
              :class="{ 'is-error': errors.phone }"
            />
            <small v-if="errors.phone" style="color: #f56c6c">
              <span v-for="(error, index) in errors.phone" :key="index">{{
                error
              }}</span>
            </small>
          </div>
        </el-col>
        <el-col :md="12" :sm="24">
          <div class="form-group">
            <label>
              Unggah Surat Keputusan Pengangkatan Kelayakan Lingkungan Hidup
            </label>
            <el-row :gutter="32">
              <el-col :md="16" :sm="24">
                <el-input
                  v-model="currentData.decision_number"
                  placeholder="Isi Nomor Surat"
                />
              </el-col>
              <el-col :md="8" :sm="24">
                <el-upload
                  action="#"
                  :auto-upload="false"
                  :on-change="handleUploadDecision"
                  :show-file-list="false"
                >
                  <el-button size="small" type="primary"> Upload </el-button>
                  <div slot="tip" class="el-upload__tip">
                    {{ decisionFileName }}
                  </div>
                </el-upload>
              </el-col>
            </el-row>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :md="12" :sm="24">
          <div class="form-group">
            <label>Jenis Kelamin</label>
            <div style="display: flex">
              <el-radio
                v-model="currentData.sex"
                label="Laki-Laki"
                :class="{ 'is-error': errors.sex }"
              >
                Laki-Laki
              </el-radio>
              <el-radio
                v-model="currentData.sex"
                label="Perempuan"
                :class="{ 'is-error': errors.sex }"
              >
                Perempuan
              </el-radio>
            </div>
            <small v-if="errors.sex" style="color: #f56c6c">
              <span v-for="(error, index) in errors.sex" :key="index">{{
                error
              }}</span>
            </small>
          </div>
        </el-col>
        <el-col :md="12" :sm="24">
          <div class="form-group">
            <label>Unggah CV</label>
            <el-upload
              action="#"
              :auto-upload="false"
              :on-change="handleUploadCv"
              :show-file-list="false"
            >
              <el-button size="small" type="primary">Upload</el-button>
              <div slot="tip" class="el-upload__tip">
                {{ cvFileName }}
              </div>
            </el-upload>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :md="12" :sm="24">
          <el-row :gutter="32">
            <el-col :md="12" :sm="24">
              <div class="form-group">
                <label>Provinsi</label>
                <el-select
                  v-model="currentData.id_province"
                  :filterable="true"
                  :class="{ 'is-error': errors.id_province }"
                >
                  <el-option
                    v-for="item in provinces"
                    :key="item.id"
                    :label="item.name"
                    :value="item.id"
                  />
                </el-select>
                <small v-if="errors.id_province" style="color: #f56c6c">
                  <span
                    v-for="(error, index) in errors.id_province"
                    :key="index"
                  >
                    {{ error }}
                  </span>
                </small>
              </div>
            </el-col>
            <el-col :md="12" :sm="24">
              <div class="form-group">
                <label>Kabupaten/Kota</label>
                <el-select
                  v-model="currentData.id_district"
                  :filterable="true"
                  :class="{ 'is-error': errors.id_district }"
                >
                  <el-option
                    v-for="item in districts"
                    :key="item.id"
                    :label="item.name"
                    :value="item.id"
                  />
                </el-select>
                <small v-if="errors.id_district" style="color: #f56c6c">
                  <span
                    v-for="(error, index) in errors.id_district"
                    :key="index"
                  >
                    {{ error }}
                  </span>
                </small>
              </div>
            </el-col>
          </el-row>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :md="12" :sm="24">
          <div class="form-group">
            <label>Alamat</label>
            <el-input
              v-model="currentData.address"
              type="textarea"
              :rows="3"
              :class="{ 'is-error': errors.address }"
            />
            <small v-if="errors.address" style="color: #f56c6c">
              <span v-for="(error, index) in errors.address" :key="index">{{
                error
              }}</span>
            </small>
          </div>
        </el-col>
      </el-row>
      <el-row>
        <el-col :md="24" :sm="24" align="right">
          <el-button
            :loading="loadingSubmit"
            type="primary"
            @click="handleSubmit"
          >
            Simpan
          </el-button>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const provinceResource = new Resource('provinces');
const districtResource = new Resource('districts');
const employeeTukResource = new Resource('employee-tuk');

export default {
  name: 'CreateEmployee',
  data() {
    return {
      currentData: {
        status: null,
        nik: null,
        nip: null,
        name: null,
        institution: null,
        email: null,
        position: null,
        phone: null,
        decision_number: null,
        decision_file: null,
        sex: null,
        cv: null,
        id_province: null,
        id_district: null,
        address: null,
      },
      cvFileName: null,
      decisionFileName: null,
      errors: {},
      provinces: [],
      districts: [],
      loadingSubmit: false,
      loading: false,
    };
  },
  created() {
    this.getProvince();
    this.getDistrict();
    if (this.$route.name === 'editEmployeeTuk') {
      this.getData();
    }
  },
  methods: {
    async handleSubmit() {
      this.loadingSubmit = true;

      const formData = new FormData();

      // eslint-disable-next-line no-undef
      _.each(this.currentData, (value, key) => {
        if (value) {
          formData.append(key, value);
        }
      });

      if (this.$route.name === 'createEmployeeTuk') {
        formData.append('type', 'create');
      } else {
        formData.append('type', 'update');
        formData.append('idEmployee', this.$route.params.id);
      }

      const data = await employeeTukResource.store(formData);
      if (data.errors) {
        this.errors = data.errors;
      } else {
        this.$message({
          message: 'Data berhasil disimpan',
          type: 'success',
          duration: 5 * 1000,
        });
        this.$router.push({
          name: 'employeeTuk',
        });
      }

      this.loadingSubmit = false;
    },
    async getProvince() {
      const provinces = await provinceResource.list({});
      this.provinces = provinces.data;
    },
    async getDistrict() {
      const districts = await districtResource.list({});
      this.districts = districts.data;
    },
    async getData() {
      this.loading = true;
      this.currentData = await employeeTukResource.list({
        type: 'edit',
        idEmployee: this.$route.params.id,
      });
      this.currentData.cv = null;
      this.currentData.decision_file = null;
      this.loading = false;
    },
    handleUploadDecision(file, fileList) {
      this.decisionFileName = file.name;
      this.currentData.decision_file = file.raw;
    },
    handleUploadCv(file, fileList) {
      this.cvFileName = file.name;
      this.currentData.cv = file.raw;
    },
  },
};
</script>

<style scoped>
.form-group {
  margin-top: 10px;
  margin-bottom: 10px;
}
label {
  display: block;
  margin-bottom: 5px;
}
</style>

<style>
.is-error .el-input__inner,
.is-error .el-radio__inner,
.is-error .el-textarea__inner {
  border-color: #f56c6c;
}
</style>
