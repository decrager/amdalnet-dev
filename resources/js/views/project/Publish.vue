<template>

  <div v-loading="fullLoading" class="form-container" style="margin: 2em;">
    <el-card id="element-to-convert" class="box-card">
      <workflow :is-penapisan="true" />
      <el-row :gutter="10">
        <el-col :span="12">
          <h2>Informasi rencana Usaha/Kegiatan</h2>
          <el-table
            :data="list"
            style="width: 100%"
            :stripe="true"
            :show-header="false"
          >
            <el-table-column prop="param" />
            <el-table-column prop="value" />
          </el-table>
        </el-col>
        <el-col :span="12">
          <div>
            <div id="mapView" />
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="10" style="padding-top: 32px">
        <el-col :span="12">
          <div><h3>Deskripsi Kegiatan</h3></div>
          <div v-html="project.description" />
        </el-col>
        <el-col :span="12">
          <div><h3>Deskripsi Lokasi</h3></div>
          <div v-html="project.location_desc" />
        </el-col>
      </el-row>
      <el-row :gutter="10">
        <el-col :span="12">
          <div>
            <el-table :data="tableData" :span-method="arraySpanMethod" style="margin-top: 20px" :header-cell-style="{ background: '#099C4B', color: 'white' }" :row-class-name="tableRowClassName">
              <el-table-column
                prop="no"
                label="No."
                width="50px"
              />
              <!-- <el-table-column
              prop="kbli"
              label="KBLI"
            /> -->
              <el-table-column
                prop="kegiatan"
                label="Kegiatan"
              />
              <el-table-column
                prop="jenisKegiatan"
                label="Jenis Kegiatan"
              />
              <el-table-column
                prop="skala"
                label="Skala Besaran"
              />
              <!-- <el-table-column
            prop="hasil"
            label="Hasil"
          /> -->
            </el-table>
          </div>
        </el-col>
        <el-col :span="12">
          <div>
            <el-table :data="project.address" :span-method="arraySpanMethod" style="margin-top: 20px" :header-cell-style="{ background: '#099C4B', color: 'white' }">
              <el-table-column
                label="No."
                width="50px"
              >
                <template slot-scope="scope">
                  {{ scope.$index + 1 }}
                </template>
              </el-table-column>
              <el-table-column
                prop="prov"
                label="Provinsi"
              />
              <el-table-column
                prop="district"
                label="Kota"
              />
              <el-table-column
                prop="address"
                label="Alamat"
              />
            </el-table>
          </div>
        </el-col>
      </el-row>
      <el-row :gutter="4">
        <el-col :span="12">
          <h2>Hasil Penapisan Rencana Kegiatan</h2>
          <div v-if="!isPemerintah" role="alert" class="el-alert el-alert--info is-light space">
            <i class="el-alert__icon el-icon-warning is-big" />
            <div class="el-alert__content">
              <p class="el-alert__description">
                Permohonan Persetujuan Lingkungan dengan tingkat Risiko <b>Rendah</b> atau <b>Menengah Rendah</b> dilakukan secara otomatis melalui sistem <b>OSS RBA</b>
              </p>
            </div>
            <br><br>
          </div>
          <el-row style="padding-bottom: 16px"><el-col :span="12">No Registrasi</el-col>
            <el-col :span="12">{{ project.registration_no || "Belum Mempunyai" }}</el-col></el-row>
          <el-row style="padding-bottom: 16px"><el-col :span="12">Jenis Dokumen</el-col>
            <el-col :span="12">{{ project.required_doc }}</el-col></el-row>
          <el-row v-show="isRiskShow" style="padding-bottom: 16px"><el-col :span="12">Tingkat Risiko</el-col>
            <el-col :span="12">{{ project.oss_risk || project.result_risk }}</el-col>
            <!-- <el-col v-if="isFromOSS || isTUK" :span="12">{{ project.result_risk }}</el-col> -->
          </el-row>
          <el-row style="padding-bottom: 16px"><el-col :span="12">Kewenangan</el-col>
            <el-col :span="12">{{ project.authority }}</el-col></el-row>
        </el-col>
        <el-col :span="12" />
      </el-row>
      <div class="dialog-footer">
        <el-button :disabled="readonly" @click="handleCancel()"> Kembali </el-button>
        <el-button v-loading="" type="primary" :disabled="readonly" @click="handleSubmit()"> Simpan </el-button>
        <el-button @click="printOrAlert()">Cetak PDF</el-button>
      </div>
      <div>
        <img id="gambar" src="">
      </div>
    </el-card>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Resource from '@/api/resource';
