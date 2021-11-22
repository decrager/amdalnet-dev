<template>
  <div class="form-container" style="padding: 24px">
    <workflow />
    <el-form
      ref="currentProject"
      :model="currentProject"
      :rules="currentProjectRules"
      label-position="top"
      label-width="200px"
      style="max-width: 100%"
    >
      <div v-if="preProject">
        <el-collapse v-model="activeName" :accordion="true">
          <el-collapse-item title="TAPAK PROYEK" name="1">
            <el-row :gutter="4">
              <el-col :span="12">
                <el-form-item
                  label="Nama Rencana Usaha/Kegiatan"
                  prop="project_title"
                >
                  <el-input v-model="currentProject.project_title" size="medium" />
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item label="Upload Dokumen Kesesuaian Tata Ruang">
                  <input ref="fileKtr" type="file" class="el-input__inner" @change="handleFileKtrUpload()">
                </el-form-item>
              </el-col>
            </el-row>
            <el-row :gutter="4">
              <el-col :span="12">
                <el-row>
                  <el-form-item label="Deskripsi Kegiatan">
                    <el-input v-model="currentProject.description" size="medium" type="textarea" />
                  </el-form-item>
                </el-row>
                <el-row>
                  <el-form-item label="Deskripsi Lokasi">
                    <el-input v-model="currentProject.location_desc" size="medium" type="textarea" />
                  </el-form-item>
                </el-row>
              </el-col>
              <el-col :span="12">
                <el-row>
                  <el-form-item label="Upload Peta (File .ZIP)">
                    <input id="fileMap" ref="fileMap" type="file" class="el-input__inner" multiple @change="handleFileTapakProyekMapUpload">
                  </el-form-item>
                  <div id="mapView" style="height: 400px;" />
                </el-row>
              </el-col>
            </el-row>
            <el-row type="flex" justify="end">
              <el-col :span="2">
                <el-button size="medium" type="primary" @click="activeName = '2'">
                  Lanjutkan
                </el-button>
              </el-col>
            </el-row>
          </el-collapse-item>
          <el-collapse-item title="PENDEKATAN STUDI" name="2">
            <el-row>
              <el-col :span="12">
                <el-form-item
                  label="Pendekatan Studi untuk Kegiatan"
                  prop="study_approach"
                >
                  <el-select
                    v-model="currentProject.study_approach"
                    placeholder="Pilih"
                    style="width: 100%"
                    size="medium"
                    @change="changeStudyApproach($event)"
                  >
                    <el-option
                      v-for="item in studyApproachOptions"
                      :key="item.value.id"
                      :label="item.label"
                      :value="item.value"
                    />
                  </el-select>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <sub-project-table :list="listSubProject" :list-kbli="getListKbli" />
              <el-button
                type="primary"
                @click="handleAddSubProjectTable"
              >+</el-button>
            </el-row>
            <el-row type="flex" justify="end">
              <el-col :span="2">
                <el-button size="medium" type="primary" @click="handleStudyAccord">
                  Lanjutkan
                </el-button>
              </el-col>
            </el-row>
          </el-collapse-item>
          <el-collapse-item title="STATUS KEGIATAN" name="3">
            <el-row>
              <el-col :span="12">
                <el-form-item
                  label="Rencana Kegiatan Baru atau Kegiatan Yang Sudah Berjalan"
                  prop="status"
                >
                  <el-select
                    v-model="currentProject.status"
                    placeholder="Pilih"
                    style="width: 100%"
                    size="medium"
                    @change="changeStatus($event)"
                  >
                    <el-option
                      v-for="item in statusOptions"
                      :key="item.value.id"
                      :label="item.label"
                      :value="item.value"
                    />
                  </el-select>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row type="flex" justify="end">
              <el-col :span="2">
                <el-button size="medium" type="primary" @click="activeName = '4'">
                  Lanjutkan
                </el-button>
              </el-col>
            </el-row>
          </el-collapse-item>
          <el-collapse-item title="JENIS KEGIATAN" name="4">
            <el-row>
              <el-col :span="12">
                <el-form-item
                  label="Kegiatan Baru atau Bagian dari Kegiatan Sebelumnya?"
                  prop="project_type"
                >
                  <el-select
                    v-model="currentProject.project_type"
                    placeholder="Pilih"
                    style="width: 100%"
                    size="medium"
                    @change="changeProjectType($event)"
                  >
                    <el-option
                      v-for="item in projectTypeOptions"
                      :key="item.value.id"
                      :label="item.label"
                      :value="item.value"
                    />
                  </el-select>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row type="flex" justify="end">
              <el-col :span="4">
                <el-button size="medium" @click="handleCancel()"> Batalkan </el-button>
                <el-button size="medium" type="primary" @click="preProject = false"> Lanjutkan </el-button>
              </el-col>
            </el-row>
          </el-collapse-item>
        </el-collapse>
      </div>

      <div v-else>
        <el-row :gutter="4">
          <el-col :span="12" :xs="24">
            <el-row>
              <el-form-item
                label="Nama Usaha / Kegiatan"
                prop="project_title"
              >
                <el-input v-model="currentProject.project_title" disabled />
              </el-form-item>
            </el-row>
            <el-row :gutter="4">
              <el-col :span="4">
                <el-form-item label="KBLI" prop="kbli">
                  <!-- <el-input v-model="currentProject.kbli" /> -->
                  <el-autocomplete
                    v-model="currentProject.kbli"
                    class="inline-input"
                    :fetch-suggestions="kbliSearch"
                    placeholder="Masukan"
                    :trigger-on-focus="false"
                    disabled
                    @select="handleKbliSelect"
                  />
                </el-form-item>
              </el-col>
              <el-col :span="6">
                <el-form-item label="Tingkat Resiko" prop="risk_level">
                  <el-input v-model="currentProject.risk_level" disabled />
                </el-form-item>
              </el-col>
              <el-col :span="6" :xs="24">
                <el-form-item label="Kewenangan" prop="authority">
                  <el-input v-model="currentProject.authority" disabled />
                </el-form-item>
              </el-col>
              <el-col :span="8">
                <el-form-item label="Sektor(PP 5/2021)" prop="field">
                  <el-select
                    v-model="currentProject.field"
                    placeholder="Pilih"
                    style="width: 100%"
                  >
                    <el-option
                      v-for="item in getProjectFieldOptions"
                      :key="item.value"
                      :label="item.label"
                      :value="item.value"
                    />
                  </el-select>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row :gutter="4">
              <el-col :span="12">
                <el-form-item label="Bidang Kegiatan (Permen LHK 4 /2021)" prop="sector">
                  <el-select
                    v-model="currentProject.sector"
                    placeholder="Pilih"
                    style="width: 100%"
                  >
                    <el-option
                      v-for="item in getSectorOptions"
                      :key="item.value"
                      :label="item.label"
                      :value="item.value"
                    />
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col :span="12">
                <el-form-item label="Jenis Rencana Kegiatan" prop="plan_type">
                  <el-input v-model="currentProject.plan_type" disabled />
                </el-form-item>
              </el-col>
            </el-row>
            <el-row>
              <el-table
                :header-cell-style="{ background: '#099C4B', color: 'white' }"
                :data="currentProject.listSubProjectParams"
                fit
                highlight-current-row
              >
                <el-table-column label="Skala Besaran" align="center">
                  <el-table-column label="Parameter">
                    <template slot-scope="scope">
                      {{ scope.row.param }}
                    </template>
                  </el-table-column>

                  <el-table-column label="Besaran">
                    <template slot-scope="scope">
                      {{ scope.row.scale }}
                    </template>
                  </el-table-column>

                  <el-table-column label="Satuan">
                    <template slot-scope="scope">
                      {{ scope.row.scale_unit }}
                    </template>
                  </el-table-column>

                  <el-table-column label="Hasil">
                    <template slot-scope="scope">
                      {{ scope.row.result }}
                    </template>
                  </el-table-column>
                </el-table-column>
              </el-table>
            </el-row>
            <!-- <el-row :gutter="4">
              <el-col :span="12" :xs="24">
                <el-form-item
                  prop="dokumenPendukung"
                  label="Dokumen Pendukung"
                ><support-table
                   :list="listSupportTable"
                   :loading="loadingSupportTable"
                 />
                  <el-button
                    type="primary"
                    @click="handleAddSupportTable"
                  >+</el-button>
                </el-form-item>
              </el-col>
            </el-row> -->
          </el-col>
          <el-col :span="12" :xs="24">
            <div id="mapView" style="height: 400px; border: 1px solid red; border-radius: 5px;" />
          </el-col>
        </el-row>
        <el-row type="flex" justify="end" :gutter="4">
          <el-col :span="12">
            <el-col :span="12" />
          </el-col>
          <el-col :span="12" :xs="24">
            <el-col :span="12">
              <el-form-item label="Provinsi" prop="id_prov">
                <el-select
                  v-model="currentProject.id_prov"
                  placeholder="Pilih"
                  style="width: 100%"
                  @change="changeProvince($event)"
                >
                  <el-option
                    v-for="item in getProvinceOptions"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value"
                  />
                </el-select>
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="Kab / Kota" prop="id_district">
                <el-select
                  v-model="currentProject.id_district"
                  placeholder="Pilih"
                  style="width: 100%"
                >
                  <el-option
                    v-for="item in getCityOptions"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value"
                  />
                </el-select>
              </el-form-item>
            </el-col>
          </el-col>
        </el-row>
        <el-row type="flex" justify="end">
          <el-col :span="12">
            <el-form-item label="Alamat" prop="address">
              <el-input v-model="currentProject.address" />
            </el-form-item>
          </el-col>
        </el-row>
      </div>
    </el-form>

    <div v-if="!preProject" slot="footer" class="dialog-footer">
      <el-button @click="preProject = true"> Batalkan </el-button>
      <el-button type="primary" @click="handleSubmit()"> Lanjutkan </el-button>
    </div>
  </div>
