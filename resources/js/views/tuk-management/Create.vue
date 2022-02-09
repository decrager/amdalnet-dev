<template>
  <div class="app-container" style="padding: 24px">
    <el-card v-loading="loading">
      <el-row :gutter="32">
        <el-col :md="12" :sm="24">
          <div class="form-group">
            <el-radio-group v-model="authority" style="display: flex">
              <el-radio label="Pusat" :class="{ 'is-error': errors.authority }">
                Pusat
              </el-radio>
              <el-radio
                label="Provinsi"
                :class="{ 'is-error': errors.authority }"
              >
                Provinsi
              </el-radio>
              <el-radio
                label="Kabupaten/Kota"
                :class="{ 'is-error': errors.authority }"
              >
                Kabupaten/Kota
              </el-radio>
            </el-radio-group>
            <small v-if="errors.authority" style="color: #f56c6c">
              <span v-for="(error, index) in errors.authority" :key="index">{{
                error
              }}</span>
            </small>
          </div>
        </el-col>
      </el-row>
      <el-row
        v-if="
          currentData.authority === 'Provinsi' || currentData.authority === 'Kabupaten/Kota'
        "
        :gutter="32"
      >
        <el-col :md="12" :sm="24">
          <el-row :gutter="32">
            <el-col :md="12" :sm="24">
              <div class="form-group">
                <label>Pilih Provinsi</label>
                <el-select
                  v-model="currentData.id_province_name"
                  :filterable="true"
                  :class="{ 'is-error': errors.id_province_name }"
                  @change="teamName($event, 'province')"
                >
                  <el-option
                    v-for="item in provinces"
                    :key="item.id"
                    :label="item.name"
                    :value="item.id"
                  />
                </el-select>
                <small v-if="errors.id_province_name" style="color: #f56c6c">
                  <span
                    v-for="(error, index) in errors.id_province_name"
                    :key="index"
                  >
                    {{ error }}
                  </span>
                </small>
              </div>
            </el-col>
            <el-col
              v-if="currentData.authority === 'Kabupaten/Kota'"
              :md="12"
              :sm="24"
            >
              <div class="form-group">
                <label>Kabupaten/Kota</label>
                <el-select
                  v-model="currentData.id_district_name"
                  :filterable="true"
                  :class="{ 'is-error': errors.id_district_name }"
                  @change="teamName($event, 'district')"
                >
                  <el-option
                    v-for="item in authority_districts"
                    :key="item.id"
                    :label="item.name"
                    :value="item.id"
                  />
                </el-select>
                <small v-if="errors.id_district_name" style="color: #f56c6c">
                  <span
                    v-for="(error, index) in errors.id_district_name"
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
          <el-input v-model="name" :readonly="true" />
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :md="12" :sm="24">
          <div class="form-group">
            <label> Unggah Bukti Penetapan TUK </label>
            <el-row :gutter="32">
              <el-col :md="16" :sm="24">
                <el-input
                  v-model="currentData.assignment_number"
                  placeholder="Isi Nomor Penetapan"
                />
              </el-col>
              <el-col :md="8" :sm="24">
                <el-upload
                  action="#"
                  :auto-upload="false"
                  :on-change="handleUploadAssignment"
                  :show-file-list="false"
                >
                  <el-button size="small" type="primary"> Upload </el-button>
                  <div slot="tip" class="el-upload__tip">
                    {{ assignmentFileName }}
                  </div>
                </el-upload>
              </el-col>
            </el-row>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :md="12" :sm="24">
          <el-row :gutter="32">
            <el-col :md="12" :sm="24">
              <div class="form-group">
                <label>Nomor Kontak</label>
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
          </el-row>
        </el-col>
      </el-row>
      <!-- <el-row :gutter="32">
        <el-col :md="12" :sm="24">
          <el-row :gutter="32">
            <el-col :md="12" :sm="24">
              <div class="form-group">
                <label>Provinsi</label>
                <el-select
                  v-model="currentData.id_province"
                  :filterable="true"
                  :class="{ 'is-error': errors.id_province }"
                  @change="handleChangeProvince"
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
                    v-for="item in address_districts"
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
      </el-row> -->
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
        <el-col :md="12" :sm="24" align="right">
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
const tukManagementResource = new Resource('tuk-management');

