<template>
  <div class="app-container" style="padding: 24px">
    <el-card>
      <div class="filter-container">
        <el-row type="flex" class="row-bg" justify="space-between">
          <div>
            <el-button
              v-if="isInitiator"
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
        fit
        highlight-current-row
        :header-cell-style="{ background: '#3AB06F', color: 'white' }"
        style="width: 100%"
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
                <span class="description">{{ scope.row.address.length > 0 ? scope.row.address[0].district : scope.row.district }} - {{ scope.row.created_at | parseTime('{y}-{m}-{d}') }}
                </span>
              </div>
              <span class="action pull-right">
                <el-button
                  v-if="isInitiator"
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
                  v-if="!isLpjp"
                  href="#"
                  type="text"
                  icon="el-icon-view"
                  @click="handleViewForm(scope.row.id)"
                >
                  Lihat Detil Penapisan
                </el-button>
                <el-button
                  v-if="(scope.row.published && (isInitiator || isExaminer))"
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
                  v-if="isAmdal(scope.row) && (isFormulator || isExaminer || isAdmin || isSubtance)"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleKerangkaAcuan(scope.row)"
                >
                  Formulir Kerangka Acuan
                </el-button>
                <el-button
                  v-if="isUklUpl(scope.row) && (isFormulator || isExaminer || isAdmin || isSubtance)"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleKerangkaUklUpl(scope.row)"
                >
                  Formulir UKL UPL
                </el-button>
                <el-button
                  v-if="isAmdal(scope.row) && (isFormulator || isSubtance || isExaminer || isAdmin)"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleAndal(scope.row)"
                >
                  Andal
                </el-button>
                <el-button
                  v-if="isAmdal(scope.row) && (isFormulator || isExaminer || isAdmin || isSubtance)"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleRklRpl(scope.row)"
                >
                  RKL/RPL
                </el-button>
                <el-button
                  v-if="isUklUpl(scope.row) && isFormulator"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleMatUklUpl(scope.row)"
                >
                  Matriks UKL/UPL
                </el-button>
                <el-button
                  v-if="isSubtance || isAdmin"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleUjiKa(scope.row)"
                >
                  Uji KA
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
                  v-if="isFormulator || isExaminer || isAdmin || isSubtance"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleWorkspaceAndal(scope.row.id)"
                >
                  Workspace Andal
                </el-button>
                <el-button
                  v-if="isFormulator || isExaminer || isAdmin || isSubtance"
                  href="#"
                  type="text"
                  icon="el-icon-document"
                  @click="handleWorkspaceRKLRPL(scope.row.id)"
                >
                  Workspace RKL RPL
                </el-button>
                <el-button
                  v-if="isInitiator"
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
                  @click="handleFeasibilityTest(scope.row.id)"
                >
                  SKKL
                </el-button>
              </span>
              <p class="title"><b>{{ scope.row.project_title }} ({{ scope.row.required_doc }})</b></p>
              <span v-html="scope.row.description" />
            </div>
          </template>
        </el-table-column>
        <el-table-column label="No." width="54px">
          <template slot-scope="scope">
            <span>{{ scope.$index + 1 }}</span>
          </template>
        </el-table-column>
        <el-table-column align="left" label="No. Registrasi" width="200">
          <template slot-scope="scope">
            <span>{{ scope.row.registration_no }}</span>
          <!-- <span>{{
              scope.row.created_at | parseTime('{y}-{m}-{d} {h}:{i}')
            }}</span> -->
          </template>
        </el-table-column>
        <el-table-column align="left" label="Nama Kegiatan" min-width="200">
          <template slot-scope="scope">
            <span>{{ scope.row.project_title }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="Dokumen" width="100">
          <template slot-scope="scope">
            <span>{{ scope.row.required_doc }}</span>
          </template>
        </el-table-column>
        <el-table-column align="left" label="Lokasi" width="200">
          <template slot-scope="scope">
            <span>{{ scope.row.address.length > 0 ? scope.row.address[0].district+'/ '+scope.row.address[0].prov : '' }}</span>
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
  data() {
    return {
      loading: false,
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
    console.log(this.userInfo);
  },
  methods: {
    isUklUpl(project){
      console.log(project.required_doc);
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
      // this.announcement.fileProof = fileProof;
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
      this.listLoading = true;
      const { data, total } = await projectResource.list(query);
      this.filtered = data.map(e => {
        e.listSubProject = e.list_sub_project;
        return e;
      });
      this.total = total;
      console.log(this.filtered);
      this.listLoading = false;
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
      console.log(currentProject);
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
      console.log(currentProject);
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
        // path: `/uklupl/${project.id}/formulir`,
        path: `/ukluplstatic/form`,
      });
    },
    handleUjiKa(project) {
      this.$router.push({
        path: `/dokumen-kegiatan/${project.id}/pengujian-ka`,
      });
    },
    handleUjiRklRpl(project) {
      this.$router.push({
        path: `/dokumen-kegiatan/${project.id}/pengujian-rkl-rpl`,
      });
    },
    handleFeasibilityTest(id) {
      this.$router.push({
        path: `/dokumen-kegiatan/${id}/skkl`,
      });
    },
    async handleGenerateSPPL(project) {
      console.log(project);

      project.listSubProject = project.listSubProject.map((e, i) => {
        e.address = project.address[i] ? this.toTitleCase(project.address[i].address + ' ' + project.address[i].district + ' ' + project.address[i].prov) : '';
        e.number = i + 1;
        return e;
      });

      console.log(project.address[0].district);

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
        path: `/dokumen-kegiatan/${project.id}/penyusunan-rkl-rpl-dummy`,
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
      console.log(project);
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

