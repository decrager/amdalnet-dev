<template>
  <div class="form-container" style="padding: 24px">
    <el-form
      ref="categoryForm"
      :model="currentProject"
      label-position="top"
      label-width="200px"
      style="max-width: 100%"
    >
      <el-row :gutter="4">
        <el-col :span="12" :xs="24">
          <el-col :span="16" :xs="24">
            <el-form-item
              label="Pilih Kegiatan Yang Sudah Diproses di OSS"
              prop="titleProject"
            >
              <el-select
                v-model="currentProject.project_title"
                placeholder="Select"
                style="width: 100%"
                @change="changeProject($event)"
              >
                <el-option
                  v-for="item in projectOptions"
                  :key="item.value.id"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="8" :xs="24">
            <el-form-item label="Jenis Kegiatan" prop="jenisKegiatan">
              <el-select
                v-model="currentProject.project_type"
                placeholder="Select"
                style="width: 100%"
              >
                <el-option
                  v-for="item in projectTypeOptions"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
            </el-form-item>
          </el-col>
        </el-col>
        <el-col :span="12" :xs="24">
          <el-col :span="12">
            <el-form-item label="Provinsi" prop="provinsi">
              <el-select
                v-model="currentProject.id_prov"
                placeholder="Select"
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
            <el-form-item label="Kab / Kota" prop="kabkot">
              <el-select
                v-model="currentProject.id_district"
                placeholder="Select"
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
            </el-form-item>
          </el-col>
        </el-col>
      </el-row>
      <el-row :gutter="4">
        <el-col :span="12" :xs="24">
          <el-col :span="8">
            <el-form-item label="KBLI" prop="kbli">
              <!-- <el-input v-model="currentProject.kbli" /> -->
              <el-autocomplete
                v-model="currentProject.kbli"
                :disabled="isOss"
                class="inline-input"
                :fetch-suggestions="kbliSearch"
                placeholder="Please Input"
                :trigger-on-focus="false"
                @select="handleKbliSelect"
              />
            </el-form-item>
          </el-col>
          <el-col :span="8">
            <el-form-item label="Tingkat Resiko" prop="riskLevel">
              <el-input v-model="currentProject.risk_level" :disabled="isOss" />
            </el-form-item>
          </el-col>
          <el-col :span="8" :xs="24">
            <el-form-item label="Tahun Kegiatan" prop="projectYear">
              <el-date-picker
                v-model="currentProject.project_year"
                type="year"
                placeholder="Pick a year"
                style="width: 100%"
                value-format="yyyy"
              />
            </el-form-item>
          </el-col>
        </el-col>
        <el-col :span="12">
          <el-col :span="12">
            <el-form-item label="Bidang Kegiatan" prop="fieldProject">
              <el-select
                v-model="currentProject.field"
                placeholder="Select"
                style="width: 100%"
              >
                <el-option
                  v-for="item in projectFieldOptions"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="Alamat" prop="address">
              <el-input v-model="currentProject.address" />
            </el-form-item>
          </el-col>
        </el-col>
      </el-row>
      <el-row :gutter="4">
        <el-col
          :span="12"
          :xs="24"
        ><el-col :span="8">
           <el-form-item label="Sector" prop="project">
             <el-select
               v-model="currentProject.sector"
               placeholder="Select"
               style="width: 100%"
             >
               <el-option
                 v-for="item in sectorOptions"
                 :key="item.value"
                 :label="item.label"
                 :value="item.value"
               />
             </el-select>
           </el-form-item>
         </el-col>
          <el-col :span="8">
            <el-form-item label="Jenis Usaha" prop="businessType">
              <el-select
                v-model="currentProject.biz_type"
                placeholder="Select"
                style="width: 100%"
                @change="handleBusinessTypeSelect($event)"
              >
                <el-option
                  v-for="item in businessTypeOptions"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="8">
            <el-form-item label="Skala/Besaran Kegiatan" prop="projectScale">
              <el-col
                :span="12"
              ><el-input
                v-model="currentProject.scale"
              /></el-col>
              <el-col
                :span="12"
              ><el-select
                v-model="currentProject.scale_unit"
                placeholder="Select"
                style="width: 100%"
              >
                <el-option
                  v-for="item in unitOptions"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                /> </el-select></el-col>
            </el-form-item> </el-col></el-col>
        <el-col :span="12" :xs="24">
          <el-col :span="24">
            <el-form-item label="Peta Tapak Proyek" prop="projectMap">
              <el-col :span="8">
                <el-radio-group v-model="isUpload">
                  <el-radio-button label="Upload" />
                  <el-radio-button label="WebGIS" />
                </el-radio-group>
              </el-col>
              <el-col :span="16">
                <div
                  style="
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    height: 36px;
                  "
                >
                  <el-button
                    icon="el-icon-document-copy"
                    type="primary"
                    size="mini"
                    style="margin-left: 15px"
                    @click="checkMapFile"
                  >Upload</el-button>
                  <span>{{ fileName }}</span>
                  <input
                    id="mapFile"
                    type="file"
                    style="display: none"
                    @change="checkMapFileSure"
                  >
                </div>
              </el-col>
            </el-form-item>
          </el-col>
        </el-col>
      </el-row>
      <el-row :gutter="4">
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
        <el-col :span="12" :xs="24" style="text-align: center">
          <h1>Map will be here!!</h1>
        </el-col>
      </el-row>
      <el-row :gutter="16">
        <el-col :span="12" :xs="24">
          <el-form-item
            prop="locationDesc"
            style="margin-bottom: 30px"
            label="Deskripsi Lokasi"
          >
            <tinymce
              ref="editor"
              v-model="currentProject.location_desc"
              :height="200"
            />
          </el-form-item>
        </el-col>
        <el-col :span="12" :xs="24">
          <el-form-item
            prop="ProjectDesc"
            style="margin-bottom: 30px"
            label="Deskripsi Kegiatan"
          >
            <tinymce
              ref="editor"
              v-model="currentProject.description"
              :height="200"
            />
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <div slot="footer" class="dialog-footer">
      <el-button @click="handleCancel()"> Cancel </el-button>
      <el-button type="primary" @click="handleSubmit()"> Confirm </el-button>
    </div>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';
