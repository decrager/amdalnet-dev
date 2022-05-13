<!-- eslint-disable vue/html-indent -->
<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <div class="filter-container">
        <el-button
          class="filter-item"
          type="primary"
          icon="el-icon-plus"
          @click="handleShowForm('create')"
        >
          {{ 'Tambah' }}
        </el-button>
        <el-row :gutter="32">
          <el-col :sm="24" :md="10">
            <el-input
              v-model="listQuery.search"
              suffix-icon="el-icon search"
              placeholder="Pencarian institusi pemerintah..."
              @input="inputSearch"
            >
              <el-button
                slot="append"
                icon="el-icon-search"
                @click="handleSearch"
              />
            </el-input>
          </el-col>
        </el-row>
      </div>
      <GovernmentInstitutionTable
        :list="list"
        :loading="loading"
        @handleEdit="handleEdit($event)"
        @handleDelete="handleDelete($event)"
      />
      <pagination
        v-show="total > 0"
        :total="total"
        :page.sync="listQuery.page"
        :limit.sync="listQuery.limit"
        @pagination="handleFilter"
      />
    </el-card>
    <el-dialog :title="dialogForm.title" :visible.sync="dialogForm.show">
      <el-row :gutter="32">
        <el-col :md="24" :sm="24">
          <div class="form-group">
            <label>Jenis Instansi<small style="color: red">*</small></label>
            <el-select
              v-model="currentData.institutionType"
              :class="{ 'is-error': errors.institutionType }"
              style="width: 100%"
            >
              <el-option
                v-for="item in institutionType"
                :key="item.name"
                :label="item.name"
                :value="item.name"
              />
            </el-select>
            <small v-if="errors.institutionType" style="color: #f56c6c">
              Jenis Usaha Wajib Diisi
            </small>
          </div>
        </el-col>
        <el-col
          v-if="
            currentData.institutionType === 'Pemerintah Provinsi' ||
            currentData.institutionType === 'Pemerintah Kabupaten/Kota'
          "
          :md="currentData.institutionType === 'Pemerintah Provinsi' ? 24 : 12"
          :sm="24"
        >
          <div class="form-group">
            <label>Provinsi<small style="color: red">*</small></label>
            <el-select
              v-model="currentData.id_province"
              :filterable="true"
              :class="{ 'is-error': errors.id_province }"
              style="width: 100%"
              @change="setDistricts"
            >
              <el-option
                v-for="item in provinces"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
            <small v-if="errors.id_province" style="color: #f56c6c">
              Provinsi Wajib Dipilih
            </small>
          </div>
        </el-col>
        <el-col
          v-if="currentData.institutionType === 'Pemerintah Kabupaten/Kota'"
          :md="12"
          :sm="24"
        >
          <div class="form-group">
            <label>Kabupaten/Kota<small style="color: red">*</small></label>
            <el-select
              v-model="currentData.id_district"
              :filterable="true"
              :class="{ 'is-error': errors.id_district }"
              style="width: 100%"
            >
              <el-option
                v-for="item in filteredDistricts"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
            <small v-if="errors.id_district" style="color: #f56c6c">
              Kabupaten/Kota Wajib Dipilih
            </small>
          </div>
        </el-col>
        <el-col :md="24" :sm="24">
          <div class="form-group">
            <label>Nama Instansi<small style="color: red">*</small></label>
            <el-input
              v-model="currentData.name"
              :class="{ 'is-error': errors.name }"
            />
            <small v-if="errors.institutionType" style="color: #f56c6c">
              Nama Instansi Wajib Diisi
            </small>
          </div>
        </el-col>
        <el-col :md="24" :sm="24">
          <div class="form-group">
            <label>Email Instansi<small style="color: red">*</small></label>
            <el-input
              v-model="currentData.email"
              :class="{ 'is-error': errors.email }"
            />
            <small v-if="errors.email" style="color: #f56c6c">
              {{ errors.email }}
            </small>
          </div>
        </el-col>
        <el-col :md="24" align="right">
          <el-button
            type="warning"
            :loading="loadingSubmit"
            @click="checkSubmit"
          >
            Simpan
          </el-button>
        </el-col>
      </el-row>
    </el-dialog>
  </div>
</template>

<script>
import GovernmentInstitutionTable from '@/views/government-institution/components/GovernmentInstitutionTable.vue';
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
const provinceResource = new Resource('provinces');
const districtResource = new Resource('districts');
const governmentInstitutionResource = new Resource('government-institution');