export default {
  name: 'CreateEmployee',
  data() {
    return {
      currentData: {
        authority: null,
        email: null,
        phone: null,
        assignment_number: null,
        assignment_file: null,
        id_province: null,
        id_district: null,
        id_province_name: null,
        id_district_name: null,
        address: null,
        team_number: null,
      },
      authority_districts: [],
      address_districts: [],
      assignmentFileName: null,
      errors: {},
      provinces: [],
      districts: [],
      loadingSubmit: false,
      loading: false,
      name: null,
      authority: null,
    };
  },
  watch: {
    authority(next, prev) {
      if (prev !== null) {
        this.authority_districts = [];
        this.currentData.id_province_name = null;
        this.currentData.id_district_name = null;
        this.name = null;
      }
      this.currentData.authority = next;

      if (next === 'Pusat') {
        this.teamName(null, 'Pusat');
      }
    },
  },
  created() {
    if (this.$route.name === 'editTuk') {
      this.getData();
    } else {
      this.getProvince();
      this.getDistrict();
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

      if (this.$route.name === 'createTuk') {
        formData.append('type', 'create');
      } else {
        formData.append('type', 'update');
        formData.append('idTeam', this.$route.params.id);
      }

      const data = await tukManagementResource.store(formData);
      if (data.errors) {
        this.errors = data.errors;
      } else {
        this.$message({
          message: 'Data berhasil disimpan',
          type: 'success',
          duration: 5 * 1000,
        });
        this.$router.push({
          name: 'tuk',
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
      const provinces = await provinceResource.list({});
      this.provinces = provinces.data;
      const districts = await districtResource.list({});
      this.districts = districts.data;
      this.currentData = await tukManagementResource.list({
        type: 'edit',
        idTeam: this.$route.params.id,
      });
      this.currentData.assignment_file = null;
      const id_province_name = { ...this.currentData }.id_province_name;
      // const id_province = { ...this.currentData }.id_province;

      if (id_province_name) {
        this.currentData.id_province_name = id_province_name;
        this.authority_districts = this.districts.filter(x => x.id_prov === id_province_name);
      }
      // if (id_province) {
      //   const id_province = this.currentData.id_province;
      //   this.address_districts = this.districts.filter(x => x.id_prov === id_province);
      // }
      this.authority = this.currentData.authority;

      const dataType = this.currentData.authority === 'Provinsi' ? 'province' : null;

      this.teamName(id_province_name, dataType);

      this.loading = false;
    },
    handleUploadAssignment(file, fileList) {
      this.assignmentFileName = file.name;
      this.currentData.assignment_file = file.raw;
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
    teamName(id_province, dataType) {
      if (this.authority === 'Pusat') {
        this.id_district_name = null;
        this.id_province_name = null;
        this.name = 'Tim Uji Kelayakan Pusat';
      } else if (this.authority === 'Provinsi' && dataType === 'province') {
        if (this.currentData.id_province_name) {
          const provinceIndex = this.provinces.findIndex(
            (prov) => prov.id === this.currentData.id_province_name
          );
          this.name =
            'Tim Uji Kelayakan Provinsi ' +
            this.capitalize(this.provinces[provinceIndex].name);
        }
      } else if (this.authority === 'Kabupaten/Kota') {
        if (this.currentData.id_district_name) {
          const districtIndex = this.districts.findIndex(
            (dis) => dis.id === this.currentData.id_district_name
          );
          this.name =
            'Tim Uji Kelayakan ' +
            this.capitalize(this.districts[districtIndex].name);
        }
      } else {
        this.name = '';
      }

      if (dataType === 'province') {
        this.id_district_name = null;
        this.authority_districts = this.districts.filter(x => x.id_prov === id_province);
      }
    },
    handleChangeProvince(id_province) {
      this.address_districts = this.districts.filter(x => x.id_prov === id_province);
      this.currentData.id_district = null;
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
