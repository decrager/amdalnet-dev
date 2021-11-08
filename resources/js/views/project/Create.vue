<template>
  <div class="form-container" style="padding: 24px">
    <el-form
      ref="currentProject"
      :model="currentProject"
      :rules="currentProjectRules"
      label-position="top"
      label-width="200px"
      style="max-width: 100%"
    >
      <div v-if="preProject">
        <el-collapse v-model="activeName">
          <el-collapse-item title="TAPAK PROYEK" name="1">
            <el-row :gutter="4">
              <el-col :span="12">
                <el-form-item
                  label="Pilih Kegiatan"
                  prop="project_title"
                >
                  <el-select
                    v-model="currentProject.project_title"
                    placeholder="Pilih"
                    style="width: 100%"
                    size="medium"
                    @change="changeProject($event)"
                  >
                    <el-option
                      v-for="item in getProjectOption"
                      :key="item.value.id"
                      :label="item.label"
                      :value="item.value"
                    />
                  </el-select>
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
                  <el-form-item label="Upload Peta Tapak Proyek(File SHP)">
                    <input ref="fileMap" type="file" class="el-input__inner" @change="handleFileMapUpload()">
                  </el-form-item>
                </el-row>
                <h1>PETA</h1>
                <el-row />
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
                    :disabled="true"
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
                    :disabled="true"
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
                    :disabled="true"
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
          </el-collapse-item>
        </el-collapse>
        <div slot="footer" class="dialog-footer" style="margin-top: 10px;">
          <el-button size="medium" @click="handleCancel()"> Batalkan </el-button>
          <el-button size="medium" type="primary" @click="preProject = false"> Lanjutkan </el-button>
        </div>
      </div>

      <div v-else>

        <el-row :gutter="4">
          <el-col :span="12" :xs="24">
            <el-col :span="16" :xs="24">
              <el-form-item
                label="Nama Usaha / Kegiatan"
                prop="project_title"
              >
                <el-select
                  v-model="currentProject.project_title"
                  placeholder="Pilih"
                  style="width: 100%"
                  @change="changeProject($event)"
                >
                  <el-option
                    v-for="item in getProjectOption"
                    :key="item.value.id"
                    :label="item.label"
                    :value="item.value"
                  />
                </el-select>
              </el-form-item>
            </el-col>
            <el-col :span="8" :xs="24">
              <el-form-item label="Jenis Kegiatan" prop="project_type">
                <el-select
                  v-model="currentProject.project_type"
                  placeholder="Pilih"
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
                  placeholder="Masukan"
                  :trigger-on-focus="false"
                  @select="handleKbliSelect"
                />
              </el-form-item>
            </el-col>
            <el-col :span="8">
              <el-form-item label="Tingkat Resiko" prop="risk_level">
                <el-input v-model="currentProject.risk_level" :disabled="isOss" />
              </el-form-item>
            </el-col>
            <el-col :span="8" :xs="24">
              <el-form-item label="Tahun Kegiatan" prop="projectYear">
                <el-date-picker
                  v-model="currentProject.project_year"
                  type="year"
                  placeholder="Pilih Tahun"
                  style="width: 100%"
                  value-format="yyyy"
                />
              </el-form-item>
            </el-col>
          </el-col>
          <el-col :span="12">
            <el-col :span="12">
              <el-form-item label="Bidang Kegiatan" prop="field">
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
             <el-form-item label="Sektor" prop="sector">
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
            <el-col :span="8">
              <el-form-item label="Jenis Usaha" prop="biz_type">
                <el-select
                  v-model="currentProject.biz_type"
                  placeholder="Pilih"
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
              <el-col :span="12">
                <el-form-item label="Skala/Besaran" prop="scale">
                  <el-input v-model="currentProject.scale" /></el-form-item></el-col>
              <el-col :span="12">
                <el-form-item label="Unit" prop="scale_unit">
                  <el-select
                    v-model="currentProject.scale_unit"
                    placeholder="Pilih"
                    style="width: 100%"
                  >
                    <el-option
                      v-for="item in getUnitOptions"
                      :key="item.value"
                      :label="item.label"
                      :value="item.value"
                    />
                  </el-select> </el-form-item></el-col> </el-col></el-col>
          <el-col :span="12" :xs="24">
            <!-- <el-col :span="24">
              <el-form-item
                ref="fileMapUpload"
                label="Peta Tapak Proyek"
                prop="fileMap"
              >
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
            </el-col> -->
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
          <!-- <el-col :span="12" :xs="24" style="text-align: center">
            <h1>Map will be here!!</h1>
          </el-col> -->
        </el-row>
        <el-row :gutter="16">
          <el-col :span="12" :xs="24">
            <el-form-item
              prop="location_desc"
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
              prop="description"
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
      </div>
    </el-form>

    <div v-if="!preProject" slot="footer" class="dialog-footer">
      <el-button @click="preProject = true"> Batalkan </el-button>
      <el-button type="primary" @click="handleSubmit()"> Lanjutkan </el-button>
    </div>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';
import Resource from '@/api/resource';
import SupportTable from './components/SupportTable.vue';
import 'vue-simple-accordion/dist/vue-simple-accordion.css';
const kbliEnvParamResource = new Resource('kbli-env-params');
const SupportDocResource = new Resource('support-docs');

export default {
  name: 'CreateProject',
  components: {
    Tinymce,
    SupportTable,
  },
  data() {
    return {
      preProject: true,
      activeName: 1,
      currentProject: {},
      listSupportTable: [],
      listKbli: [],
      loadingSupportTable: false,
      isUpload: 'Upload',
      fileName: 'No File Selected.',
      fileMap: null,
      isOss: true,
      businessTypeOptions: [],
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
  },
  async created() {
    // for default value
    this.currentProject.study_approach = 'Tunggal';
    this.currentProject.status = 'Rencana';
    this.currentProject.project_type = 'Baru';

    if (this.$route.params.project) {
      this.currentProject = this.$route.params.project;
      this.fileName = this.getFileName(this.currentProject.map);
      this.fileMap = this.getFileName(this.currentProject.map);
      this.listSupportTable = await this.getListSupporttable(
        this.currentProject.id
      );
      this.getDistricts(this.currentProject.id_prov);
      console.log(this.listSupportTable);
    }
    this.getAllData();
  },
  methods: {
    handleFileKtrUpload(){
      this.fileKtr = this.$refs.fileKtr.files[0];
    },
    handleFileMapUpload(){
      this.fileMap = this.$refs.fileMap.files[0];
    },
    async getListSupporttable(idProject) {
      const { data } = await SupportDocResource.list({ idProject });

      return data.map((e) => {
        return { fileDoc: { name: e.file }, ...e };
      });
    },
    getFileName(value) {
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
      this.$refs.currentProject.validate((valid) => {
        if (valid) {
          this.currentProject.listSupportDoc = this.listSupportTable.filter(
            (item) => item.name && item.file
          );

          // send to pubishProjectRoute
          this.$router.push({
            name: 'publishProject',
            params: { project: this.currentProject },
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
      console.log(value);
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
      const { data } = await kbliEnvParamResource.list({
        kbli: nameKbli,
        businessType: true,
      });
      this.businessTypeOptions = data.map((i) => {
        return { value: i.param, label: i.param };
      });
    },
    async getKblis() {
      await this.$store.dispatch('getKblis', { kblis: true });
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
<style>
.el-collapse-item__header {
  background-color: #296d36;
  padding-left: 10px;
  font-size: large;
  font-weight: bold;
  color: rgb(196, 196, 196);
}
.el-collapse-item__content {
  padding-top: 10px;
}

</style>
