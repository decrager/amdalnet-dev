<template>
  <div class="webgis__container">
    <DarkHeaderHome />
    <el-button id="open__drawer" type="primary" style="margin-left: 16px;" @click="drawer = true">
      Layer Peta Tematik Dokumen Lingkungan
    </el-button>
    <el-drawer
      size="45%"
      title="Cari Layer Kegiatan"
      :visible.sync="drawer"
    >
      <div style="padding: 10px">
        <el-row>
          <el-col :span="6">
            <el-select
              v-model="filterAuthority"
              placeholder="Kewenangan"
              filterable
            >
              <el-option
                v-for="item in authorities"
                :key="item.authority"
                :label="item.authority"
                :value="item.authority"
              />
            </el-select>
          </el-col>
          <el-col :span="6">
            <el-select
              v-model="filterSector"
              placeholder="Sektor"
              filterable
            >
              <el-option
                v-for="item in sectors"
                :key="item.sector"
                :label="item.sector"
                :value="item.sector"
              />
            </el-select>
          </el-col>
          <el-col :span="6">
            <el-select
              v-model="filterYear"
              placeholder="Tahun Kegiatan"
              filterable
            >
              <el-option
                v-for="item in projectYears"
                :key="item.value"
                :label="item.value"
                :value="item.value"
              />
            </el-select>
          </el-col>
          <el-col :span="6">
            <el-select
              v-model="filterInitiator"
              placeholder="Pemrakarsa"
              filterable
            >
              <el-option
                v-for="item in initiators"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-col>
        </el-row>
        <el-row style="padding-top: 5px;">
          <el-col :span="6">
            <el-select
              v-model="filterProvince"
              placeholder="Provinsi"
              filterable
              @change="getDistrictsByIdProv($event)"
            >
              <el-option
                v-for="item in provinces"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-col>
          <el-col :span="6">
            <el-select
              v-model="filterDistrict"
              placeholder="Kabupaten/Kota"
              filterable
            >
              <el-option
                v-for="item in districts"
                :key="item.id"
                :label="item.name"
                :value="item.name"
              />
            </el-select>
          </el-col>
          <el-col :span="6">
            <el-select
              v-model="filterIsPublish"
              placeholder="Status Dokumen"
              filterable
            >
              <el-option
                v-for="item in statusDokumen"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-col>
          <el-col :span="12" />
        </el-row>
        <el-row style="padding-top: 10px">
          <el-col :span="24">
            <el-button type="success" @click="applyFilter">Filter</el-button>
            <el-button type="danger" @click="resetFilter">Reset Filter</el-button>
          </el-col>
        </el-row>
        <div style="padding: 10px;">
          <el-input
            v-model="selectedProject"
            placeholder="Pilih Kegiatan"
          >
            <i slot="prefix" class="el-input__icon el-icon-search" />
          </el-input>
        </div>
        <div style="padding: 10px;">
          <div style="margin-bottom: 10px">

            <span><i>Jumlah Data : <b>{{ total }}</b> Kegiatan</i></span>
          </div>
          <el-table
            stripe
            :data="filterByKegiatan"
            style="width: 100%"
          >
            <el-table-column label="No." width="54px" align="center">
              <template slot-scope="scope">
                <span>{{ (scope.$index) + 1 }}</span>
              </template>
            </el-table-column>
            <el-table-column
              prop="project_title"
              label="Nama Kegiatan"
            />
            <el-table-column
              label="Peta"
              width="60"
              align="center"
            >
              <template slot-scope="scope">
                <el-button
                  size="mini"
                  type="warning"
                  icon="el-icon-map-location"
                  title="Lihat di Peta"
                  circle
                  @click="getIdProject(scope.row)"
                />
              </template>
            </el-table-column>
          </el-table>
          <el-divider />
          <div style="text-align: center; margin-top: 10px;">
            <el-pagination
              background
              layout="prev, pager, next"
              :total="total"
              @current-change="handleCurrentChange"
            />
          </div>
        </div>
      </div>
    </el-drawer>

    <div id="legend__drawer">
      <el-collapse accordion>
        <el-collapse-item title="Legend Peta Tematik" name="1">
          <div id="option__legend" style="padding: 5px">
            <el-checkbox id="layerKelolaCheckBox" v-model="checkedKelola"><img src="/titik_kelola.png" alt=""> Titik Pengelolaan</el-checkbox>
            <el-checkbox id="layerPantauCheckBox" v-model="checkedPantau"><img src="/titik_pantau.png" alt=""> Titik Pemantauan</el-checkbox>
            <el-checkbox id="layerTapakCheckBox" v-model="checkedTapak">
              <div style="display: flex; flex-direction: row; column-gap: 5px;">
                <div style="width: 20px; height: 20px; background-color: rgb(200, 0, 0); border-radius: 3px;" />
                <div>Tapak Proyek</div>
              </div>
            </el-checkbox>
            <el-checkbox id="layerEcologyCheckBox" v-model="checkedEcology">
              <div style="display: flex; flex-direction: row; column-gap: 5px;">
                <div style="width: 20px; height: 20px; border: 2px solid rgb(168, 112, 0); border-radius: 3px;" />
                <div>Batas Ekologis</div>
              </div>
            </el-checkbox>
            <el-checkbox id="layerSosialCheckBox" v-model="checkedSosial">
              <div style="display: flex; flex-direction: row; column-gap: 5px;">
                <div style="width: 20px; height: 20px; border: 2px solid rgb(0, 76, 115); border-radius: 3px;" />
                <div>Batas Sosial</div>
              </div>
            </el-checkbox>
            <el-checkbox id="layerStudiCheckBox" v-model="checkedStudi">
              <div style="display: flex; flex-direction: row; column-gap: 5px;">
                <div style="width: 20px; height: 20px; border: 2px solid rgb(255, 0, 255); border-radius: 3px;" />
                <div>Batas Wilayah Studi</div>
              </div>
            </el-checkbox>
          </div>
        </el-collapse-item>

        <el-collapse-item title="Upload Shapefile" name="2">
          <div style="padding: 5px">
            <form id="uploadForm" enctype="multipart/form-data" method="post">
              <div class="field">
                <label class="file-upload">
                  <p><strong>Tambah Shapefile</strong></p>
                  <input id="inFile" type="file" name="file">
                </label>
              </div>
            </form>

            <span
              id="upload-status"
              class="file-upload-status"
              style="opacity:1;"
            />
            <div id="fileInfo" />
            <div>
              <el-button id="remove__layer" type="warning" @click="removeShapefile">Hapus Shapefile</el-button>
            </div>
          </div>

        </el-collapse-item>

        <el-collapse-item title="Layer List" name="3">
          <div style="margin-top: 15px">
            <h3>PETA RTRW PROPINSI</h3>
          </div>
          <div style="display: flex; flex-direction: column;">
            <div style="margin-top: 10px;">
              <el-select
                v-model="province"
                placeholder="Pilih"
                style="width: 100%"
                filterable
                @change="getLayerRtrw($event)"
              >
                <el-option
                  v-for="item in provinces"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </div>
          </div>
          <div style="margin-top: 15px">
            <h3>PETA TEMATIK STATUS</h3>
          </div>
          <div style="margin-top: 10px">
            <el-checkbox id="layerPIPPIBCheckBox" v-model="checkedPIPPIB">Layer PIPPIB</el-checkbox>
          </div>
          <div style="margin-top: 10px">
            <el-checkbox id="layerKawasanHutanCheckBox" v-model="checkedKawasanHutan">Layer Kawasan Hutan</el-checkbox>
          </div>
          <div style="margin-top: 10px">
            <el-checkbox id="layerTutupanLahanCheckBox" v-model="checkedTutupanLahan">Layer Tutupan Lahan</el-checkbox>
          </div>
          <div style="margin-top: 15px">
            <h3>PETA RUPA BUMI</h3>
          </div>
          <div style="margin-top: 10px">
            <el-checkbox id="layerRBICheckBox" v-model="checkedRBI">Peta Rupa Bumi</el-checkbox>
          </div>
        </el-collapse-item>

      </el-collapse>
    </div>
    <div id="mapViewDiv" />
  </div>

