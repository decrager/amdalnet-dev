<template>
  <div style="padding: 24px" class="app-container">
    <div class="filter-container">
      <el-select
        v-model="listQuery.document_type"
        :placeholder="'Jenis Document'"
        clearable
        class="filter-item"
      >
        <el-option
          v-for="item in documentTypeOptions"
          :key="item.value"
          :label="item.label"
          :value="item.value"
        />
      </el-select>
      <el-select
        v-model="listQuery.id_prov"
        placeholder="Province"
        clearable
        class="filter-item"
        @change="changeProvince($event)"
      >
        <el-option
          v-for="item in provinceOptions"
          :key="item.value"
          :label="item.label"
          :value="item.value"
        />
      </el-select>
      <el-select
        v-model="listQuery.id_district"
        placeholder="Kab / Kota"
        clearable
        class="filter-item"
      >
        <el-option
          v-for="item in cityOptions"
          :key="item.value"
          :label="item.label"
          :value="item.value"
          :disabled="cityOptions.length == 0"
        />
      </el-select>
      <el-button
        class="filter-item"
        type="primary"
        icon="el-icon-search"
        @click="handleFilter"
      >
        {{ $t('table.search') }}
      </el-button>
      <el-button
        class="filter-item"
        type="primary"
        icon="el-icon-plus"
        @click="handleCreate"
      >
        {{ $t('table.add') + ' Kegiatan' }}
      </el-button>
    </div>
    <el-table
      v-loading="loading"
      :data="filtered"
      border
      fit
      highlight-current-row
    >
      <el-table-column align="center" label="No. Registrasi" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.reg_no + '1233DD21123ASD' }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Nama Kegiatan" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.project_title }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Jenis Dokumen" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.required_doc }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Provinsi" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.id_prov }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Kab/Kota" sortable>
        <template slot-scope="scope">
          <span>{{ scope.row.id_district }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Tanggal Input" sortable>
        <template slot-scope="scope">
          <span>{{
            scope.row.created_at | parseTime('{y}-{m}-{d} {h}:{i}')
          }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Aksi">
        <template slot-scope="scope">
          <el-button
            type="primary"
            size="mini"
            icon="el-icon-edit"
            @click="handleEditForm(scope.row.id)"
          >
            Edit
          </el-button>
          <el-button
            type="danger"
            size="mini"
            icon="el-icon-delete"
            @click="handleDelete(scope.row.id, scope.row.nama_penyusun)"
          >
            Delete
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <pagination
      v-show="total > 0"
      :total="total"
      :page.sync="listQuery.page"
      :limit.sync="listQuery.limit"
      @pagination="handleFilter"
    />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination';
const provinceResource = new Resource('provinces');
const districtResource = new Resource('districts');
const projectResource = new Resource('projects');

export default {
  name: 'Project',
  components: { Pagination },
  data() {
    return {
      loading: false,
      filtered: [],
      search: '',
      page: 1,
      pageSize: 4,
      total: 0,
      listQuery: {
        page: 1,
        limit: 10,
      },
      provinceOptions: [],
      cityOptions: [],
      documentTypeOptions: [
        { value: 'Tinggi', label: 'Amdal' },
        { value: 'Menengah Tinggi', label: 'UKL-UPL MT' },
        { value: 'Menengah Rendah', label: 'UKL-UPL MR' },
        { value: 'Rendah', label: 'SPPL' },
      ],
    };
  },
  mounted() {
    this.getProvinces();
    this.getFiltered(this.listQuery);
  },
  methods: {
    handleFilter() {
      this.getFiltered(this.listQuery);
    },
    async getProvinces() {
      const { data } = await provinceResource.list({});
      this.provinceOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },
    async getFiltered(query) {
      this.listLoading = true;
      const { data, meta } = await projectResource.list(query);
      this.filtered = data;
      this.total = meta.total;
      this.listLoading = false;
    },
    handleCreate() {
      this.$router.push({
        name: 'createProject',
        params: {},
      });
    },
    handleCurrentChange(val) {
      this.page = val;
    },
    download(url) {
      window.open(url, '_blank').focus();
    },
    handleEditForm(id) {
      const currentProject = this.filtered.find((item) => item.id === id);

      // change project_year to string
      currentProject.project_year = currentProject.project_year.toString();
      console.log(currentProject);
      this.$router.push({
        name: 'createProject',
        params: { project: currentProject },
      });
    },
    handleDelete(id, nama) {
      this.$emit('handleDelete', { id, nama });
    },
    async changeProvince(value) {
      // change all district by province
      this.getDistricts(value);
    },
    async getDistricts(idProv) {
      const { data } = await districtResource.list({ idProv });
      this.cityOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },
  },
};
</script>

