<template>
  <div class="app-container" style="padding: 24px">

    <el-card>
      <div class="filter-container">
        <el-row type="flex" class="row-bg" justify="space-between">
          <el-col>
            <el-button
              v-if="couldCreateProject && !isScoping && !isDigiWork"
              class="filter-item"
              type="primary"
              icon="el-icon-plus"
              @click="handleCreate"
            >
              {{ ' Kegiatan' }}
            </el-button>
          </el-col>
          <el-col :span="10">
            <el-input v-model="listQuery.search" prefix-icon="el-icon-search" placeholder="Pencarian" :readonly="loading" @keyup.enter.native="handleFilter">
              <el-button slot="append" icon="el-icon-close" :disabled="loading" @click="resetSearch" />
            </el-input>

            <!-- <el-button
              class="filter-item"
              type="primary"
              icon="el-icon-search"
              @click="handleFilter"
            >
              {{ $t('table.search') }}
            </el-button> -->
          </el-col>
        </el-row>
      </div>
      <el-table
        v-loading="loading"
        :data="filtered"
        fit
        :clear-filter="onClearFilter"
        highlight-current-row
        :header-cell-style="{ background: '#216221', color: 'white' }"
        style="width: 100%"
        @sort-change="onTableSort"
      >
        <el-table-column type="expand" class="row-detail">
          <template slot-scope="scope">
            <div class="post">
              <div class="entity-block">
                <img
                  class="img-circle"
                  :src="scope.row.avatar || 'no-image.png'"
                  @error="$event.target.src='no-image.png'"
                >
                <span class="name text-muted">
                  <a href="#">{{ scope.row.applicant }}</a>
                  <a
                    href="#"
                    class="pull-right btn-box-tool"
                  >
                    <i class="fa fa-times" />
                  </a>
                </span>
                <span class="description">{{ scope.row.address.length > 0 ? scope.row.address[0].district : scope.row.district }}
                </span>
              </div>
              <span class="action pull-right">
                <el-button
                  v-if="isInitiator && !isScoping && !isDigiWork"
                  type="text"
                  href="#"
                  icon="el-icon-user"
                  @click="handleTimPenyusunForm(scope.row.id)"
                >
                  Tim Penyusun
                </el-button>
                <el-button
                  v-if="!scope.row.published && isInitiator"
                  type="text"
                  href="#"
                  icon="el-icon-tickets"
                  @click="handlePublishForm(scope.row.id)"
                >
                  Publish
                </el-button>
                <!-- <el-button
                  v-if="!scope.row.published && isInitiator"
                  type="text"
                  href="#"
                  icon="el-icon-edit"
                  @click="handleEditForm(scope.row.id)"
                >
                  Edit
                </el-button> -->
                <!-- <el-button
                  v-if="!scope.row.published && isInitiator"
                  type="text"
                  href="#"
                  icon="el-icon-delete"
                  @click="handleDelete(scope.row.id, scope.row.project_title)"
                >
                  Delete
                </el-button> -->
                <el-button
                  v-if="couldViewProject && !isScoping && !isDigiWork"
                  href="#"
                  type="text"
                  icon="el-icon-view"
                  @click="handleViewForm(scope.row.id)"
                >
                  Lihat Detil Penapisan
                </el-button>
                <el-button
                  v-if="(scope.row.published && (isInitiator || isExaminer) && !isScoping && !isDigiWork)"
                  href="#"
                  type="text"
                  icon="el-icon-view"
                  @click="handleViewSpt(scope.row.announcementId)"
                >
                  Lihat SPT
                </el-button>
                <el-button
                  v-if="isLpjp && !scope.row.team_id"
                  href="#"
                  type="text"
                  icon="el-icon-user"
                  @click="handleLpjpTeam(scope.row)"
                >
                  Tambah Tim LPJP
                </el-button>
                <el-button
                  v-if="isAmdal(scope.row) && (isFormulator || isExaminer || isAdmin || isSubtance) && !isScreening && !isDigiWork"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleKerangkaAcuan(scope.row)"
                >
                  Formulir Kerangka Acuan
                </el-button>
                <el-button
                  v-if="isUklUpl(scope.row) && (isFormulator || isExaminer || isAdmin || isSubtance) && !isScreening && !isDigiWork"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleKerangkaUklUpl(scope.row)"
                >
                  Formulir UKL UPL
                </el-button>
                <el-button
                  v-if="isAmdal(scope.row) && (isFormulator || isSubtance || isExaminer || isAdmin) && !isScreening && !isDigiWork"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleAndal(scope.row)"
                >
                  Andal
                </el-button>
                <el-button
                  v-if="isAmdal(scope.row) && (isFormulator || isExaminer || isAdmin || isSubtance) && !isScreening && !isDigiWork"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleRklRpl(scope.row)"
                >
                  RKL/RPL
                </el-button>
                <el-button
                  v-if="isUklUpl(scope.row) && isFormulator && !isScreening && !isDigiWork"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleMatUklUpl(scope.row)"
                >
                  Matriks UKL/UPL
                </el-button>
                <el-button
                  v-if="isAmdal(scope.row) && (isSubtance || isAdmin)"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleUjiKa(scope.row)"
                >
                  Uji KA
                </el-button>
                <el-button
                  v-if="isUklUpl(scope.row) && (isSubtance || isAdmin)"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleUjiUKLUPL(scope.row)"
                >
                  Uji UKL UPL
                </el-button>
                <el-button
                  v-if="isAdmin || isSubtance || isExaminer"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleUjiRklRpl(scope.row)"
                >
                  Uji Kelayakan
                </el-button>
                <!-- <el-button
                  v-if="isFormulator"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleFlowChart(scope.row)"
                >
                  Bagan Alir
                </el-button> -->
                <el-button
                  v-if="isAmdal(scope.row) && (isFormulator || isExaminer || isAdmin || isSubtance) && !isScreening && !isScoping"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleWorkspaceAndal(scope.row.id)"
                >
                  Workspace Andal
                </el-button>
                <el-button
                  v-if="isAmdal(scope.row) && (isFormulator || isExaminer || isAdmin || isSubtance) && !isScreening && !isScoping"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleWorkspaceRKLRPL(scope.row.id)"
                >
                  Workspace RKL RPL
                </el-button>
                <el-button
                  v-if="isUklUpl(scope.row) && (isFormulator || isExaminer || isAdmin || isSubtance) && !isScreening && !isScoping"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleWorkspaceUKLUPL(scope.row.id)"
                >
                  Workspace UKL UPL
                </el-button>
                <el-button
                  v-if="isInitiator && !isScoping && !isDigiWork"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleGenerateSPPL(scope.row)"
                >
                  Unduh SPPL
                </el-button>
                <el-button
                  v-if="scope.row.feasibility_test"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleRekomendasiUjiKelayakan(scope.row)"
                >
                  Surat Rekomendasi Uji Kelayakan
                </el-button>
                <el-button
                  v-if="isAmdal(scope.row) && scope.row.feasibility_test"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleFeasibilityTest(scope.row.id)"
                >
                  SKKL
                </el-button>
                <el-button
                  v-if="isUklUpl(scope.row) && scope.row.feasibility_test"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handlePKPLH(scope.row)"
                >
                  PKPLH
                </el-button>
              </span>
              <p class="title"><b>{{ scope.row.project_title }} ({{ scope.row.required_doc }})</b></p>
              <span v-html="scope.row.description" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="No." width="54px">
          <template slot-scope="scope">
            <span>{{ ((listQuery.page-1) * listQuery.limit) + (scope.$index + 1) }}</span>
          </template>
        </el-table-column>
        <el-table-column prop="registration_no" align="left" label="No. Registrasi" width="150" sortable="custom">
          <template slot-scope="scope">
            <span>{{ scope.row.registration_no ? scope.row.registration_no.toUpperCase() : '' }}</span>
          <!-- <span>{{
              scope.row.created_at | parseTime('{y}-{m}-{d} {h}:{i}')
            }}</span> -->
          </template>
        </el-table-column>
        <el-table-column
          prop="created_at"
          align="center"
          label="Tanggal"
          sortable="custom"
          width="150px"
        >
          <template slot-scope="scope">
            <div style="line-height: 1.1em;">
              <span>{{ scope.row.created_at | parseTime('{y}-{m}-{d}') }}</span>
              <!-- <br>
              <span style="font-size:86%">{{ scope.row.created_at | parseTime('{h}:{i}') }}</span> -->
            </div>
          </template>
        </el-table-column>
        <el-table-column
          prop="project_title"
          align="left"
          label="Nama Kegiatan"
          sortable="custom"
          min-width="200"
        >
          <template slot-scope="scope">
            <span>{{ scope.row.project_title }}</span>
          </template>
        </el-table-column>
        <el-table-column
          width="150px"

          column-key="doc_type"
          align="center"
          label="Dokumen"
        >

          <template slot="header">
            <el-select
              v-model="listQuery.filters"
              class="filter-header"
              clearable
              placeholder="Dokumen"
              @change="onDocTypeFilter"
            >
              <el-option
                v-for="item in [{text: 'AMDAL', value: 'AMDAL'}, {text: 'UKL-UPL', value: 'UKL-UPL'}, {text: 'SPPL', value: 'SPPL'}]"
                :key="item.value"
                :label="item.text"
                :value="item.value"
              />
            </el-select>
          </template>
          <template slot-scope="scope">
            <span>{{ scope.row.required_doc }}</span>
          </template>
        </el-table-column>
        <el-table-column align="left" label="Lokasi" width="200">
          <template slot-scope="scope">
            <span>{{ scope.row.address.length > 0 ? scope.row.address[0].district+'/ '+scope.row.address[0].prov : '' }}</span>
          </template>
        </el-table-column>
        <el-table-column label="Tahap" class-name="status-col">
          <template slot-scope="scope">
            {{ scope.row.marking | projectStep }}
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
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import Pagination from '@/components/Pagination';
import AnnouncementDialog from './components/AnnouncementDialog.vue';
import Docxtemplater from 'docxtemplater';
import PizZip from 'pizzip';
import PizZipUtils from 'pizzip/utils/index.js';
import { saveAs } from 'file-saver';
import axios from 'axios';
const initiatorResource = new Resource('initiatorsByEmail');
const provinceResource = new Resource('provinces');
const districtResource = new Resource('districts');
const projectResource = new Resource('projects');
const announcementResource = new Resource('announcements');
const lpjpResource = new Resource('lpjpsByEmail');
const formulatorResource = new Resource('formulatorsByEmail');
const andalComposingResource = new Resource('andal-composing');
const rklResource = new Resource('matriks-rkl');
// const kbliResource = new Resource('business');

