<template>
  <div v-loading="fullLoading" class="form-container" style="padding: 24px">
    <workflow />
    <el-row>
      <el-col
        :span="12"
      ><h2>Informasi rencana Usaha/Kegiatan</h2>
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
    <el-row :gutter="4" style="padding-top: 32px">
      <el-col :span="12">
        <div><h3>Deskripsi Kegiatan</h3></div>
        <div v-html="project.description" />
      </el-col>
      <el-col :span="12">
        <div><h3>Deskripsi Lokasi</h3></div>
        <div v-html="project.location_desc" />
      </el-col>
    </el-row>
    <el-row :gutter="4">
      <el-col :span="12">
        <div>
          <el-table :data="tableData" :span-method="arraySpanMethod" style="margin-top: 20px" :header-cell-style="{ background: '#099C4B', color: 'white' }">
            <el-table-column
              prop="no"
              label="No."
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
              label="City"
            />
            <el-table-column
              prop="address"
              label="address"
            />
          </el-table>
        </div>
      </el-col>
    </el-row>
    <el-row :gutter="4">
      <el-col :span="12">
        <h2>Hasil Penapisan Rencana Kegiatan</h2>
        <el-row style="padding-bottom: 16px"><el-col :span="12">No Registrasi</el-col>
          <el-col :span="12">1611182998277</el-col></el-row>
        <el-row style="padding-bottom: 16px"><el-col :span="12">Jenis Dokumen</el-col>
          <el-col :span="12">{{ project.required_doc }}</el-col></el-row>
        <el-row style="padding-bottom: 16px"><el-col :span="12">Tingkat Resiko</el-col>
          <el-col :span="12">{{ project.result_risk }}</el-col></el-row>
        <el-row style="padding-bottom: 16px"><el-col :span="12">Kewenangan</el-col>
          <el-col :span="12">Pusat</el-col></el-row>
        <!-- <el-row style="padding-bottom: 16px"><el-col :span="12">Pilih Tim Penyusun</el-col>
          <el-col :span="12">
            <el-form
              ref="project"
              :model="project"
              label-position="top"
              label-width="200px"
              style="max-width: 100%"
            >
              <el-form-item prop="type_formulator_team">
                <el-select
                  v-model="project.type_formulator_team"
                  placeholder="Pilih"
                  :disabled="readonly"
                >
                  <el-option
                    v-for="item in teamOptions"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value"
                  />
                </el-select>
              </el-form-item>
            </el-form>
          </el-col>
        </el-row> -->
        <el-row v-if="project.type_formulator_team === 'lpjp'" style="padding-bottom: 16px"><el-col :span="12">Pilih LPJP</el-col>
          <el-col :span="12">
            <el-form
              ref="project"
              :model="project"
              label-position="top"
            >
              <el-form-item prop="id_lpjp">
                <el-select v-model="project.id_lpjp" filterable placeholder="Pilih" size="mini">
                  <el-option
                    v-for="item in getLpjps"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value"
                  />
                </el-select>
              </el-form-item>
            </el-form>
          </el-col>
        </el-row>
      </el-col>
      <el-col :span="12" />
    </el-row>
    <el-row>
      <div v-if="project.type_formulator_team === 'mandiri'">
        <el-row style="padding-bottom: 16px">
          <h2>Tambah Penyusun</h2>
          <formulator-table
            :list="listFormulatorTeam"
            :loading="false"
          />
          <el-button
            type="primary"
            @click="handleAddFormulatorTable"
          >+</el-button>
        </el-row>
        <!-- <el-row style="padding-bottom: 16px">
          <h2>Tambah Tenaga Ahli</h2>
          <expert-table
            :list="listExpertTeam"
            :loading="false"
          />
          <el-button
            type="primary"
            @click="handleAddExpertTable"
          >+</el-button>
        </el-row> -->
      </div>
    </el-row>
    <div slot="footer" class="dialog-footer">
      <el-button :disabled="readonly" @click="handleCancel()"> Kembali </el-button>
      <el-button v-loading="" type="primary" :disabled="readonly" @click="handleSubmit()"> Simpan </el-button>
    </div>
  </div>
</template>