import Resource from '@/api/resource';
import SupportTable from './components/SupportTable.vue';
const projectFieldResource = new Resource('project-fields');
const provinceResource = new Resource('provinces');
const districtResource = new Resource('districts');
const kbliResource = new Resource('kblis');
const kbliEnvParamResource = new Resource('kbli-env-params');
const ossProjectResource = new Resource('oss-projects');
const SupportDocResource = new Resource('support-docs');

export default {
  name: 'CreateProject',
  components: { Tinymce, SupportTable },
  data() {
    return {
      currentProject: {},
      listSupportTable: [],
      listKbli: [],
      loadingSupportTable: false,
      isUpload: 'Upload',
      fileName: 'No File Selected.',
      fileMap: null,
      isOss: true,
      projectOptions: [],
      projectFieldOptions: [],
      sectorOptions: [],
      provinceOptions: [],
      cityOptions: [],
      businessTypeOptions: [],
      projectTypeOptions: [
        {
          value: 'Baru',
          label: 'Baru',
        },
        {
          value: 'Perpanjangan',
          label: 'Perpanjangan',
        },
      ],
      unitOptions: [],
    };
  },
  async mounted() {
    if (this.$route.params.project) {
      this.currentProject = this.$route.params.project;
      this.fileName = this.getFileName(this.currentProject.map);
      this.listSupportTable = await this.getListSupporttable(this.currentProject.id);
      this.getDistricts(this.currentProject.id_prov);
      console.log(this.listSupportTable);
    }
    this.getAllData();
  },
  methods: {
    async getListSupporttable(idProject){
      const { data } = await SupportDocResource.list({ idProject });

      return data.map(e => {
        return { fileDoc: { name: e.file }, ...e };
      });
    },
    getFileName(value){
      console.log(value);
      const onlyName = value.split('/');

      return onlyName.at(-1);
    },
    kbliSearch(queryString, cb) {
      var links = this.listKbli;
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
    },
    async getAllData() {
      this.getProjectFields();
      this.getProvinces();
      this.getSectors();
      this.getKblis();
      this.getProjectsFromOss();
    },
    async getProjectsFromOss() {
      const { data } = await ossProjectResource.list({});
      this.projectOptions = data.map((i) => {
        return { value: i.json_content, label: i.json_content.nama_kegiatan };
      });
    },
    async getProjectFields() {
      const { data } = await projectFieldResource.list({});
      this.projectFieldOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },
    async getProvinces() {
      const { data } = await provinceResource.list({});
      this.provinceOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },
    handleCancel() {
      this.$router.push('/project');
    },
    handleSubmit() {
      this.currentProject.fileMap = this.fileMap;
      console.log(this.listSupportTable);
      this.currentProject.listSupportDoc = this.listSupportTable.filter(item => item.name && item.file);
      console.log(this.currentProject);

      // send to pubishProjectRoute
      this.$router.push({
        name: 'publishProject',
        params: { project: this.currentProject },
      });
    },
    async changeProvince(value) {
      // change all district by province
      this.getDistricts(value);
    },
    async changeProject(value) {
      this.currentProject.project_title = value.nama_kegiatan;
      this.currentProject.id_project = value.id_proyek;
      this.currentProject.location = value.alamat_usaha;
      this.currentProject.description = value.deskripsi_kegiatan;
      this.currentProject.kbli = value.kbli;
      this.currentProject.risk_level = value.skala_resiko;
      this.getSectorsByKbli(this.currentProject.kbli);
      this.getBusinessByKbli(this.currentProject.kbli);
      console.log(value);
    },
    async getDistricts(idProv) {
      const { data } = await districtResource.list({ idProv });
      this.cityOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },
    async getSectors() {
      const { data } = await kbliResource.list({ sectors: true });
      this.sectorOptions = data.map((i) => {
        return { value: i.value, label: i.value };
      });
    },
    async getSectorsByKbli(nameKbli) {
      const { data } = await kbliResource.list({
        sectorsByKbli: nameKbli,
      });
      this.sectorOptions = data.map((i) => {
        return { value: i.value, label: i.value };
      });
    },
    async getUnitByKbli(nameKbli, businessType) {
      const { data } = await kbliEnvParamResource.list({
        kbli: nameKbli,
        businessType,
        unit: true,
      });
      this.unitOptions = data.map((i) => {
        return { value: i.unit, label: i.unit };
      });
    },
    async getBusinessByKbli(nameKbli) {
      const { data } = await kbliEnvParamResource.list({
        kbli: nameKbli,
        businessType: true,
      });
      this.businessTypeOptions = data.map((i) => {
        return { value: i.param, label: i.param };
      });
    },
    async getKblis() {
      const { data } = await kbliResource.list({ kblis: true });
      this.listKbli = data.map((i) => {
        return { value: i.value, label: i.value };
      });
    },
    handleAddSupportTable() {
      this.listSupportTable.push({});
    },
    handleKbliSelect(item) {
      this.getSectorsByKbli(item.value);
      this.getBusinessByKbli(item.value);
    },
    handleBusinessTypeSelect(item) {
      this.getUnitByKbli(this.currentProject.kbli, item);
    },
  },
};
</script>
