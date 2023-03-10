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
    </div>
    <el-table
      v-loading="loading"
      :data="filtered"
      border
      fit
      highlight-current-row
      style="width: 100%"
    >
      <el-table-column align="center" label="No. Registrasi" width="200px">
        <template slot-scope="scope">
          <span>{{ scope.row.reg_no + '1233DD21123ASD' }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Nama Kegiatan" width="200px">
        <template slot-scope="scope">
          <span>{{ scope.row.project_title }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Jenis Dokumen" width="200px">
        <template slot-scope="scope">
          <span>{{ scope.row.required_doc }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Deskripsi Kegiatan" width="250px">
        <template slot-scope="scope">
          <span v-html="scope.row.description" />
        </template>
      </el-table-column>
      <el-table-column align="center" label="Provinsi" width="100px">
        <template slot-scope="scope">
          <span>{{ scope.row.province }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Kab/Kota" width="100px">
        <template slot-scope="scope">
          <span>{{ scope.row.district }}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Tanggal Buat" width="200px">
        <template slot-scope="scope">
          <span>{{
            scope.row.created_at | parseTime('{y}-{m}-{d} {h}:{i}')
          }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Tahap" class-name="status-col" width="100">
        <template slot-scope="scope">
          <el-tag
            :type="scope.row.tag === 'Home' ? 'primary' : 'success'"
            disable-transitions
          >{{ scope.row.tag }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column align="center" label="Aksi" fixed="right" width="150px">
        <template slot-scope="scope">
          <el-row style="margin-bottom: 4px"><el-button
            type="primary"
            size="mini"
            icon="form"
            @click="handleEditForm(scope.row.id)"
          >
            Workspace
          </el-button></el-row>
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
const announcementResource = new Resource('announcements');

export default {
  name: 'WorkspaceList',
  components: { Pagination },
  data() {
    return {
      loading: false,
      filtered: [],
      announcement: {},
      show: false,
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
    handleSubmitAnnouncement(fileProof){
      this.announcement.fileProof = fileProof;
      console.log(this.announcement);

      // make form data because we got file
      const formData = new FormData();

      // eslint-disable-next-line no-undef
      _.each(this.announcement, (value, key) => {
        formData.append(key, value);
      });

      announcementResource
        .store(formData)
        .then((response) => {
          this.$message({
            message:
                'Project ' +
                this.announcement.project_type +
                ' has been Published.',
            type: 'success',
            duration: 5 * 1000,
          });
          this.getFiltered(this.listQuery);
          this.show = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    handleCancelAnnouncement(){
      this.show = false;
    },
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
      const { data, total } = await projectResource.list(query);
      this.filtered = data;
      this.total = total;
      this.listLoading = false;
    },
    download(url) {
      window.open(url, '_blank').focus();
    },
    handleEditForm(id) {
      const currentProject = this.filtered.find((item) => item.id === id);

      this.$router.push({
        name: 'editWorkspace',
        params: { project: currentProject },
      });
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

