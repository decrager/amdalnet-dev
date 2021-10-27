<template>
  <div style="padding: 24px" class="app-container">
    <div class="filter-container">
      <el-row type="flex" class="row-bg" justify="space-between">
        <div>
          <el-button
            class="filter-item"
            type="primary"
            icon="el-icon-plus"
            @click="handleCreate"
          >
            {{ ' Kegiatan' }}
          </el-button>
        </div>
        <div>
          <el-select
            v-model="listQuery.document_type"
            :placeholder="'Jenis Dokumen'"
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
            placeholder="Provinsi"
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
      </el-row>
    </div>
    <el-table
      v-loading="loading"
      :data="filtered"
      border
      fit
      highlight-current-row
      :header-cell-style="{ background: '#3AB06F', color: 'white' }"
      style="width: 100%"
    >
      <el-table-column label="No." width="54px">
        <template slot-scope="scope">
          <span>{{ scope.$index + 1 }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="No. Registrasi" width="200px">
        <template slot-scope="scope">
          <span>{{ scope.row.project_title + '1233DD21123ASD' }}</span>
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
      <el-table-column align="center" label="Aksi" fixed="right" width="200px">
        <template slot-scope="scope">
          <el-row style="margin-bottom: 10px"><el-button
            v-if="!scope.row.published"
            size="mini"
            icon="el-icon-s-promotion"
            type="primary"
            @click="handlePublishForm(scope.row.id)"
          >
            Publikasi
          </el-button></el-row>
          <el-row style="margin-bottom: 4px"><el-button
                                               v-if="!scope.row.published"
                                               size="mini"
                                               icon="el-icon-view"
                                               style="background-color:blue; color:white;"
                                               @click="handleViewForm(scope.row.id)"
                                             >
                                               Lihat
                                             </el-button>
            <el-button
              v-if="!scope.row.published"
              type="warning"
              size="mini"
              icon="el-icon-edit"
              @click="handleEditForm(scope.row.id)"
            >
              Edit
            </el-button></el-row>
          <!-- <el-row><el-button
            v-if="!scope.row.published"
            type="danger"
            size="mini"
            icon="el-icon-delete"
            @click="handleDelete(scope.row.id, scope.row.project_title)"
          >
            Delete
          </el-button></el-row> -->

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
    <AnnouncementDialog
      :announcement="announcement"
      :show="show"
      @handleSubmitAnnouncement="handleSubmitAnnouncement($event)"
      @handleCancelAnnouncement="handleCancelAnnouncement"
    />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination';
import AnnouncementDialog from './components/AnnouncementDialog.vue';
const provinceResource = new Resource('provinces');
const districtResource = new Resource('districts');
const projectResource = new Resource('projects');
const announcementResource = new Resource('announcements');

export default {
  name: 'Project',
  components: { Pagination, AnnouncementDialog },
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
    handleCreate() {
      this.$router.push({
        name: 'createProject',
        params: {},
      });
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
    handleViewForm(id) {
      const currentProject = this.filtered.find((item) => item.id === id);

      // change project_year to string
      currentProject.project_year = currentProject.project_year.toString();
      this.$router.push({
        name: 'createProject',
        params: { project: currentProject },
      });
      this.$router.push({
        name: 'publishProject',
        params: { project: currentProject, readonly: true },
      });
    },
    handlePublishForm(id) {
      const currentProject = this.filtered.find((item) => item.id === id);
      console.log(currentProject);
      this.announcement.project_id = currentProject.id;
      this.announcement.project_result = currentProject.required_doc;
      this.announcement.project_type = currentProject.project_title;
      this.announcement.project_scale =
        currentProject.scale + ' ' + currentProject.scale_unit;
      this.announcement.project_location = currentProject.address;
      this.show = true;
    },
    handleDelete(id, nama) {
      this.$confirm(
        'apakah anda yakin akan menghapus ' + nama + '. ?', 'Peringatan',
        {
          confirmButtonText: 'OK',
          cancelButtonText: 'Batal',
          type: 'warning',
        }
      )
        .then(() => {
          projectResource
            .destroy(id)
            .then((response) => {
              this.$message({
                type: 'success',
                message: 'Hapus Selesai',
              });
              this.getFiltered(this.listQuery);
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