export default {
  name: 'GovernmentInstitution',
  components: {
    GovernmentInstitutionTable,
    Pagination,
  },
  data() {
    return {
      list: [],
      loading: false,
      loadingSubmit: false,
      listQuery: {
        page: 1,
        limit: 10,
        search: null,
      },
      timeoutId: null,
      total: 0,
      dialogForm: {
        show: false,
        type: '',
        title: '',
      },
      provinces: [],
      districts: [],
      filteredDistricts: [],
      currentData: {},
      errors: {
        institutionType: null,
        id_province: null,
        id_district: null,
        name: null,
        email: null,
      },
      institutionType: [
        {
          name: 'Kementerian',
        },
        {
          name: 'Lembaga',
        },
        {
          name: 'Pemerintah Provinsi',
        },
        {
          name: 'Pemerintah Kabupaten/Kota',
        },
        {
          name: 'Lainnya',
        },
      ],
    };
  },
  created() {
    this.getData();
    this.getProvince();
    this.getDistrict();
  },
  methods: {
    handleFilter() {
      this.getData();
    },
    async getData() {
      this.loading = true;
      const { data, total } = await governmentInstitutionResource.list(
        this.listQuery
      );
      this.list = data;
      this.total = total;
      this.loading = false;
    },
    async getProvince() {
      const provinces = await provinceResource.list({});
      this.provinces = provinces.data;
    },
    async getDistrict() {
      const districts = await districtResource.list({});
      this.districts = districts.data;
    },
    checkSubmit() {
      this.resetErrors();

      let errors = 0;

      if (!this.currentData.institutionType) {
        errors++;
        this.errors.institutionType = true;
      }

      if (!this.currentData.name) {
        errors++;
        this.errors.name = true;
      }

      if (!this.currentData.email) {
        errors++;
        this.errors.email = 'Email Wajib Diisi';
      } else {
        if (!this.validateEmail(this.currentData.email)) {
          errors++;
          this.errors.email = 'Email Tidak Valid';
        }
      }

      if (
        this.currentData.institutionType === 'Pemerintah Provinsi' ||
        this.currentData.institutionType === 'Pemerintah Kabupaten/Kota'
      ) {
        if (!this.currentData.id_province) {
          errors++;
          this.errors.id_province = true;
        }
      }

      if (this.currentData.institutionType === 'Pemerintah Kabupaten/Kota') {
        if (!this.currentData.id_district) {
          errors++;
          this.errors.id_district = true;
        }
      }

      if (errors === 0) {
        this.handleSubmit();
      }
    },
    async handleSubmit() {
      this.loadingSubmit = true;
      await governmentInstitutionResource.store({
        ...this.currentData,
        type: this.dialogForm.type,
      });
      this.$message({
        message: 'Data berhasil disimpan',
        type: 'success',
        duration: 5 * 1000,
      });
      this.getData();
      this.dialogForm.show = false;
      this.loadingSubmit = false;
    },
    handleShowForm(act) {
      this.resetErrors();

      if (act === 'create') {
        this.currentData = {};
        this.filteredDistricts = [];
        this.dialogForm.title = 'Tambah Instansi Pemerintah';
      } else {
        this.dialogForm.title = 'Ubah Instansi Pemerintah';
        if (this.currentData.institutionType === 'Pemerintah Kabupaten/Kota') {
          this.setDistricts(this.currentData.id_province);
        }
      }

      this.dialogForm.type = act;
      this.dialogForm.show = true;
    },
    setDistricts(id) {
      const filteredDistricts = this.districts.filter((x) => x.id_prov === id);
      this.filteredDistricts = filteredDistricts;
    },
    handleEdit({ id }) {
      const institution = this.list.find((x) => x.id === id);
      this.currentData = {
        id: institution.id,
        name: institution.name,
        institutionType: institution.institution_type,
        id_province: institution.id_province,
        id_district: institution.id_district,
        email: institution.email,
        old_email: institution.email,
      };
      this.handleShowForm('update');
    },
    handleDelete({ id, name }) {
      this.$confirm(
        'apakah anda yakin akan menghapus ' + name + '. ?',
        'Peringatan',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Batal',
          type: 'warning',
        }
      )
        .then(() => {
          governmentInstitutionResource
            .destroy(id)
            .then((response) => {
              this.$message({
                type: 'success',
                message: 'Hapus Selesai',
              });
              this.getData();
            })
            .catch((error) => {
              console.log(error);
            });
        })
        .catch(() => {
          this.$message({
            type: 'info',
            message: 'Hapus Digagalkan',
          });
        });
    },
    resetErrors() {
      this.errors = {
        institutionType: null,
        id_province: null,
        id_district: null,
        name: null,
        email: null,
      };
    },
    validateEmail(mail) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(mail);
    },
    async handleSearch() {
      this.listQuery.page = 1;
      this.listQuery.limit = 10;
      await this.getData();
      this.listQuery.search = null;
    },
    inputSearch(val) {
      if (this.timeoutId) {
        clearTimeout(this.timeoutId);
      }
      this.timeoutId = setTimeout(() => {
        this.listQuery.page = 1;
        this.listQuery.limit = 10;
        this.getData();
      }, 500);
    },
  },
};
</script>

<style scoped>
.form-group {
  margin-top: 10px;
  margin-bottom: 10px;
  padding: 0 10px;
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