</template>

<script>
// import Tinymce from '@/components/Tinymce';
import Workflow from '@/components/Workflow';
import Resource from '@/api/resource';
// import SupportTable from './components/SupportTable.vue';
import SubProjectTable from './components/SubProjectTable.vue';
import 'vue-simple-accordion/dist/vue-simple-accordion.css';
const SupportDocResource = new Resource('support-docs');
import * as L from 'leaflet';
import shp from 'shpjs';

export default {
  name: 'CreateProject',
  components: {
    // Tinymce,
    // SupportTable,
    Workflow,
    SubProjectTable,
  },
  data() {
    return {
      preProject: true,
      activeName: '1',
      currentProject: {},
      listSupportTable: [],
      listSubProject: [],
      loadingSupportTable: false,
      isUpload: 'Upload',
      fileName: 'No File Selected.',
      fileMap: [],
      isOss: true,
      studyApproachOptions: [
        {
          value: 'Terpadu',
          label: 'Terpadu',
        },
        {
          value: 'Tunggal',
          label: 'Tunggal',
        },
        {
          value: 'Kawasan',
          label: 'Kawasan',
        },
      ],
      statusOptions: [
        {
          value: 'Rencana',
          label: 'Rencana',
        },
        {
          value: 'Berjalan',
          label: 'Berjalan',
        },
      ],
      projectTypeOptions: [
        {
          value: 'Baru',
          label: 'Baru',
        },
        {
          value: 'Pengembangan',
          label: 'Pengembangan',
        },
      ],
      currentProjectRules: {
        project_title: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
        project_type: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
        id_prov: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
        id_district: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
        kbli: [
          { required: true, trigger: 'blur', message: 'Data Belum Diisi' },
        ],
        risk_level: [
          { required: true, trigger: 'blur', message: 'Data Belum Diisi' },
        ],
        project_year: [
          {
            type: 'date',
            required: true,
            trigger: 'blur',
            message: 'Data Belum Diisi',
          },
        ],
        field: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
        address: [
          { required: true, trigger: 'blur', message: 'Data Belum Diisi' },
        ],
        sector: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
        biz_type: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
        scale_unit: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
        scale: [
          { required: true, trigger: 'blur', message: 'Data Belum Diisi' },
        ],
        location_desc: [
          { required: true, trigger: 'blur', message: 'Data Belum Diisi' },
        ],
        description: [
          { required: true, trigger: 'blur', message: 'Data Belum Diisi' },
        ],
        fileMap: [
          { required: true, trigger: 'change', message: 'Data Belum Dipilih' },
        ],
      },
    };
  },
  computed: {
    getProjectOption() {
      return this.$store.getters.projectOptions;
    },
    getProjectFieldOptions() {
      return this.$store.getters.projectFieldOptions;
    },
    getSectorOptions() {
      return this.$store.getters.sectorOptions;
    },
    getProvinceOptions() {
      return this.$store.getters.provinceOptions;
    },
    getCityOptions() {
      return this.$store.getters.cityOptions;
    },
    getUnitOptions() {
      return this.$store.getters.unitOptions;
    },
    getBusinessTypeOptions() {
      return this.$store.getters.businessTypeOptions;
    },
    getListKbli() {
      return this.$store.getters.kblis;
    },
    isPemerintah() {
      return this.$store.getters.isPemerintah;
    },
  },
  async created() {
    // for step
    this.$store.dispatch('getStep', 0);

    // get initiator data
    const { email } = await this.$store.dispatch('user/getInfo');
    this.$store.dispatch('getInitiator', email);

    // for default value
    // this.currentProject.study_approach = 'Tunggal';
    // this.currentProject.status = 'Rencana';
    // this.currentProject.project_type = 'Baru';

    if (this.$route.params.project) {
      this.currentProject = this.$route.params.project;
      this.fileName = this.getFileName(this.currentProject.map);
      this.fileMap = this.getFileName(this.currentProject.map);
      this.listSupportTable = await this.getListSupporttable(
        this.currentProject.id
      );
      this.getDistricts(this.currentProject.id_prov);
    }
    this.getAllData();
  },
  methods: {
    sumResult(listParam){
      let result = '';
      let result_risk = '';
      for (let i = 0; i < listParam.length; i++) {
        if (listParam[i].result === 'AMDAL'){
          result = listParam[i].result;
          result_risk = listParam[i].result_risk;
          break;
        } else if (listParam[i].result === 'UKL-UPL'){
          result = listParam[i].result;
          result_risk = listParam[i].result_risk;
        } else if (listParam[i].result === 'SPPL' && result !== 'UKL-UPL'){
          result = listParam[i].result;
          result_risk = listParam[i].result_risk;
        }
      }
      return { result, result_risk };
    },
    calculateListSubProjectResult(){
      this.currentProject.listSubProject = this.listSubProject.map(e => {
        e.listSubProjectParams = e.listSubProjectParams.filter(e => e.used);

        const { result, result_risk } = this.sumResult(e.listSubProjectParams);
        e.result = result;
        e.result_risk = result_risk;

        return e;
      });
    },
    calculateChoosenProject(){
      console.log('project tanpa filter', this.currentProject);
      const listMainProjectAmdal = this.currentProject.listSubProject.filter(e => e.type === 'utama' && e.result === 'AMDAL');
      const listMainProjectUklUpl = this.currentProject.listSubProject.filter(e => e.type === 'utama' && e.result === 'UKL-UPL');
      const listMainProjectSppl = this.currentProject.listSubProject.filter(e => e.type === 'utama' && e.result === 'SPPL');

      console.log('listAmdal', listMainProjectAmdal);
      let choosenProject = '';

      if (listMainProjectAmdal.length !== 0){
        choosenProject = listMainProjectAmdal[0];
      } else if (listMainProjectUklUpl.length !== 0) {
        choosenProject = listMainProjectUklUpl[0];
      } else if (listMainProjectSppl.length !== 0) {
        choosenProject = listMainProjectSppl[0];
      }

      console.log('choosenProject', choosenProject);

      // add choosen project to current project
      this.currentProject.kbli = choosenProject.kbli;
      this.currentProject.required_doc = choosenProject.result;
      this.currentProject.risk_level = choosenProject.result_risk;
      this.currentProject.result_risk = choosenProject.result_risk;
      this.currentProject.authority = 'Pusat';
      this.currentProject.plan_type = choosenProject.name;
      this.currentProject.listSubProjectParams = choosenProject.listSubProjectParams;
    },
    handleStudyAccord(){
      this.calculateListSubProjectResult();
      this.calculateChoosenProject();
      this.activeName = '3';
    },
    handleFileKtrUpload(){
      this.fileKtr = this.$refs.fileKtr.files[0];
    },
    handleFileTapakProyekMapUpload(e){
      var selectedFiles = e.target.files;

      const map = L.map('mapView');
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      }).addTo(map);

      for (let i = 0; i < selectedFiles.length; i++){
        this.fileMap.push(selectedFiles[i]);
      }

      for (let i = 0; i < this.fileMap.length; i++){
        const reader = new FileReader(); // instantiate a new file reader
        reader.onload = (event) => {
          const base = event.target.result;
          shp(base).then(function(data) {
            const geo = L.geoJSON(data).addTo(map);
            console.log(geo);

            map.fitBounds(geo.getBounds());
          });
        };

        reader.readAsArrayBuffer(this.fileMap[i]);
      }
    },
    async getListSupporttable(idProject) {
      const { data } = await SupportDocResource.list({ idProject });

      return data.map((e) => {
        return { fileDoc: { name: e.file }, ...e };
      });
    },
    getFileName(value) {
      const onlyName = value.split('/');

      return onlyName.at(-1);
    },
    kbliSearch(queryString, cb) {
      var links = this.$store.getters.kblis;
      var results = queryString
        ? links.filter(this.createKbliFilter(queryString))
        : links;
      // call callback function to return suggestions
      cb(results);
    },
    createKbliFilter(queryString) {
      return (link) => {
        return (
          link.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0
        );
      };
    },
    checkMapFile() {
      document.querySelector('#mapFile').click();
    },
    checkMapFileSure(e) {
      this.fileName = e.target.files[0].name;
      this.fileMap = e.target.files[0];
      this.$refs.fileMapUpload.clearValidate();
    },
    async getAllData() {
      await this.getProjectFields();
      await this.getProvinces();
      await this.getSectors();
      await this.getKblis();
      await this.getProjectsFromOss();
    },
    async getProjectsFromOss() {
      await this.$store.dispatch('getProjectOss');
    },
    async getProjectFields() {
      await this.$store.dispatch('getProjectFields');
    },
    async getProvinces() {
      await this.$store.dispatch('getProvinces');
    },
    handleCancel() {
      this.$router.push('/project');
    },
    handleSubmit() {
      this.currentProject.fileMap = this.fileMap;
      this.currentProject.fileKtr = this.fileKtr;

      this.$refs.currentProject.validate((valid) => {
        if (valid) {
          this.currentProject.listSupportDoc = this.listSupportTable.filter(
            (item) => item.name && item.file
          );

          // send to pubishProjectRoute
          this.$router.push({
            name: 'publishProject',
            params: { project: this.currentProject, mapUpload: this.fileMap },
          });
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    async changeProvince(value) {
      // change all district by province
      this.getDistricts(value);
    },
    changeStudyApproach(value) {

    },
    changeStatus(value) {

    },
    changeProjectType(value) {

    },
    async changeProject(value) {
      this.currentProject.project_title = value.nama_kegiatan;
      this.currentProject.id_project = value.id_proyek;
      this.currentProject.location_desc = value.alamat_usaha;
      this.currentProject.description = value.deskripsi_kegiatan;
      this.currentProject.kbli = value.kbli;
      this.currentProject.risk_level = value.skala_resiko;
      this.getSectorsByKbli(this.currentProject.kbli);
      this.getBusinessByKbli(this.currentProject.kbli);
    },
    async getDistricts(idProv) {
      await this.$store.dispatch('getDistricts', { idProv });
    },
    async getSectors() {
      await this.$store.dispatch('getSectors', { sectors: true });
    },
    async getSectorsByKbli(nameKbli) {
      await this.$store.dispatch('getSectors', {
        sectorsByKbli: nameKbli,
      });
    },
    async getUnitByKbli(nameKbli, businessType) {
      await this.$store.dispatch('getUnitByKbli', {
        kbli: nameKbli,
        businessType,
        unit: true,
      });
    },
    async getBusinessByKbli(nameKbli) {
      await this.$store.dispatch('getBusinessByKbli', {
        kbli: nameKbli,
        businessType: true,
      });
    },
    async getKblis() {
      await this.$store.dispatch('getKblis', { kblis: true });
    },
    handleAddSupportTable() {
      this.listSupportTable.push({});
    },
    handleAddSubProjectTable() {
      this.listSubProject.push({});
    },
    handleKbliSelect(item) {
      this.getSectorsByKbli(item.value);
      this.getBusinessByKbli(item.value);
      // this.currentProject.biz_type = '';
      // this.currentProject.sector = '';
    },
    handleBusinessTypeSelect(item) {
      this.getUnitByKbli(this.currentProject.kbli, item);
    },
  },
};
</script>
<style>
@import url('../../../../node_modules/leaflet/dist/leaflet.css');

.el-collapse-item__header {
  /* background-color: #296d36; */
  background-color: #1E5128;
  padding-left: 10px;
  font-size: large;
  font-weight: bold;
  color: rgb(196, 196, 196);
}
.el-collapse-item__content {
  padding-top: 10px;
}

</style>