<script>
import Resource from '@/api/resource';
import FormulatorTable from './components/formulatorTable.vue';
// import ExpertTable from './components/expertTable.vue';

const kbliEnvParamResource = new Resource('kbli-env-params');
const districtResource = new Resource('districts');
const provinceResource = new Resource('provinces');
const formulatorTeamResource = new Resource('formulator-teams');
const projectResource = new Resource('projects');
// const supportDocResource = new Resource('support-docs');
const initiatorByEmailResource = new Resource('initiatorsByEmail');
const initiatorResource = new Resource('initiators');
// const formulatorResource = new Resource('formulators');
const formulatorTeamsResource = new Resource('formulator-teams');

import MapImageLayer from '@arcgis/core/layers/MapImageLayer';
import Map from '@arcgis/core/Map';
import MapView from '@arcgis/core/views/MapView';
import Home from '@arcgis/core/widgets/Home';
import Compass from '@arcgis/core/widgets/Compass';
import BasemapToggle from '@arcgis/core/widgets/BasemapToggle';
import Attribution from '@arcgis/core/widgets/Attribution';
import Expand from '@arcgis/core/widgets/Expand';
import Legend from '@arcgis/core/widgets/Legend';
import LayerList from '@arcgis/core/widgets/LayerList';
import GroupLayer from '@arcgis/core/layers/GroupLayer';
import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';
import shp from 'shpjs';
import Workflow from '@/components/Workflow';
import axios from 'axios';