const kbliEnvParamResource = new Resource('kbli-env-params');
const districtResource = new Resource('districts');
const provinceResource = new Resource('provinces');
const projectResource = new Resource('projects');
const initiatorByEmailResource = new Resource('initiatorsByEmail');
const initiatorResource = new Resource('initiators');
const formulatorTeamsResource = new Resource('formulator-teams');

import Map from '@arcgis/core/Map';
import MapView from '@arcgis/core/views/MapView';
// import Attribution from '@arcgis/core/widgets/Attribution';
import urlBlob from '../webgis/scripts/urlBlob';
import Expand from '@arcgis/core/widgets/Expand';
import Legend from '@arcgis/core/widgets/Legend';
import LayerList from '@arcgis/core/widgets/LayerList';
import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';
import shp from 'shpjs';
import Workflow from '@/components/Workflow';
import axios from 'axios';
import popupTemplate from '../webgis/scripts/popupTemplate';

export default {
  name: 'Publish',
  components: { Workflow },
  props: {
    project: {
      type: Object,
      default: null,
    },
    readonly: {
      type: Boolean,
      default: false,
    },
    mapUpload: {
      type: File,
      default: () => null,
    },
    mapUploadPdf: {
      type: File,
      default: () => null,
    },
    isOSS: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      geomFromGeojson: {},
      geomProperties: {},
      kbliEnvParams: null,
      doc_req: 'SPPL',
      risk_level: 'Rendah',
      teamOptions: [{ value: 'mandiri', label: 'Penyusun Perseorangan' }, { value: 'lpjp', label: 'Lembaga Penyedia Jasa Penyusun' }],
      // teamToChooseOptions: null,
      kabkot: null,
      list: [],
      listFormulatorTeam: [],
      listExpertTeam: [],
      // projectRules: {
      //   id_drafting_team: [{ required: true, trigger: 'change', message: 'Data Belum Dipilih' }],
      // },
      initiatorData: {},
      geojson: null,
      all: [],
      mapList: [],
      tableData: [],
      addressTableData: [],
      province: null,
      fullLoading: false,
      geomStyles: 1,
      mapPdf: '',
      mapView: null,
      mapGeojsonArrayProject: [],
    };
  },
  beforeRouteLeave(to, from, next) {
    if (from.name === 'publishProject' && to.name === 'createProject') {
      if (!to.params.project){
        next(false);
      } else {
        next();
      }
    } else {
      next();
    }
  },
  computed: {
    ...mapGetters({
      'userInfo': 'user',
      'userId': 'userId',
    }),
    getFormulatedTeam(){
      return this.$store.getters.teamType;
    },
    getLpjps(){
      return this.$store.getters.lpjps;
    },
    isTUK() {
      return this.userInfo.roles.some((role) => role.includes('examiner'));
    },
    isPemerintah(){
      return this.$store.getters.isPemerintah;
    },
    isRiskShow() {
      if (this.project.required_doc != null) {
        if (this.$store.getters.isPemerintah) {
          return false;
        }
      }

      return true;
    },
    isProjectIdExist() {
      return this.project.id;
    },
    isFromOSS() {
      return this.isOSS;
    },
  },
  async mounted() {
    console.log(this.project, this.list);
    this.fullLoading = true;
    // for step
    this.$store.dispatch('getStep', 1);
    // await this.getTeamOptions();
    // console.log('OKE', this.project.isPemerintah === 'true');
    await this.getInitiatorData();
    await this.updateList();
    this.setDataTables();
    this.setAddressDataTables();
    this.fullLoading = false;
    this.loadMap();
    await this.$store.dispatch('getLpjp');
    await this.$store.dispatch('getFormulators', {
      page: 1,
      limit: 1000,
      active: 'true',
    });
    this.getMapPdf();
  },
  methods: {
    async print() {
      document.body.style.cursor = 'wait';
      const options = {
        width: 800,
        height: 600,
      };
      const formData = new FormData();
      formData.append('project_id', this.project.id);
      const screenshot = await this.mapView.takeScreenshot(options);

      formData.append('imageUrl', screenshot.dataUrl);

      await axios.post('api/print-penapisan', formData).then((response) => {
        document.body.style.cursor = 'default';
        const link = document.createElement('a');
        link.href = response.data;
        link.click();
      });
    },
    tableRowClassName({ row, rowIndex }) {
      if (row.no === 'A' || row.no === 'B') {
        return 'bold-row';
      }

      return '';
    },
    printOrAlert(){
      if (this.isProjectIdExist){
        return this.print();
      } else {
        return this.$alert('Cetak penapisan hanya bisa digunakan setelah Penapisan di simpan.', 'Informasi', {
          confirmButtonText: 'OK',
        });
      }
    },
    setAddressDataTables(){
      this.addressTableData.push({
        no: '1',
        province: this.province,
        city: this.kabkot,
        address: this.project.address,
      });
    },
    setDataTables(){
      const mainArr = this.project.listSubProject.filter(e => e.type === 'utama').map((e, index) => {
        var rmDecimalMain = e.scale;
        var	reverse = rmDecimalMain.toString().split('').reverse().join(''), projectScale = reverse.match(/\d{1,3}/g);
        projectScale = projectScale.join('.').split('').reverse().join('');
        return {
          no: index + 1,
          kbli: e.kbli,
          kegiatan: e.name,
          jenisKegiatan: e.biz_name,
          skala: projectScale + ' ' + (e.scale_unit || ''),
          hasil: e.result,
        };
      });
      const suppArr = this.project.listSubProject.filter(e => e.type === 'pendukung').map((e, index) => {
        var rmDecimalSupp = e.scale;
        var	reverse = rmDecimalSupp.toString().split('').reverse().join(''), projectScale = reverse.match(/\d{1,3}/g);
        projectScale = projectScale.join('.').split('').reverse().join('');
        return {
          no: index + 1,
          kbli: e.kbli,
          kegiatan: e.name,
          jenisKegiatan: e.biz_name,
          skala: projectScale + ' ' + (e.scale_unit || ''),
          hasil: e.result,
        };
      });

      if (mainArr.length > 0){
        mainArr.unshift({
          no: 'A',
          kegiatan: 'Kegiatan Utama',
        });
      }

      if (suppArr.length > 0){
        suppArr.unshift({
          no: 'B',
          kegiatan: 'Kegiatan Pendukung',
        });
      }

      this.tableData = [...mainArr, ...suppArr];
    },
    arraySpanMethod({ row, column, rowIndex, columnIndex }) {
      if (row.scale) {
        if (columnIndex === 1) {
          return [1, 3];
        } else if (columnIndex === 2) {
          return [0, 0];
        }
      }
    },
    defineActions(event) {
      const item = event.item;

      item.actionsSections = [
        [
          {
            title: 'Go to full extent',
            className: 'esri-icon-zoom-in-magnifying-glass',
            id: 'full-extent',
          },
        ],
      ];
    },
    loadMap() {
      // let layerTapak = null;
      const map = new Map({
        basemap: 'satellite',
      });
      if (this.readonly === true) {
        axios.get(`api/map-geojson?id=${this.project.id}`)
          .then((response) => {
            response.data.forEach((item) => {
              const getType = JSON.parse(item.feature_layer);
              const propType = getType.features[0].properties.type;
              const propFields = getType.features[0].properties.field;
              const propStyles = getType.features[0].properties.styles;
              const step = getType.features[0].properties.step;

              // Tapak
              if (propType === 'tapak' && step === 'ka') {
                const geojsonLayerArray = new GeoJSONLayer({
                  url: urlBlob(item.feature_layer),
                  outFields: ['*'],
                  visible: true,
                  title: 'Layer Tapak Proyek',
                  renderer: propStyles,
                  popupTemplate: popupTemplate(propFields),
                });

                mapView.on('layerview-create', async(event) => {
                  await mapView.goTo({
                    target: geojsonLayerArray.fullExtent,
                  });
                });

                this.mapGeojsonArrayProject.push(geojsonLayerArray);
              }

              // Pemantauan
              if (propType === 'pemantauan') {
                const geojsonLayerArray = new GeoJSONLayer({
                  url: urlBlob(item.feature_layer),
                  outFields: ['*'],
                  visible: true,
                  title: 'Layer Titik Pemantauan',
                  renderer: propStyles,
                  popupTemplate: popupTemplate(propFields),
                });

                this.mapGeojsonArrayProject.push(geojsonLayerArray);
              }

              // Area Pemantauan
              if (propType === 'area-pemantauan') {
                const geojsonLayerArray = new GeoJSONLayer({
                  url: urlBlob(item.feature_layer),
                  outFields: ['*'],
                  visible: true,
                  title: 'Layer Area Pemantauan',
                  renderer: propStyles,
                  popupTemplate: popupTemplate(propFields),
                });

                this.mapGeojsonArrayProject.push(geojsonLayerArray);
              }

              // Pengelolaan
              if (propType === 'pengelolaan') {
                const geojsonLayerArray = new GeoJSONLayer({
                  url: urlBlob(item.feature_layer),
                  outFields: ['*'],
                  visible: true,
                  title: 'Layer Titik Pengelolaan',
                  renderer: propStyles,
                  popupTemplate: popupTemplate(propFields),
                });

                this.mapGeojsonArrayProject.push(geojsonLayerArray);
              }

              // Area Pengelolaan
              if (propType === 'area-pengelolaan') {
                const geojsonLayerArray = new GeoJSONLayer({
                  url: urlBlob(item.feature_layer),
                  outFields: ['*'],
                  visible: true,
                  title: 'Layer Area Pengelolaan',
                  renderer: propStyles,
                  popupTemplate: popupTemplate(propFields),
                });

                this.mapGeojsonArrayProject.push(geojsonLayerArray);
              }
              map.addMany(this.mapGeojsonArrayProject);
            });
          });
      } else {
        const fr = new FileReader();
        fr.onload = (event) => {
          const base = event.target.result;
          shp(base).then((data) => {
            // this.geomFromGeojson = data.features[0].geometry;
            // this.geomProperties = data.features[0].properties;
            this.geomFromGeojson = [];
            this.geomProperties = [];

            data.features.map((value, index) => {
              this.geomFromGeojson.push(value.geometry);
              this.geomProperties.push(value.properties);
            });

            const blob = new Blob([JSON.stringify(data)], {
              type: 'application/json',
            });

            const renderer = {
              type: 'simple',
              field: '*',
              symbol: {
                type: 'simple-fill',
                color: [200, 0, 0, 1],
                outline: {
                  color: [200, 0, 0, 1],
                },
              },
            };
            const url = URL.createObjectURL(blob);
            const geojsonLayer = new GeoJSONLayer({
              url: url,
              visible: true,
              outFields: ['*'],
              opacity: 0.75,
              title: 'Peta Tapak Proyek',
              renderer: renderer,
            });

            map.add(geojsonLayer);
            this.mapView.on('layerview-create', (event) => {
              this.mapView.goTo({
                target: geojsonLayer.fullExtent,
              });
            });
          });
        };
        fr.readAsArrayBuffer(this.mapUpload);
      }

      const mapView = new MapView({
        container: 'mapView',
        map: map,
        center: [115.287, -1.588],
        zoom: 6,
      });

      this.mapView = mapView;

      const legend = new Legend({
        view: mapView,
        container: document.createElement('div'),
      });
      const layerList = new LayerList({
        view: mapView,
        container: document.createElement('div'),
        listItemCreatedFunction: this.defineActions,
      });

      layerList.on('trigger-action', (event) => {
        const id = event.action.id;
        if (id === 'full-extent') {
          mapView.goTo({
            target: event.item.layer.fullExtent,
          });
        }
      });

      const legendExpand = new Expand({
        view: mapView,
        content: legend.domNode,
        expandIconClass: 'esri-icon-collection',
        expandTooltip: 'Legend',
      });

      mapView.ui.add(legendExpand, 'bottom-left');
      mapView.ui.add(layerList, 'top-right');
    },
    handleAddExpertTable(){
      this.listExpertTeam.push({});
    },
    handleAddFormulatorTable(){
      this.listFormulatorTeam.push({});
    },
    onFormulatorTypeChange(value){
      this.$store.dispatch('getTeamType', value);
    },
    async getInitiatorData(){
      if (this.project.id_applicant){
        this.project.initiatorData = await initiatorResource.get(this.project.id_applicant);
      } else {
        // const data = await this.$store.dispatch('user/getInfo');
        this.project.initiatorData = await initiatorByEmailResource.list({ email: this.userInfo.email });
      }
    },
    // async getTeamOptions() {
    //   const { data } = await formulatorTeamResource.list({});
    //   this.teamToChooseOptions = data.map((i) => {
    //     return { value: i.id, label: i.name };
    //   });
    // },
    async getKabKotName(id) {
      const data = await districtResource.get(id);
      this.kabkot = data.name;
    },
    async getProvince(id) {
      const data = await provinceResource.get(id);
      this.province = data.name;
    },
    calculatedProjectDoc() {
      this.kbliEnvParams.forEach((item) => {
        let tempStatus = true;
        item.conditions.forEach((element) => {
          if (this.checkIfTrue(element)) {
            tempStatus = tempStatus && true;
          } else {
            tempStatus = tempStatus && false;
          }
        });
        if (tempStatus) {
          this.project.required_doc = item.doc_req;
          this.project.result_risk = item.risk_level;
        }
      });

      // check for any condition that not in kbli param
      if (!this.project.required_doc) {
        this.project.required_doc = 'SPPL';
        this.project.result_risk = 'Rendah';
      }
    },
    checkIfTrue(item) {
      const [data1, operator, data2] = item.split(/\s+/);

      switch (operator) {
        case '<=':
          return parseFloat(data1) <= parseFloat(data2);
        case '>=':
          return parseFloat(data1) >= parseFloat(data2);
        case '<':
          return parseFloat(data1) < parseFloat(data2);
        case '>':
          return parseFloat(data1) > parseFloat(data2);

        default:
          break;
      }
    },
    async getKbliEnvParams() {
      const { data } = await kbliEnvParamResource.list({
        kbli: this.project.kbli,
        businessType: this.project.biz_type,
        unit: this.project.scale_unit,
      });
      this.kbliEnvParams = data.map((item) => {
        const items = item.condition.replace(/[\[\"\]]/g, '').split(',');
        item.conditions = items.map((e) => this.project.scale + ' ' + e);
        return item;
      });

      this.calculatedProjectDoc();
    },
    handleCancel() {
      // this.$router.back();
      if (this.isOSS) {
        this.$router.push({
          name: 'createProjectOss',
          params: { project: this.project },
        });
      } else {
        this.$router.push({
          name: 'createProject',
          params: { project: this.project },
        });
      }
    },
    async createTeam(id){
      // create tim using all list
      const submitData = {
        id_project: id,
        name: 'tim penyusun ' + this.project.project_title,
        members: this.all,
      };
      await formulatorTeamsResource
        .store(submitData)
        .then((response) => {
          this.project.id_formulator_team = response.id;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async handleSubmit() {
      this.$confirm('Anda akan menyimpan hasil penapisan rencana usaha dan/atau kegiatan. Pastikan data yang anda isikan sudah sesuai, karena hasil penapisan yang sudah diproses, tidak dapat diubah.', 'Perhatian', {
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batalkan',
        type: 'warning',
      }).then(() => {
        this.fullLoading = true;
        this.project.id_applicant = this.project.initiatorData.id ? this.project.initiatorData.id : 0;

        if (!this.project.id_project){
          this.project.id_project = 1;
        }

        // make form data because we got file
        const formData = new FormData();
        formData.append('isOSS', this.isOSS);
        formData.append('geomFromGeojson', JSON.stringify(this.geomFromGeojson));
        formData.append('geomProperties', JSON.stringify(this.geomProperties));
        formData.append('geomStyles', JSON.stringify(this.geomStyles));

        // for formulator team mandiri
        if (this.project.type_formulator_team === 'mandiri') {
          formData.append('formulatorTeams', JSON.stringify(this.listFormulatorTeam));
          for (var u = 0; u < this.listFormulatorTeam.length; u++){
            formData.append('listFormulatorTeam[' + u + ']', this.listFormulatorTeam[u]);

            if (this.listFormulatorTeam[u].fileDoc){
              const formulatorFile = this.listFormulatorTeam[u].fileDoc;
              formData.append('formulatorFiles[' + u + ']', formulatorFile);
            }
          }
        }

        // eslint-disable-next-line no-undef
        _.each(this.project, (value, key) => {
          if (key === 'listSubProject' || key === 'address' || key === 'listKewenangan'){
            formData.append(key, JSON.stringify(value));
          } else {
            formData.append(key, value);
          }
        });

        if (this.project.id !== undefined) {
        // update
          projectResource.updateMultipart(this.project.id, formData).then(response => {
            this.$message({
              type: 'success',
              message: 'Project info has been updated successfully',
              duration: 5 * 1000,
            });
            this.fullLoading = false;
            this.$router.push('/project');
          }).catch(error => {
            console.log(error);
            this.fullLoading = false;
          });
        } else {
          projectResource
            .store(formData)
            .then((response) => {
              this.$message({
                message:
                    'New Project ' +
                    this.project.project_title +
                    ' has been created successfully.',
                type: 'success',
                duration: 5 * 1000,
              });
              this.$router.push('/project');
            })
            .catch((error) => {
              this.fullLoading = false;
              console.log(error);
            });
        }
      }).catch(() => {
      });
    },
    getMapPdf() {
      axios.get(`api/map-pdf?file_type=PDF&attachment_type=tapak?id_project=${this.project.id}`)
        .then((response) => {
          response.data.forEach((item) => {
            this.mapPdf = item.stored_filename;
          });
        });
    },
    async updateList() {
      var rmDecimal = this.project.scale;
      var	reverse = rmDecimal.toString().split('').reverse().join(''), projectScale = reverse.match(/\d{1,3}/g);
      projectScale = projectScale.join('.').split('').reverse().join('');
      this.list = [
        {
          param: 'Nama Kegiatan',
          value: this.project.project_title,
        },
        {
          param: 'Bidang Usaha/Kegiatan',
          value: this.project.listSubProject[0].biz_name,
        },
        {
          param: 'Skala/Besaran',
          // value: this.project.scale + ' ' + this.project.scale_unit,
          value: projectScale + ' ' + this.project.scale_unit,
        },
        {
          param: 'Alamat',
          value: this.project.address[0].address,
        },
        {
          param: 'Pemrakarsa',
          value: this.project.initiatorData.name,
        },
        {
          param: 'Penanggung Jawab',
          value: this.project.initiatorData.pic,
        },
        {
          param: 'Alamat Pemrakarsa',
          value: this.project.initiatorData.address,
        },
        {
          param: 'No. Telp Pemrakarsa',
          value: '0' + this.project.initiatorData.phone,
        },
        {
          param: 'Email Pemrakarsa',
          value: this.project.initiatorData.email,
        },
        {
          param: 'Provinsi/Kota',
          value: this.project.address[0].district,
        },
      ];
    },
  },
};
</script>

<style scoped>
#mapView {
  width: 50%;
  height: 100%;
  padding: 0;
  margin: 0 10px;
  position: absolute;
}
</style>
<style>
.bold-row {
  font-weight: bold;
}

.space {
  margin-bottom: 1rem;
}
</style>