</template>

<script>
// Arcgis Module
import Map from '@arcgis/core/Map';
import MapView from '@arcgis/core/views/MapView';
import DarkHeaderHome from '../home/section/DarkHeader';
import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';
import MapImageLayer from '@arcgis/core/layers/MapImageLayer';
import FeatureLayer from '@arcgis/core/layers/FeatureLayer';
import * as urlUtils from '@arcgis/core/core/urlUtils';
import esriConfig from '@arcgis/core/config.js';
import centroid from '@turf/centroid';
import Legend from '@arcgis/core/widgets/Legend';

// Script
import axios from 'axios';
import rupabumis from './scripts/mapRupabumi';
import { generateFeatureCollection, layerIn } from './scripts/uploadShapefile';
import widgetMap from './scripts/widgetMap';
import popupTemplate from './scripts/popupTemplate';
import urlBlob from './scripts/urlBlob';
import generateArcgisToken from './scripts/arcgisGenerateToken';

export default {
  name: 'WebGis',
  components: {
    DarkHeaderHome,
  },
  data() {
    return {
      tempLayer: null,
      radioProject: null,
      radioRTRW: null,
      checkedEcology: false,
      checkedKelola: false,
      checkedPantau: false,
      checkedTapak: true,
      checkedSosial: false,
      checkedStudi: false,
      checkedRBI: false,
      checkedPIPPIB: false,
      checkedKawasanHutan: false,
      checkedTutupanLahan: false,
      layerRtrw: [],
      mapView: null,
      selectedFeedback: {},
      showIdDialog: false,
      mapGeojsonArray: [],
      mapGeojsonArrayProject: [],
      projects: [],
      projectTitle: '',
      selectId: null,
      title: '',
      groupGeojson: [],
      rendererTapak: {},
      selectedProject: '',
      drawer: false,
      idProjectItem: null,
      mapInit: null,
      isToggled: false,
      mapRtrwGroup: [],
      mappedProject: [],
      provinces: [],
      province: '',
      loadingMap: false,
      filtered: [],
      search: '',
      total: 0,
      pageSize: 10,
      page: 1,
      loadStatus: false,
      currentRtRwLayer: null,
      token: null,
      penutupanLahan2020JSON: null,
      kawasanHutanBJSON: null,
      pippib2022Periode1JSON: null,
      authorities: [],
      sectors: [],
      statusDokumen: [
        {
          label: 'Terbit',
          value: 'true',
        },
        {
          label: 'Belum Terbit',
          value: 'false',
        },
      ],
      projectYears: [
        {
          id: 1,
          value: 2020,
        },
        {
          id: 2,
          value: 2021,
        },
        {
          id: 3,
          value: 2022,
        },
      ],
      initiators: [],
      districts: [],
      filterAuthority: null,
      filterSector: null,
      filterIsPublish: null,
      filterYear: null,
      filterInitiator: null,
      filterProvince: null,
      filterDistrict: null,
    };
  },
  computed: {
    filterByKegiatan() {
      /* eslint-disable vue/no-side-effects-in-computed-properties */
      if (this.search === null) {
        return this.mappedProject;
      }
      this.filtered = this.mappedProject.filter(item => {
        return item.project_title.toLowerCase().includes(this.selectedProject.toLowerCase());
      });

      this.total = this.filtered.length;

      return this.filtered.slice(this.pageSize * this.page - this.pageSize, this.pageSize * this.page);
    },
  },
  mounted: async function() {
    generateArcgisToken(this.token);
    console.log('Map Component Mounted');
    await this.loadMap();
    this.getProjectData();
    await this.getMappedProject();
    await axios.get('/api/provinces').then((result) => {
      this.provinces = result.data.data;
    });
    await axios.get('/api/project-authority-list').then((result) => {
      this.authorities = result.data;
    });
    await axios.get('/api/project-sectors').then((result) => {
      this.sectors = result.data;
    });
    await axios.get('/api/initiators').then((result) => {
      this.initiators = result.data.data;
    });
  },
  methods: {
    handleCurrentChange(val) {
      console.log(val);
      this.page = val;
    },
    getMappedProject() {
      const params = {};
      if (this.filterAuthority !== null) {
        params['authority'] = this.filterAuthority;
      }
      if (this.filterSector !== null) {
        params['sector'] = this.filterSector;
      }
      if (this.filterIsPublish !== null) {
        params['is_publish'] = this.filterIsPublish;
      }
      if (this.filterYear !== null) {
        params['project_year'] = this.filterYear;
      }
      if (this.filterInitiator !== null) {
        params['id_applicant'] = this.filterInitiator;
      }
      if (this.filterProvince !== null) {
        params['prov'] = this.filterProvince;
      }
      if (this.filterDistrict !== null) {
        params['district'] = this.filterDistrict;
      }
      axios.get('api/projects-geom', {
        params: params,
      })
        .then((response) => {
          this.mappedProject = response.data;
        });
    },
    applyFilter() {
      this.getMappedProject();
    },
    resetFilter() {
      this.filterAuthority = null;
      this.filterSector = null;
      this.filterIsPublish = null;
      this.filterYear = null;
      this.filterInitiator = null;
      this.filterProvince = null;
      this.filterDistrict = null;
    },
    getLayerRtrw(e) {
      axios.get(`api/arcgis-services?id_province=${e}`)
        .then((response) => {
          this.layerRtrw = response.data.data;
          console.log('this.layerRtrw.length = ' + this.layerRtrw.length);
          if (this.layerRtrw.length > 0) {
            this.layerRtrw.forEach((item) => {
              if (item.is_proxy === true) {
                urlUtils.addProxyRule({
                  proxyUrl: 'proxy/proxy.php',
                  urlPrefix: 'https://gistaru.atrbpn.go.id/',
                });
              }

              const rtrwLayer = new MapImageLayer({
                url: item.url_service,
                imageTransparency: true,
                visible: true,
              });
              if (this.currentRtRwLayer !== null) {
                this.mapInit.layers.remove(this.currentRtRwLayer);
              }
              this.currentRtRwLayer = rtrwLayer;
              this.mapInit.add(rtrwLayer);
            });
          } else {
            return this.$notify({
              type: 'warning',
              title: 'Perhatian!',
              message: 'Peta RTRW tidak / belum tersedia!',
              duration: 5000,
            });
          }
        });
    },
    async getDistrictsByIdProv(e) {
      await axios.get('/api/districts?idProv=' + e)
        .then((result) => {
          this.districts = result.data.data;
        });
    },
    removeShapefile() {
      this.tempLayer.forEach(item => {
        this.mapInit.removeMany(item);
      });
      document.getElementById('inFile').value = '';
    },
    getIdProject(ids) {
      this.idProjectItem = ids.id;

      if (this.idProjectItem) {
        this.drawer = false;

        axios.get(`api/map-geojson?id=${this.idProjectItem}&step=ka`)
          .then((response) => {
            response.data.forEach((item) => {
              const getType = JSON.parse(item.feature_layer);
              const propType = getType.features[0].properties.type;
              const propFields = getType.features[0].properties.field;
              const propStyles = getType.features[0].properties.styles;

              // Tapak
              if (propType === 'tapak') {
                const geojsonLayerArray = new GeoJSONLayer({
                  url: urlBlob(item.feature_layer),
                  outFields: ['*'],
                  visible: true,
                  title: 'Layer Tapak Proyek',
                  renderer: propStyles,
                  popupTemplate: popupTemplate(propFields),
                  opacity: 0.7,
                });

                this.$parent.mapView.on('layerview-create', async(event) => {
                  await this.$parent.mapView.goTo({
                    target: geojsonLayerArray.fullExtent,
                  }).catch(function(error) {
                    console.error(error);
                  });
                });

                this.mapGeojsonArrayProject.push(geojsonLayerArray);
                if (this.checkedTapak === false) {
                  this.checkedTapak = true;
                }
              }

              // Ecology
              if (propType === 'ecology') {
                const geojsonLayerArray = new GeoJSONLayer({
                  url: urlBlob(item.feature_layer),
                  outFields: ['*'],
                  title: 'Layer Batas Ekologis',
                  visible: true,
                  renderer: propStyles,
                  popupTemplate: popupTemplate(propFields),
                });

                this.mapGeojsonArrayProject.push(geojsonLayerArray);
                if (this.checkedEcology === false) {
                  this.checkedEcology = true;
                }
              }

              // Social
              if (propType === 'social') {
                const geojsonLayerArray = new GeoJSONLayer({
                  url: urlBlob(item.feature_layer),
                  outFields: ['*'],
                  visible: true,
                  title: 'Layer Batas Sosial',
                  renderer: propStyles,
                  popupTemplate: popupTemplate(propFields),
                });

                this.mapGeojsonArrayProject.push(geojsonLayerArray);
                if (this.checkedSosial === false) {
                  this.checkedSosial = true;
                }
              }

              // Study
              if (propType === 'study') {
                const geojsonLayerArray = new GeoJSONLayer({
                  url: urlBlob(item.feature_layer),
                  outFields: ['*'],
                  visible: true,
                  title: 'Layer Batas Studi',
                  renderer: propStyles,
                  popupTemplate: popupTemplate(propFields),
                });

                this.mapGeojsonArrayProject.push(geojsonLayerArray);
                if (this.checkedStudi === false) {
                  this.checkedStudi = true;
                }
              }

              this.mapInit.addMany(this.mapGeojsonArrayProject);
            });
          });
      } else {
        this.mapInit.removeMany(this.mapGeojsonArrayProject);
        this.checkedTapak = false;
        this.checkedEcology = false;
        this.checkedSosial = false;
        this.checkedStudi = false;
      }
    },
    getProjectData() {
      axios.get('api/project-maps')
        .then(response => {
          this.projects = response.data;
        });
    },
    handleSelect(item) {
      this.selectId = item.id;
    },
    toDashboard() {
      this.$router.push({ path: '/dashboard' });
    },
    loginModalShow() {
      this.selectedFeedback = {};
      this.showIdDialog = true;
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
    async loadMap() {
      console.log('this.token = ' + this.token);
      let layerTapak = null;
      let layerTapakPoint = null;
      const map = new Map({
        basemap: 'satellite',
      });
      this.mapInit = map;

      urlUtils.addProxyRule({
        proxyUrl: 'proxy/proxy.php',
        urlPrefix: 'https://gistaru.atrbpn.go.id/',
      });

      const tapakData = await axios.get(`api/map-geojson?type=tapak&step=ka&limit=1`);
      for (let i = 0; i < tapakData.data.length; i++) {
        const item = tapakData.data[i];
        const getType = JSON.parse(item.feature_layer);
        const propFields = getType.features[0].properties.field;
        const propStyles = getType.features[0].properties.styles;
        const geomFields = getType.features[0];

        const geojsonLayerArray = new GeoJSONLayer({
          url: urlBlob(item.feature_layer),
          outFields: ['*'],
          opacity: 0.7,
          visible: true,
          title: 'Layer Tapak Proyek',
          renderer: propStyles,
          popupTemplate: popupTemplate(propFields),
          minScale: 500000,
          legendEnabled: false,
        });

        var centroids = centroid(geomFields);
        var getCoordinates = centroids.geometry.coordinates;

        const markerSymbol = {
          type: 'simple',
          symbol: {
            type: 'picture-marker',
            url: 'map-marker/marker_webgis.png',
            width: '24px',
            height: '24px',
          },
        };

        var features = [
          {
            geometry: {
              type: 'point',
              x: getCoordinates[0],
              y: getCoordinates[1],
            },
            attributes: {
              ObjectID: geomFields.id,
            },
          },
        ];

        const tapakPoint = new FeatureLayer({
          source: features,
          renderer: markerSymbol,
          maxScale: 500000,
          featureReduction: {
            type: 'cluster',
          },
          visible: true,
          fields: [
            {
              name: 'ObjectID',
              alias: 'ObjectID',
              type: 'oid',
            },
          ],
          objectIdField: 'ObjectID',
          legendEnabled: false,
          popupTemplate: popupTemplate(propFields),
        });

        if (layerTapakPoint !== null) {
          this.mapInit.layers.remove(layerTapakPoint);
        }
        this.mapInit.add(tapakPoint);
        layerTapakPoint = tapakPoint;

        this.mapGeojsonArray.push(geojsonLayerArray);
        const toggle = document.getElementById('layerTapakCheckBox');

        toggle.addEventListener('change', async() => {
          if (this.checkedTapak === true) {
            geojsonLayerArray.visible = true;
          } else {
            this.mapInit.removeMany(this.mapGeojsonArrayProject);
            geojsonLayerArray.visible = false;
          }
        });
        if (layerTapak !== null) {
          this.mapInit.layers.remove(layerTapak);
        }
        this.mapInit.add(geojsonLayerArray);
        layerTapak = geojsonLayerArray;
      }

      if (this.checkedPantau === true) {
        const responsePemantauan = await axios.get(`api/map-geojson?type=pemantauan&step=ka`);
        for (let i = 0; i < responsePemantauan.data.length; i++) {
          const item = responsePemantauan.data[i];
          const getType = JSON.parse(item.feature_layer);
          const propFields = getType.features[0].properties.field;
          const propStyles = getType.features[0].properties.styles;

          // Pemantauan
          const geojsonLayerArray = new GeoJSONLayer({
            url: urlBlob(item.feature_layer),
            outFields: ['*'],
            visible: false,
            title: 'Layer Titik Pemantauan',
            renderer: propStyles,
            popupTemplate: popupTemplate(propFields),
            legendEnabled: false,
          });

          this.mapGeojsonArray.push(geojsonLayerArray);
          const toggle = document.getElementById('layerPantauCheckBox');

          toggle.addEventListener('change', () => {
            if (this.checkedPantau === true) {
              geojsonLayerArray.visible = true;
            } else {
              this.mapInit.removeMany(this.mapGeojsonArrayProject);
              geojsonLayerArray.visible = false;
            }
          });
          this.mapInit.add(geojsonLayerArray);
        }
      }

      if (this.checkedKelola === true) {
        const responsepengelolaan = await axios.get(`api/map-geojson?type=pengelolaan&step=ka`);
        for (let i = 0; i < responsepengelolaan.data.length; i++) {
          const item = responsepengelolaan.data[i];
          const getType = JSON.parse(item.feature_layer);
          const propFields = getType.features[0].properties.field;
          const propStyles = getType.features[0].properties.styles;

          // Pemantauan
          const geojsonLayerArray = new GeoJSONLayer({
            url: urlBlob(item.feature_layer),
            outFields: ['*'],
            visible: false,
            title: 'Layer Titik Pengelolaan',
            renderer: propStyles,
            popupTemplate: popupTemplate(propFields),
            legendEnabled: false,
          });

          const toggle = document.getElementById('layerKelolaCheckBox');

          toggle.addEventListener('change', () => {
            if (this.checkedKelola === true) {
              geojsonLayerArray.visible = true;
            } else {
              this.mapInit.remove(geojsonLayerArray);
              geojsonLayerArray.visible = false;
            }
          });
          this.mapInit.add(geojsonLayerArray);
        }
      }

      if (this.checkedEcology === true) {
        const responseEcology = await axios.get(`api/map-geojson?type=ecology&step=ka`);
        for (let i = 0; i < responseEcology.data.length; i++) {
          const item = responseEcology.data[i];
          const getType = JSON.parse(item.feature_layer);
          const propFields = getType.features[0].properties.field;
          const propStyles = getType.features[0].properties.styles;

          const geojsonLayerArray = new GeoJSONLayer({
            url: urlBlob(item.feature_layer),
            outFields: ['*'],
            title: 'Layer Batas Ekologis',
            visible: false,
            renderer: propStyles,
            popupTemplate: popupTemplate(propFields),
            legendEnabled: false,
          });

          this.mapGeojsonArray.push(geojsonLayerArray);
          const ecologyToggle = document.getElementById('layerEcologyCheckBox');

          ecologyToggle.addEventListener('change', () => {
            if (this.checkedEcology === true) {
              geojsonLayerArray.visible = true;
            } else {
              this.mapInit.removeMany(this.mapGeojsonArrayProject);
              geojsonLayerArray.visible = false;
            }
          });
          this.mapInit.add(geojsonLayerArray);
        }
      }

      if (this.checkedSosial === true) {
        const responseSocial = await axios.get(`api/map-geojson?type=social&step=ka`);
        for (let i = 0; i < responseSocial.data.length; i++) {
          const item = responseSocial.data[i];
          const getType = JSON.parse(item.feature_layer);
          const propFields = getType.features[0].properties.field;
          const propStyles = getType.features[0].properties.styles;

          const geojsonLayerArray = new GeoJSONLayer({
            url: urlBlob(item.feature_layer),
            outFields: ['*'],
            visible: false,
            title: 'Layer Batas Sosial',
            renderer: propStyles,
            popupTemplate: popupTemplate(propFields),
            legendEnabled: false,
          });

          this.mapGeojsonArray.push(geojsonLayerArray);
          const toggle = document.getElementById('layerSosialCheckBox');

          toggle.addEventListener('change', () => {
            if (this.checkedSosial === true) {
              geojsonLayerArray.visible = true;
            } else {
              this.mapInit.removeMany(this.mapGeojsonArrayProject);
              geojsonLayerArray.visible = false;
            }
          });
          this.mapInit.add(geojsonLayerArray);
        }
      }

      if (this.checkedStudi === true) {
        const responseStudy = await axios.get(`api/map-geojson?type=study&step=ka`);
        for (let i = 0; i < responseStudy.data.length; i++) {
          const item = responseStudy.data[i];
          const getType = JSON.parse(item.feature_layer);
          const propFields = getType.features[0].properties.field;
          const propStyles = getType.features[0].properties.styles;

          const geojsonLayerArray = new GeoJSONLayer({
            url: urlBlob(item.feature_layer),
            outFields: ['*'],
            visible: false,
            title: 'Layer Batas Studi',
            renderer: propStyles,
            popupTemplate: popupTemplate(propFields),
            legendEnabled: false,
          });

          this.mapGeojsonArray.push(geojsonLayerArray);
          const toggle = document.getElementById('layerStudiCheckBox');

          toggle.addEventListener('change', () => {
            if (this.checkedStudi === true) {
              geojsonLayerArray.visible = true;
            } else {
              this.mapInit.removeMany(this.mapGeojsonArrayProject);
              geojsonLayerArray.visible = false;
            }
          });

          this.mapInit.add(geojsonLayerArray);
        }
      }

      const rbiToggle = document.getElementById('layerRBICheckBox');

      rbiToggle.addEventListener('change', () => {
        if (this.checkedRBI === true) {
          rupabumis.visible = true;
        } else {
          rupabumis.visible = false;
        }
      });

      this.mapInit.add(rupabumis);

      esriConfig.request.proxyUrl = 'https://sigap.menlhk.go.id/proxy/proxy.php';
      const penutupanLahan2020 = new MapImageLayer({
        url: 'https://sigap.menlhk.go.id/server/rest/services/KLHK/A_Penutupan_Lahan_2020/MapServer',
        sublayers: [
          {
            id: 0,
            visible: true,
          },
        ],
      });

      const penutupanLahanToggle = document.getElementById('layerTutupanLahanCheckBox');

      penutupanLahanToggle.addEventListener('change', () => {
        if (this.checkedTutupanLahan === true) {
          penutupanLahan2020.visible = true;
        } else {
          penutupanLahan2020.visible = false;
        }
      });

      this.mapInit.add(penutupanLahan2020);

      const kawasanHutanB = new MapImageLayer({
        url: 'https://sigap.menlhk.go.id/server/rest/services/KLHK/B_Kawasan_Hutan/MapServer',
        sublayers: [
          {
            id: 0,
            visible: true,
          },
        ],
      });

      const kawasanHutanToggle = document.getElementById('layerKawasanHutanCheckBox');

      kawasanHutanToggle.addEventListener('change', () => {
        if (this.checkedKawasanHutan === true) {
          kawasanHutanB.visible = true;
        } else {
          kawasanHutanB.visible = false;
        }
      });

      this.mapInit.add(kawasanHutanB);

      const pippib2022Periode1 = new MapImageLayer({
        url: 'https://sigap.menlhk.go.id/server/rest/services/KLHK/D_PIPPIB_2022_Periode_2/MapServer',
        sublayers: [
          {
            id: 0,
            visible: true,
          },
        ],
      });

      const pipbipToggle = document.getElementById('layerPIPPIBCheckBox');

      pipbipToggle.addEventListener('change', () => {
        if (this.checkedPIPPIB === true) {
          pippib2022Periode1.visible = true;
        } else {
          pippib2022Periode1.visible = false;
        }
      });

      this.mapInit.add(pippib2022Periode1);

      const mapView = new MapView({
        container: 'mapViewDiv',
        map: this.mapInit,
        center: [115.287, -1.588],
        zoom: 6,
      });
      this.$parent.mapView = mapView;
      // Map widgets

      document
        .getElementById('uploadForm')
        .addEventListener('change', (event) => {
          const fileName = event.target.value.toLowerCase();

          if (fileName.indexOf('.zip') !== -1) {
            generateFeatureCollection(fileName, map, mapView);
          } else {
            document.getElementById('upload-status').innerHTML =
                '<p style="color:red">Add shapefile as .zip file</p>';
          }
        });

      this.tempLayer = layerIn;

      const legend = new Legend({
        view: mapView,
      });

      mapView.ui.add(legend, 'bottom-left');

      mapView.ui.add(document.getElementById('open__drawer'), 'top-right');
      mapView.ui.add(document.getElementById('legend__drawer'), 'top-right');
      mapView.ui.add(document.getElementById('upload__shapefile'), 'top-right');
      mapView.ui.add(document.getElementById('layer__list'), 'top-right');

      widgetMap(mapView);
    },
  },
};
</script>
<style>
@import './styles/main-webgis.css';
</style>