export default {
  name: 'Project',
  components: { Pagination, AnnouncementDialog },
  filters: {
    projectStep: function(value) {
      if (value === 'screening-completed'){
        return 'Selesai Penapisan';
      } else if (value === 'formulator-assignment'){
        return 'Penetapan Penyusun';
      } else {
        return '';
      }
    },
  },
  data() {
    return {
      loading: true,
      searchString: '',
      sectionHeader: 'list-penapisan',
      userInfo: {
        roles: [],
      },
      filtered: [],
      initiator: {},
      announcement: {},
      show: false,
      total: 0,
      listQuery: {
        page: 1,
        limit: 10,
        orderBy: 'id',
        order: 'DESC',
        filters: '',
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
  computed: {
    couldCreateProject(){
      return this.$store.getters.permissions.includes('create project');
    },
    couldViewProject(){
      return this.$store.getters.permissions.includes('read project');
    },
    isLpjp() {
      return this.userInfo.roles.includes('lpjp');
    },
    isInitiator() {
      return this.userInfo.roles.includes('initiator');
    },
    isFormulator() {
      return this.userInfo.roles.includes('formulator');
    },
    isSubtance() {
      return this.userInfo.roles.includes('examiner-substance');
    },
    isAdmin() {
      return this.userInfo.roles.includes('examiner-administration');
    },
    isExaminer() {
      return this.userInfo.roles.includes('examiner');
    },
    isScoping(){
      return this.$route.name === 'scopingProject';
    },
    isDigiWork(){
      return this.$route.name === 'digWorkProject';
    },
    isScreening(){
      return this.$route.name === 'screeningProject';
    },
  },
  async created() {
    this.getProvinces();
    this.userInfo = await this.$store.dispatch('user/getInfo');
    if (this.userInfo.roles.includes('lpjp')){
      const lpjp = await lpjpResource.list({ email: this.userInfo.email });
      this.listQuery.lpjpId = lpjp.id;
    } else if (this.userInfo.roles.includes('initiator')) {
      const initiator = await initiatorResource.list({ email: this.userInfo.email });
      this.listQuery.initiatorId = initiator.id;
      this.initiator = initiator;
    } else if (this.userInfo.roles.includes('formulator')) {
      const formulator = await formulatorResource.list({ email: this.userInfo.email });
      this.listQuery.formulatorId = formulator.id;
    }
    // else if (this.userInfo.roles.includes('examiner-substance')) {
    //   const formulator = await formulatorResource.list({ email: this.userInfo.email });
    //   this.listQuery.formulatorId = formulator.id;
    // }

    this.getFiltered(this.listQuery);
  },
  methods: {
    isUklUpl(project){
      return project.required_doc === 'UKL-UPL';
    },
    isAmdal(project){
      return project.required_doc === 'AMDAL';
    },
    toTitleCase(str) {
      return str.replace(
        /\w\S*/g,
        function(txt) {
          return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        }
      );
    },
    handleSubmitAnnouncement(fileProof){
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
                'Rencana Kegiatan ' +
                this.announcement.project_type +
                ' Telah Dipublikasikan Di Daftar Pengumuman & Informasi Pada Halaman Utama Amdalnet',
            type: 'success',
            duration: 5 * 1000,
          });
          this.getFiltered(this.listQuery);
          this.announcement = {};
          this.show = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    handleCancelAnnouncement(){
      this.announcement = {};
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
      this.loading = true;
      this.filtered = [];
      console.log('getFiltered', query);
      await projectResource.list(query)
        .then((res) => {
          const { data, total } = res;
          const mapped = data.map(e => {
            e.listSubProject = e.list_sub_project;
            return e;
          });
          this.filtered = mapped;
          this.total = total;
          this.loading = false;
          console.log(data, this.filtered);
        }).finally(() => {
          this.loading = false;
        });
    },
    handleCreate() {
      this.$router.push({
        name: 'createProject',
        params: {},
      });
    },
    handleCreate2() {
      this.$router.push({
        name: 'createProject2',
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

      // change field to number and formulator team
      currentProject.field = Number(currentProject.field);
      currentProject.id_formulator_team = Number(currentProject.id_formulator_team);
      this.$router.push({
        name: 'createProject',
        params: { project: currentProject },
      });
    },
    handleViewForm(id) {
      const currentProject = this.filtered.find((item) => item.id === id);

      // change project_year to string
      currentProject.project_year = currentProject.project_year.toString();
      // change field to number and formulator team
      currentProject.field = Number(currentProject.field);
      currentProject.id_formulator_team = Number(currentProject.id_formulator_team);

      // this.$router.push({a
      //   name: 'createProject',
      //   params: { project: currentProject },
      // });
      this.$router.push({
        name: 'publishProject',
        params: { project: currentProject, readonly: true },
      });
    },
    handleTimPenyusunForm(id) {
      const currentProject = this.filtered.find((item) => item.id === id);
      this.$router.push({
        name: 'timPenyusun',
        params: { project: currentProject, readonly: true, id: currentProject.id },
      });
      // this.$router.push('penyusun/' + currentProject.id);
    },
    handlePublishForm(id) {
      const currentProject = this.filtered.find((item) => item.id === id);
      const subProject = currentProject.list_sub_project.map(curr => {
        return {
          name: curr.name,
          scale: curr.scale + ' ' + curr.scale_unit,
        };
      });
      this.announcement.sub_project = subProject;
      this.announcement.pic_name = this.initiator.pic;
      this.announcement.pic_address = this.initiator.address;
      this.announcement.project_id = currentProject.id;
      this.announcement.project_result = currentProject.required_doc;
      this.announcement.project_type = currentProject.project_title;
      this.announcement.project_scale =
        currentProject.scale + ' ' + currentProject.scale_unit;
      this.announcement.project_location = currentProject.address;
      this.announcement.id_applicant = currentProject.id_applicant;
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
    handleKerangkaAcuan(project) {
      this.$router.push({
        path: `/amdal/${project.id}/formulir`,
      });
    },
    handleKerangkaUklUpl(project) {
      this.$router.push({
        path: `/uklupl/${project.id}/formulir`,
        // path: `/ukluplstatic/form`,
      });
    },
    handleUjiKa(project) {
      this.$router.push({
        path: `/dokumen-kegiatan/${project.id}/pengujian-ka`,
      });
    },
    handleUjiUKLUPL(project) {
      this.$router.push({
        path: `/uklupl/${project.id}/pengujian`,
      });
    },
    handleUjiRklRpl(project) {
      if (this.isAmdal(project)) {
        this.$router.push({
          path: `/dokumen-kegiatan/${project.id}/pengujian-rkl-rpl`,
        });
      } else {
        this.$router.push({
          path: `/uklupl/${project.id}/pengujian`,
        });
      }
    },
    handleRekomendasiUjiKelayakan(project) {
      if (this.isAmdal(project)) {
        this.$router.push({
          path: `/dokumen-kegiatan/${project.id}/rekomendasi-uji-kelayakan`,
        });
      } else {
        this.$router.push({
          path: `/uklupl/${project.id}/rekomendasi-uji-kelayakan`,
        });
      }
    },
    handleFeasibilityTest(id) {
      this.$router.push({
        path: `/dokumen-kegiatan/${id}/skkl`,
      });
    },
    handlePKPLH(project) {
      this.$router.push({
        path: `/uklupl/${project.id}/pkplh`,
      });
    },
    async handleGenerateSPPL(project) {
      project.listSubProject = project.listSubProject.map((e, i) => {
        e.address = project.address[i] ? this.toTitleCase(project.address[i].address + ' ' + project.address[i].district + ' ' + project.address[i].prov) : '';
        e.number = i + 1;
        return e;
      });

      PizZipUtils.getBinaryContent(
        '/template_sppl.docx',
        (error, content) => {
          if (error) {
            throw error;
          }
          const zip = new PizZip(content);
          const doc = new Docxtemplater(zip, {
            paragraphLoop: true,
            linebreaks: true,
          });
          doc.render({
            initator_name: this.initiator.name,
            nib: this.initiator.nib ? this.initiator.nib : 'Belum Terdaftar',
            initiator_address: this.initiator.address,
            phone: this.initiator.phone,
            sub_projects: project.listSubProject,
            pic: this.initiator.pic,
            city: project.address[0].district,
            publish_date: project.created_at.substring(0, 10),
          });

          const out = doc.getZip().generate({
            type: 'blob',
            mimeType: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
          });

          saveAs(out, 'SPPL-' + project.project_title + '.docx');
          // this.docOutput = out;
        }
      );
    },
    handleAndal(project) {
      this.$router.push({
        path: `/dokumen-kegiatan/${project.id}/penyusunan-andal`,
      });
    },
    handleMatUklUpl(project) {
      this.$router.push({
        path: `/uklupl/${project.id}/matriks`,
      });
    },
    handleRklRpl(project) {
      this.$router.push({
        path: `/dokumen-kegiatan/${project.id}/penyusunan-rkl-rpl`,
      });
    },
    handleLpjpTeam(project) {
      this.$router.push({
        path: `/lpjp-team/${project.id}/daftar-anggota`,
      });
    },
    handleViewSpt(id) {
      this.$router.push({
        path: `/announcement/view/${id}`,
      });
    },
    handleFlowChart(project) {
      this.$router.push({
        name: 'flowchart',
        params: { id: project.id, project: project },
      });
    },
    handleWorkspace(project) {
      this.$router.push({
        name: 'editWorkspace',
        params: { id: project.id, project: project },
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
    async handleWorkspaceAndal(idProject) {
      await andalComposingResource.list({
        docs: 'true',
        idProject: idProject,
      });

      this.$router.push({
        name: 'projectWorkspace',
        params: {
          id: idProject,
          filename: `${idProject}-andal.docx`,
        },
      });
    },
    async handleWorkspaceRKLRPL(idProject) {
      await rklResource.list({
        docs: 'true',
        idProject: idProject,
      });

      this.$router.push({
        name: 'projectWorkspace',
        params: {
          id: idProject,
          filename: `${idProject}-rkl-rpl.docx`,
        },
      });
    },
    async handleWorkspaceUKLUPL(idProject) {
      const projectName = await axios.get(
        `/api/dokumen-ukl-upl/${idProject}`
      );
      this.$router.push({
        name: 'projectWorkspace',
        params: {
          id: idProject,
          filename: projectName.data,
        },
      });
    },
    // sorting, filtering
    onTableSort(sort) {
      /* switch (sort.prop) {
        case 'date':
          this.listQuery.orderBy = 'created_at';
          console.log(this.listQuery);
          break;
        case 'title':
          this.listQuery.orderBy = 'project_title';
        default:
      }*/
      this.listQuery.orderBy = sort.prop;
      this.listQuery.order = (sort.order === 'ascending') ? 'ASC' : 'DESC';
      this.handleFilter();
    },
    onDocTypeFilter(val, col, row){
      console.log('filtering doctype!', val);

      this.listQuery.filters = val;
      this.listQuery.page = 1;

      this.handleFilter();
    },
    onClearFilter(key){
      console.log('clearing filter!', key);
    },
    doSearch(){
      this.listQuery.page = 1;
      this.getFiltered(this.listQuery);
    },
    resetSearch(){
      this.listQuery.search = '';
      this.listQuery.page = 1;
      this.handleFilter();
    },
  },
};
</script>

<style lang="scss" scoped>
.entity-block {
  display: inline-block;

  .name, .description {
    display: block;
    margin-left: 50px;
    padding: 2px 0;
  }
  .action {
    display:  inline-block;
    padding-right: 5%;
  }
  img {
    width: 40px;
    height: 40px;
    float: left;
  }
  :after {
    clear: both;
  }
  .img-circle {
    border-radius: 50%;
    border: 2px solid #d2d6de;
    padding: 2px;
  }
  span {
    font-weight: 500;
    font-size: 12px;
  }

}
.post {
  font-size: 14px;
  margin-bottom: 15px;
  padding-right: 20px;
  padding-left: 7%;
  color: #666;
  .image {
    width: 100%;
  }
  .user-images {
    padding-top: 20px;
  }
  .title {
    display: block;
    padding: 2px 0;
  }
}
.list-inline {
  padding-left: 0;
  margin-left: -5px;
  list-style: none;
  li {
    display: inline-block;
    padding-right: 5px;
    padding-left: 5px;
    font-size: 13px;
  }
  .link-black {
    &:hover, &:focus {
      color: #999;
    }
  }
}
</style>