export default {
  name: 'Publish',
  components: { FormulatorTable, Workflow },
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
  },
  data() {
    return {
      kbliEnvParams: null,
      doc_req: 'SPPL',
      risk_level: 'Rendah',
      teamOptions: [{ value: 'mandiri', label: 'Penyusun Perseorangan' }, { value: 'lpjp', label: 'Lembaga Penyedia Jasa Penyusun' }],
      teamToChooseOptions: null,
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
    getFormulatedTeam(){
      return this.$store.getters.teamType;
    },
    getLpjps(){
      return this.$store.getters.lpjps;
    },
    // getProjectField() {
    //   const pfield = this.$store.getters.projectFieldOptions;
    //   console.log(pfield);
    //   return pfield.filter(e => e.value === this.project.field)[0].label;
    // },
  },
  async mounted() {
    console.log(this.project);
    this.fullLoading = true;
    // for step
    this.$store.dispatch('getStep', 1);
    // await this.getProjectFields();
    // await this.getKbliEnvParams();
    await this.getTeamOptions();
    await this.getInitiatorData();
    await this.updateList();
    // data table for subproject
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
  },
  methods: {
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
        return {
          no: index + 1,
          kbli: e.kbli,
          kegiatan: e.name,
          jenisKegiatan: e.biz_name,
          skala: e.scale + ' ' + (e.scale_unit || ''),
          hasil: e.result,
        };
      });
      const suppArr = this.project.listSubProject.filter(e => e.type === 'pendukung').map((e, index) => {
        return {
          no: index + 1,
          kbli: e.kbli,
          kegiatan: e.name,
          jenisKegiatan: e.biz_name,
          skala: e.scale + ' ' + (e.scale_unit || ''),
          hasil: e.result,
        };
      });
      console.log('main', mainArr);
      console.log('sup', suppArr);

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
      console.log('all', this.tableData);
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
    // async getProjectFields() {
    //   await this.$store.dispatch('getProjectFields');
    // },
    loadMap() {
      if (this.readonly === true) {
        const map = new Map({
          basemap: 'topo',
        });

        const batasProv = new MapImageLayer({
          url: 'https://regionalinvestment.bkpm.go.id/gis/rest/services/Administrasi/batas_wilayah_provinsi/MapServer',
          sublayers: [{
            id: 0,
            title: 'Batas Provinsi',
          }],
        });

        const graticuleGrid = new MapImageLayer({
          url: 'https://gis.ngdc.noaa.gov/arcgis/rest/services/graticule/MapServer',
        });

        map.addMany([batasProv, graticuleGrid]);

        const baseGroupLayer = new GroupLayer({
          title: 'Base Layer',
          visible: true,
          layers: [batasProv, graticuleGrid],
          opacity: 0.90,
        });

        map.add(baseGroupLayer);

        const mapGeojsonArray = [];

        const getMapData = axios.get('api/map' + this.project.id);

        for (let i = 0; i < getMapData.length; i++) {
          shp(window.location.origin + '/storage/map/' + getMapData[i]).then(data => {
            if (data.length > 1) {
              for (let i = 0; i < data.length; i++) {
                const blob = new Blob([JSON.stringify(data[i])], {
                  type: 'application/json',
                });
                const url = URL.createObjectURL(blob);
                const geojsonLayerArray = new GeoJSONLayer({
                  url: url,
                  outFields: ['*'],
                  title: 'Layers',
                });
                mapView.on('layerview-create', (event) => {
                  mapView.goTo({
                    target: geojsonLayerArray.fullExtent,
                  });
                });

                mapGeojsonArray.push(geojsonLayerArray);
              }

              const kegiatanGroupLayer = new GroupLayer({
                title: this.project.project_title,
                visible: true,
                visibilityMode: 'exclusive',
                layers: mapGeojsonArray,
                opacity: 0.75,
              });

              map.add(kegiatanGroupLayer);
            } else {
              const blob = new Blob([JSON.stringify(data)], {
                type: 'application/json',
              });
              const url = URL.createObjectURL(blob);
              const geojsonLayer = new GeoJSONLayer({
                url: url,
                visible: true,
                outFields: ['*'],
                opacity: 0.75,
                title: this.project.project_title,
              });

              map.add(geojsonLayer);
              mapView.on('layerview-create', (event) => {
                mapView.goTo({
                  target: geojsonLayer.fullExtent,
                });
              });
            }
          });
        }

        const mapView = new MapView({
          container: 'mapView',
          map: map,
          center: [115.287, -1.588],
          zoom: 6,
        });
        this.$parent.mapView = mapView;

        const home = new Home({
          view: mapView,
        });

        mapView.popup.on('trigger-action', (event) => {
          if (event.action.id === 'get-details') {
            console.log('tbd');
          }
        });

        mapView.ui.add(home, 'top-left');
        const compass = new Compass({
          view: mapView,
        });
        mapView.ui.add(compass, 'top-left');
        const basemapToggle = new BasemapToggle({
          view: mapView,
          container: document.createElement('div'),
          secondBasemap: 'satellite',
        });
        const expandBasemapToggler = new Expand({
          view: mapView,
          name: 'basemap',
          content: basemapToggle.domNode,
          expandIconClass: 'esri-icon-basemap',
        });
        mapView.ui.add(expandBasemapToggler, 'top-left');
        const attribution = new Attribution({
          view: mapView,
        });
        mapView.ui.add(attribution, 'manual');

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

        const layersExpand = new Expand({
          view: mapView,
          content: layerList.domNode,
          expandIconClass: 'esri-icon-layer-list',
          expandTooltip: 'Layers',
        });
        mapView.ui.add(legendExpand, 'bottom-left');
        mapView.ui.add(layersExpand, 'top-right');
      } else {
        const map = new Map({
          basemap: 'topo',
        });

        const fr = new FileReader();
        fr.onload = (event) => {
          const base = event.target.result;
          shp(base).then(function(data) {
            const blob = new Blob([JSON.stringify(data)], {
              type: 'application/json',
            });

            const renderer = {
              type: 'simple',
              field: '*',
              symbol: {
                type: 'simple-fill',
                outline: {
                  color: 'red',
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
            mapView.on('layerview-create', (event) => {
              mapView.goTo({
                target: geojsonLayer.fullExtent,
              });
            });
          });
        };
        fr.readAsArrayBuffer(this.mapUpload);

        const mapView = new MapView({
          container: 'mapView',
          map: map,
          center: [115.287, -1.588],
          zoom: 6,
        });
        this.$parent.mapView = mapView;

        const layerList = new LayerList({
          view: mapView,
          container: document.createElement('div'),
          listItemCreatedFunction: this.defineActions,
        });

        mapView.ui.add(layerList, 'top-right');
      }
    },
    handleAddExpertTable(){
      this.listExpertTeam.push({});
    },
    handleAddFormulatorTable(){
      this.listFormulatorTeam.push({});
    },
    onFormulatorTypeChange(value){
      this.$store.dispatch('getTeamType', value);
      console.log(this.$store.getters.teamType);
    },
    async getInitiatorData(){
      if (this.project.id_applicant){
        this.project.initiatorData = await initiatorResource.get(this.project.id_applicant);
      } else {
        const data = await this.$store.dispatch('user/getInfo');
        this.project.initiatorData = await initiatorByEmailResource.list({ email: data.email });
      }
    },
    async getTeamOptions() {
      const { data } = await formulatorTeamResource.list({});
      this.teamToChooseOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },
    async getKabKotName(id) {
      const data = await districtResource.get(id);
      console.log(data);
      this.kabkot = data.name;
    },
    async getProvince(id) {
      const data = await provinceResource.get(id);
      console.log(data);
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
      this.$router.push({
        name: 'createProject',
        params: { project: this.project },
      });
    },
    // async createTimPenyusun(){
    //   // insert all tim ahli
    //   const listAhli = [];

    //   await this.listExpertTeam.forEach(element => {
    //     // make form data because we got file
    //     const formData = new FormData();

    //     // eslint-disable-next-line no-undef
    //     _.each(element, (value, key) => {
    //       formData.append(key, value);
    //     });

    //     // adding TA for membership_status
    //     formData.append('membership_status', 'TA');

    //     formulatorResource
    //       .store(formData)
    //       .then((response) => {
    //         this.element = {};
    //         listAhli.push(response);
    //       })
    //       .catch((error) => {
    //         console.log(error);
    //       });
    //   });

    //   this.all = [...listAhli, this.listFormulatorTeam];
    // },
    async createTeam(id){
      console.log('list tim ahli yang dibuat', this.all);
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
      this.fullLoading = true;
      this.project.id_applicant = this.project.initiatorData.id ? this.project.initiatorData.id : 0;

      if (!this.project.id_project){
        this.project.id_project = 1;
      }

      // make form data because we got file
      const formData = new FormData();

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

      console.log(this.project);

      // eslint-disable-next-line no-undef
      _.each(this.project, (value, key) => {
        if (key === 'listSubProject' || key === 'address'){
          formData.append(key, JSON.stringify(value));
        } else {
          formData.append(key, value);
        }
      });

      console.log('project yang disubmit', formData);

      // this.$refs.project.validate(valid => {
      // if (this.project.type_formulator_team) {
      if (this.project.id !== undefined) {
        // update
        projectResource.updateMultipart(this.project.id, formData).then(response => {
          // const { data } = response;
          // this.saveSupportDocs(data.id);
          this.$message({
            type: 'success',
            message: 'Project info has been updated successfully',
            duration: 5 * 1000,
          });
          this.fullLoading = false;
          this.$router.push('/project');
        }).catch(error => {
          console.log(error);
        });
      } else {
        projectResource
          .store(formData)
          .then((response) => {
            // save supportdocs
            // const { data } = response;

            // this.saveSupportDocs(data.id);
            // this.createTeam(data.id);
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
      // } else {
      //   console.log('error submit!!');
      //   this.$alert('Mohon Pilih tim Penyusun', 'Title', {
      //     confirmButtonText: 'OK',
      //   });
      // }
      // });
    },
    // async saveSupportDocs(id_project){
    //   this.project.listSupportDoc.forEach(element => {
    //     element.id_project = id_project;

    //     // make form data because we got file
    //     const formData = new FormData();

    //     // eslint-disable-next-line no-undef
    //     _.each(element, (value, key) => {
    //       formData.append(key, value);
    //     });
    //     if (element.id === undefined){
    //       supportDocResource.store(formData);
    //     } else {
    //       supportDocResource.updateMultipart(element.id, formData);
    //     }
    //   });
    // },
    async updateList() {
      console.log(this.project.address[0]);
      // await this.getKabKotName(this.project.address[0].id_district);
      // await this.getProvince(this.project.address[0].id_prov);
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
          value: this.project.scale + ' ' + this.project.scale_unit,
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
          value: this.project.initiatorData.phone,
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
