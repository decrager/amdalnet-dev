<template>
  <div class="webgis__container">
    <DarkHeaderHome />
    <el-button id="open__drawer" type="primary" style="margin-left: 16px;" @click="drawer = true">
      Lihat Layer
    </el-button>
    <el-drawer
      title="Cari Layer Kegiatan"
      :visible.sync="drawer"
    >
      <div style="padding: 10px">
        <el-input
          v-model="selectedProject"
          placeholder="Pilih Kegiatan"
        >
          <i slot="prefix" class="el-input__icon el-icon-search" />
        </el-input>
        <calcite-value-list-item
          v-for="item in filterByKegiatan"
          id="list_item_id"
          :key="item.id_project"
          :label="item.project_title"
          :value="item.id_project"
          style="margin-top: 3px;"
          @click="getIdProject($event)"
        >
          <calcite-action slot="actions-start" icon="grid" />
        </calcite-value-list-item>
      </div>
    </el-drawer>
    <div id="legend__drawer">
      <calcite-accordion selection-mode="single">
        <calcite-accordion-item
          icon="collection"
          item-title="Legends"
          item-subtitle="Legend Peta Tematik"
        >
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
            <el-checkbox id="layerKelolaPolyCheckBox" v-model="checkedKelolaPoly">
              <div style="display: flex; flex-direction: row; column-gap: 5px;">
                <div style="width: 20px; height: 20px; border: 2px solid rgb(255, 0, 0); border-radius: 3px;" />
                <div>Lokasi Pengelolaan</div>
              </div>
            </el-checkbox>
            <el-checkbox id="layerPantauPolyCheckBox" v-model="checkedPantauPoly">
              <div style="display: flex; flex-direction: row; column-gap: 5px;">
                <div style="width: 20px; height: 20px; border: 2px solid rgb(152, 230, 0); border-radius: 3px;" />
                <div>Lokasi Pemantauan</div>
              </div>
            </el-checkbox>
          </div>
        </calcite-accordion-item>
      </calcite-accordion>
    </div>
    <div id="layer__list">
      <calcite-accordion selection-mode="single">
        <calcite-accordion-item
          icon="layers"
          item-title="Layers"
          item-subtitle="Layer List"
        >
          <div style="margin-top: 10px">
            <h3>PETA RUPA BUMI</h3>
          </div>
          <div style="margin-top: 10px">
            <el-checkbox id="layerRBICheckBox" v-model="checkedRBI">Peta Rupa Bumi</el-checkbox>
          </div>
        </calcite-accordion-item>
      </calcite-accordion>
    </div>
    <div id="upload__shapefile">
      <calcite-accordion selection-mode="single">
        <calcite-accordion-item
          icon="upload"
          item-title="Upload Shapefile"
          item-subtitle="Format data berbentuk .zip"
        >
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
          </div>
        </calcite-accordion-item>
      </calcite-accordion>
    </div>
    <div id="mapViewDiv" />
  </div>

</template>

<script>
// import MapImageLayer from '@arcgis/core/layers/MapImageLayer';
import Map from '@arcgis/core/Map';
import MapView from '@arcgis/core/views/MapView';
import Home from '@arcgis/core/widgets/Home';
import Compass from '@arcgis/core/widgets/Compass';
import BasemapToggle from '@arcgis/core/widgets/BasemapToggle';
import Attribution from '@arcgis/core/widgets/Attribution';
import Expand from '@arcgis/core/widgets/Expand';
// import Legend from '@arcgis/core/widgets/Legend';
// import LayerList from '@arcgis/core/widgets/LayerList';
import Print from '@arcgis/core/widgets/Print';
import ScaleBar from '@arcgis/core/widgets/ScaleBar';
import DarkHeaderHome from '../home/section/DarkHeader';
import axios from 'axios';
import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';
import GroupLayer from '@arcgis/core/layers/GroupLayer';
import * as urlUtils from '@arcgis/core/core/urlUtils';
import esriRequest from '@arcgis/core/request';
import FeatureLayer from '@arcgis/core/layers/FeatureLayer';
import Field from '@arcgis/core/layers/support/Field';
import Graphic from '@arcgis/core/Graphic';
// import rdtrMap from './mapRdtr';
import rupabumis from './mapRupabumi';
import rtrwProvMap from './mapRtrw';

export default {
  name: 'WebGis',
  components: {
    DarkHeaderHome,
  },
  data() {
    return {
      checkedEcology: false,
      checkedKelola: true,
      checkedPantau: true,
      checkedTapak: false,
      checkedSosial: false,
      checkedStudi: false,
      checkedKelolaPoly: false,
      checkedPantauPoly: false,
      checkedRBI: true,
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
      mapInit: [],
    };
  },
  computed: {
    filterByKegiatan() {
      return this.projects.filter(item => {
        return item.project_title.toLowerCase().includes(this.selectedProject);
      });
    },
  },
  mounted: function() {
    console.log('Map Component Mounted');
    this.loadMap();
    this.getProjectData();
  },
  methods: {
    getIdProject(e) {
      this.idProjectItem = e.target.value;
      console.log(e);
      axios.get(`api/map-geojson/${this.idProjectItem}`)
        .then((response) => {
          response.data.forEach((item) => {
            const getType = JSON.parse(item.feature_layer);
            const propType = getType.features[0].properties.type;

            // Tapak
            if (propType === 'tapak') {
              const blob = new Blob([item.feature_layer], {
                type: 'application/json',
              });
              const rendererTapak = {
                type: 'simple',
                field: '*',
                symbol: {
                  type: 'simple-fill',
                  color: [200, 0, 0, 1],
                  outline: {
                    color: [200, 0, 0, 1],
                    width: 2,
                  },
                },
              };
              const url = URL.createObjectURL(blob);
              const geojsonLayerArray = new GeoJSONLayer({
                url: url,
                outFields: ['*'],
                visible: true,
                title: 'Layer Tapak Proyek',
                renderer: rendererTapak,
              });

              this.mapGeojsonArrayProject.push(geojsonLayerArray);
              this.mapInit.add(this.mapGeojsonArrayProject);
            }

            // Ecology
            if (propType === 'ecology') {
              const blob = new Blob([item.feature_layer], {
                type: 'application/json',
              });
              const rendereEco = {
                type: 'simple',
                field: '*',
                symbol: {
                  type: 'simple-fill',
                  color: [0, 0, 0, 0.0],
                  outline: {
                    color: [168, 112, 0, 1],
                    width: 2,
                  },
                },
              };
              const url = URL.createObjectURL(blob);
              const geojsonLayerArray = new GeoJSONLayer({
                url: url,
                outFields: ['*'],
                title: 'Layer Batas Ekologis',
                visible: true,
                renderer: rendereEco,
              });

              this.mapGeojsonArrayProject.push(geojsonLayerArray);
              this.mapInit.add(this.mapGeojsonArrayProject);
            }

            // Social
            if (propType === 'social') {
              const blob = new Blob([item.feature_layer], {
                type: 'application/json',
              });
              const rendererSocial = {
                type: 'simple',
                field: '*',
                symbol: {
                  type: 'simple-fill',
                  color: [0, 0, 0, 0.0],
                  outline: {
                    color: [0, 76, 115, 1],
                    width: 2,
                  },
                },
              };
              const url = URL.createObjectURL(blob);
              const geojsonLayerArray = new GeoJSONLayer({
                url: url,
                outFields: ['*'],
                visible: true,
                title: 'Layer Batas Sosial',
                renderer: rendererSocial,
              });
              this.mapGeojsonArrayProject.push(geojsonLayerArray);
              this.mapInit.add(this.mapGeojsonArrayProject);
            }

            // Study
            if (propType === 'study') {
              const blob = new Blob([item.feature_layer], {
                type: 'application/json',
              });
              const rendererStudy = {
                type: 'simple',
                field: '*',
                symbol: {
                  type: 'simple-fill',
                  color: [0, 0, 0, 0.0],
                  outline: {
                    color: [255, 0, 255, 1],
                    width: 2,
                  },
                },
              };
              const url = URL.createObjectURL(blob);
              const geojsonLayerArray = new GeoJSONLayer({
                url: url,
                outFields: ['*'],
                visible: true,
                title: 'Layer Batas Studi',
                renderer: rendererStudy,
              });

              this.mapGeojsonArrayProject.push(geojsonLayerArray);
              this.mapInit.add(this.mapGeojsonArrayProject);
            }

            // Pemantauan
            if (propType === 'pemantauan') {
              const blob = new Blob([item.feature_layer], {
                type: 'application/json',
              });
              const rendererPemantauan = {
                type: 'simple',
                field: '*',
                symbol: {
                  type: 'picture-marker', // autocasts as new SimpleMarkerSymbol()
                  url: '/titik_pantau.png',
                  width: '24px',
                  height: '24px',
                },
              };
              const url = URL.createObjectURL(blob);
              const geojsonLayerArray = new GeoJSONLayer({
                url: url,
                outFields: ['*'],
                visible: true,
                title: 'Layer Titik Pemantauan',
                renderer: rendererPemantauan,
              });

              this.mapGeojsonArrayProject.push(geojsonLayerArray);
              this.mapInit.add(this.mapGeojsonArrayProject);
            }

            // Pengelolaan
            if (propType === 'study') {
              const blob = new Blob([item.feature_layer], {
                type: 'application/json',
              });
              const rendererPengelolaan = {
                type: 'simple',
                field: '*',
                symbol: {
                  type: 'picture-marker', // autocasts as new SimpleMarkerSymbol()
                  url: '/titik_kelola.png',
                  width: '24px',
                  height: '24px',
                },
              };
              const url = URL.createObjectURL(blob);
              const geojsonLayerArray = new GeoJSONLayer({
                url: url,
                outFields: ['*'],
                visible: true,
                title: 'Layer Titik Pengelolaan',
                renderer: rendererPengelolaan,
              });

              this.mapGeojsonArrayProject.push(geojsonLayerArray);
              this.mapInit.add(this.mapGeojsonArrayProject);
            }
          });
        });
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
    loadMap() {
      const map = new Map({
        basemap: 'topo',
      });

      this.mapInit = map;

      urlUtils.addProxyRule({
        proxyUrl: 'proxy/proxy.php',
        urlPrefix: 'https://gistaru.atrbpn.go.id/',
      });

      // const mapGeojsonArray = [];
      axios.get(`api/map-geojson-merge`)
        .then((response) => {
          response.data.forEach((item) => {
            const getType = JSON.parse(item.feature_layer);
            const propType = getType.features[0].properties.type;
            // const propFields = getType.features[0].properties.field;

            // Popup
            //             const arrayJsonTemplate = {
            //               title: response.data.project_title + ' (' + response.data.project_year + ').',
            //               content: '<table style="border-collapse: collapse !important">' +
            //                       '<thead>' +
            //                         '<tr style="margin: 5px 0;">' +
            //                           '<td style="width: 35%">FID</td>' +
            //                           '<td> : </td>' +
            //                           '<td>' + data.features[0].properties.id + '</td>' +
            //                         '</tr>' +
            //                         '<tr style="margin: 5px 0; background-color: #CFEEFA">' +
            //                           '<td style="width: 35%">NAMA_PEMRAKARSA</td>' +
            //                           '<td> : </td>' +
            //                           '<td>' + data.features[0].properties.pemrakarsa + '</td>' +
            //                         '</tr>' +
            //                         '<tr style="margin: 5px 0;">' +
            //                           '<td style="width: 35%">LAYER</td>' +
            //                           '<td> : </td>' +
            //                           '<td>' + data.features[0].properties.layer + '</td>' +
            //                         '</tr>' +
            //                         '<tr style="margin: 5px 0; background-color: #CFEEFA">' +
            //                           '<td style="width: 35%">NAMA_KEGIATAN</td>' +
            //                           '<td> : </td>' +
            //                           '<td>' + data.features[0].properties.kegiatan + '</td>' +
            //                         '</tr>' +
            //                         '<tr style="margin: 5px 0;">' +
            //                           '<td style="width: 35%">JENIS_DOKUMEN</td>' +
            //                           '<td> : </td>' +
            //                           '<td>' + data.features[0].properties.dokumen + '</td>' +
            //                         '</tr>' +
            //                         '<tr style="margin: 5px 0; background-color: #CFEEFA">' +
            //                           '<td style="width: 35%">LOKASI</td>' +
            //                           '<td> : </td>' +
            //                           '<td>' + data.features[0].properties.lokasi + '</td>' +
            //                         '</tr>' +
            //                         '<tr style="margin: 5px 0;">' +
            //                           '<td style="width: 35%">LUAS</td>' +
            //                           '<td> : </td>' +
            //                           '<td>' + data.features[0].properties.luas + '</td>' +
            //                         '</tr>' +
            //                         '<tr style="margin: 5px 0; background-color: #CFEEFA">' +
            //                           '<td style="width: 35%">SKALA_DATA</td>' +
            //                           '<td> : </td>' +
            //                           '<td>' + data.features[0].properties.skala + '</td>' +
            //                         '</tr>' +
            //                       '</thead>' +
            //                     '</table>',
            //             };

            // Tapak
            if (propType === 'tapak') {
              const blob = new Blob([item.feature_layer], {
                type: 'application/json',
              });
              const rendererTapak = {
                type: 'simple',
                field: '*',
                symbol: {
                  type: 'simple-fill',
                  color: [200, 0, 0, 1],
                  outline: {
                    color: [200, 0, 0, 1],
                    width: 2,
                  },
                },
              };
              const url = URL.createObjectURL(blob);
              const geojsonLayerArray = new GeoJSONLayer({
                url: url,
                outFields: ['*'],
                visible: false,
                title: 'Layer Tapak Proyek',
                renderer: rendererTapak,
              });

              this.mapGeojsonArray.push(geojsonLayerArray);
              const toggle = document.getElementById('layerTapakCheckBox');

              toggle.addEventListener('change', () => {
                if (this.checkedTapak === true) {
                  geojsonLayerArray.visible = true;
                } else {
                  geojsonLayerArray.visible = false;
                }
              });
            }

            // Ecology
            if (propType === 'ecology') {
              const blob = new Blob([item.feature_layer], {
                type: 'application/json',
              });
              const rendereEco = {
                type: 'simple',
                field: '*',
                symbol: {
                  type: 'simple-fill',
                  color: [0, 0, 0, 0.0],
                  outline: {
                    color: [168, 112, 0, 1],
                    width: 2,
                  },
                },
              };
              const url = URL.createObjectURL(blob);
              const geojsonLayerArray = new GeoJSONLayer({
                url: url,
                outFields: ['*'],
                title: 'Layer Batas Ekologis',
                visible: false,
                renderer: rendereEco,
              });

              this.mapGeojsonArray.push(geojsonLayerArray);
              const ecologyToggle = document.getElementById('layerEcologyCheckBox');

              ecologyToggle.addEventListener('change', () => {
                if (this.checkedEcology === true) {
                  geojsonLayerArray.visible = true;
                } else {
                  geojsonLayerArray.visible = false;
                }
              });
            }

            // Social
            if (propType === 'social') {
              const blob = new Blob([item.feature_layer], {
                type: 'application/json',
              });
              const rendererSocial = {
                type: 'simple',
                field: '*',
                symbol: {
                  type: 'simple-fill',
                  color: [0, 0, 0, 0.0],
                  outline: {
                    color: [0, 76, 115, 1],
                    width: 2,
                  },
                },
              };
              const url = URL.createObjectURL(blob);
              const geojsonLayerArray = new GeoJSONLayer({
                url: url,
                outFields: ['*'],
                visible: false,
                title: 'Layer Batas Sosial',
                renderer: rendererSocial,
              });

              this.mapGeojsonArray.push(geojsonLayerArray);
              const toggle = document.getElementById('layerSosialCheckBox');

              toggle.addEventListener('change', () => {
                if (this.checkedSosial === true) {
                  geojsonLayerArray.visible = true;
                } else {
                  geojsonLayerArray.visible = false;
                }
              });
            }

            // Study
            if (propType === 'study') {
              const blob = new Blob([item.feature_layer], {
                type: 'application/json',
              });
              const rendererStudy = {
                type: 'simple',
                field: '*',
                symbol: {
                  type: 'simple-fill',
                  color: [0, 0, 0, 0.0],
                  outline: {
                    color: [255, 0, 255, 1],
                    width: 2,
                  },
                },
              };
              const url = URL.createObjectURL(blob);
              const geojsonLayerArray = new GeoJSONLayer({
                url: url,
                outFields: ['*'],
                visible: false,
                title: 'Layer Batas Studi',
                renderer: rendererStudy,
              });

              this.mapGeojsonArray.push(geojsonLayerArray);
              const toggle = document.getElementById('layerStudiCheckBox');

              toggle.addEventListener('change', () => {
                if (this.checkedStudi === true) {
                  geojsonLayerArray.visible = true;
                } else {
                  geojsonLayerArray.visible = false;
                }
              });
            }

            // Pemantauan
            if (propType === 'pemantauan') {
              const blob = new Blob([item.feature_layer], {
                type: 'application/json',
              });
              const rendererPemantauan = {
                type: 'simple',
                field: '*',
                symbol: {
                  type: 'picture-marker', // autocasts as new SimpleMarkerSymbol()
                  url: '/titik_pantau.png',
                  width: '24px',
                  height: '24px',
                },
              };
              const url = URL.createObjectURL(blob);
              const geojsonLayerArray = new GeoJSONLayer({
                url: url,
                outFields: ['*'],
                visible: true,
                title: 'Layer Titik Pemantauan',
                renderer: rendererPemantauan,
              });

              this.mapGeojsonArray.push(geojsonLayerArray);
              const toggle = document.getElementById('layerPantauCheckBox');

              toggle.addEventListener('change', () => {
                if (this.checkedPantau === true) {
                  geojsonLayerArray.visible = true;
                } else {
                  geojsonLayerArray.visible = false;
                }
              });
            }

            // Pengelolaan
            if (propType === 'study') {
              const blob = new Blob([item.feature_layer], {
                type: 'application/json',
              });
              const rendererPengelolaan = {
                type: 'simple',
                field: '*',
                symbol: {
                  type: 'picture-marker', // autocasts as new SimpleMarkerSymbol()
                  url: '/titik_kelola.png',
                  width: '24px',
                  height: '24px',
                },
              };
              const url = URL.createObjectURL(blob);
              const geojsonLayerArray = new GeoJSONLayer({
                url: url,
                outFields: ['*'],
                visible: true,
                title: 'Layer Titik Pengelolaan',
                renderer: rendererPengelolaan,
              });

              this.mapGeojsonArray.push(geojsonLayerArray);
              const toggle = document.getElementById('layerKelolaCheckBox');

              toggle.addEventListener('change', () => {
                if (this.checkedKelola === true) {
                  geojsonLayerArray.visible = true;
                } else {
                  geojsonLayerArray.visible = false;
                }
              });
              const kegiatanGroupLayer = new GroupLayer({
                title: 'Peta Tematik Dokumen Lingkungan',
                visible: true,
                layers: this.mapGeojsonArray,
                opacity: 0.90,
              });

              map.add(kegiatanGroupLayer);
            }
          });
        });

      const rbiToggle = document.getElementById('layerRBICheckBox');

      rbiToggle.addEventListener('change', () => {
        if (this.checkedRBI === true) {
          rupabumis.visible = true;
        } else {
          rupabumis.visible = false;
        }
      });

      map.add(rupabumis);

      // const rtrwToggle = document.getElementById('check__rtrw');
      // rtrwToggle.addEventListener('change', (e) => {
      //   console.log(e);
      // if (this.layerRtrw === true) {
      //   for (let i = 0; i < rtrwProvMap.length; i++) {
      //     rtrwProvMap[i].visible = true;
      //   }
      // } else {
      //   for (let i = 0; i < rtrwProvMap.length; i++) {
      //     rtrwProvMap[i].visible = false;
      //   }
      // }
      // });
      map.add(rtrwProvMap);

      const mapView = new MapView({
        container: 'mapViewDiv',
        map: map,
        center: [115.287, -1.588],
        zoom: 6,
      });
      this.$parent.mapView = mapView;
      // Map widgets
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

      // const legend = new Legend({
      //   view: mapView,
      //   container: document.createElement('div'),
      // });
      // const layerList = new LayerList({
      //   view: mapView,
      //   container: document.createElement('div'),
      //   listItemCreatedFunction: this.defineActions,
      // });

      const print = new Print({
        view: mapView,
        printServiceUrl:
              'https://utility.arcgisonline.com/arcgis/rest/services/Utilities/PrintingTools/GPServer/Export%20Web%20Map%20Task',
      });

      const printExpand = new Expand({
        view: mapView,
        content: print,
      });

      // layerList.on('trigger-action', (event) => {
      //   const id = event.action.id;
      //   console.log(event.item);
      //   if (id === 'full-extent') {
      //     mapView.goTo({
      //       target: event.item.layer.fullExtent,
      //     });
      //   }
      // });

      const scaleBar = new ScaleBar({
        view: mapView,
        unit: 'metric', // The scale bar displays both metric and non-metric units.
      });

      // Add the widget to the bottom left corner of the view
      mapView.ui.add(scaleBar, {
        position: 'bottom-left',
      });

      document
        .getElementById('uploadForm')
        .addEventListener('change', (event) => {
          const fileName = event.target.value.toLowerCase();

          if (fileName.indexOf('.zip') !== -1) {
            // is file a zip - if not notify user
            generateFeatureCollection(fileName);
          } else {
            document.getElementById('upload-status').innerHTML =
                '<p style="color:red">Add shapefile as .zip file</p>';
          }
        });

      function generateFeatureCollection(fileName) {
        let name = fileName.split('.');
        // Chrome adds c:\fakepath to the value - we need to remove it
        name = name[0].replace('c:\\fakepath\\', '');

        document.getElementById('upload-status').innerHTML =
            '<b>Loading </b>' + name;

        // define the input params for generate see the rest doc for details
        // https://developers.arcgis.com/rest/users-groups-and-items/generate.htm
        const params = {
          name: name,
          targetSR: mapView.spatialReference,
          maxRecordCount: 1000,
          enforceInputFileSizeLimit: true,
          enforceOutputJsonSizeLimit: true,
        };

        // generalize features to 10 meters for better performance
        params.generalize = true;
        params.maxAllowableOffset = 10;
        params.reducePrecision = true;
        params.numberOfDigitsAfterDecimal = 0;

        const myContent = {
          filetype: 'shapefile',
          publishParameters: JSON.stringify(params),
          f: 'json',
        };

        const portalUrl = 'https://www.arcgis.com';
        esriRequest(portalUrl + '/sharing/rest/content/features/generate', {
          query: myContent,
          body: document.getElementById('uploadForm'),
          responseType: 'json',
        })
          .then((response) => {
            const layerName =
                response.data.featureCollection.layers[0].layerDefinition.name;
            document.getElementById('upload-status').innerHTML =
                '<b>Loaded: </b>' + layerName;
            addShapefileToMap(response.data.featureCollection);
          })
          .catch(errorHandler);
      }

      function errorHandler(error) {
        document.getElementById('upload-status').innerHTML =
            "<p style='color:red;max-width: 500px;'>" + error.message + '</p>';
      }

      function addShapefileToMap(featureCollection) {
        // add the shapefile to the map and zoom to the feature collection extent
        // if you want to persist the feature collection when you reload browser, you could store the
        // collection in local storage by serializing the layer using featureLayer.toJson()
        // see the 'Feature Collection in Local Storage' sample for an example of how to work with local storage
        let sourceGraphics = [];

        const layers = featureCollection.layers.map((layer) => {
          const graphics = layer.featureSet.features.map((feature) => {
            return Graphic.fromJSON(feature);
          });
          sourceGraphics = sourceGraphics.concat(graphics);
          const featureLayer = new FeatureLayer({
            objectIdField: 'FID',
            source: graphics,
            fields: layer.layerDefinition.fields.map((field) => {
              return Field.fromJSON(field);
            }),
          });
          return featureLayer;
          // associate the feature with the popup on click to enable highlight and zoom to
        });
        map.addMany(layers);
        mapView.goTo(sourceGraphics).catch((error) => {
          if (error.name !== 'AbortError') {
            console.error(error);
          }
        });

        document.getElementById('upload-status').innerHTML = '';
      }

      // mapView.ui.add(document.getElementById('input__select__kegiatan'), 'top-right');
      mapView.ui.add(document.getElementById('legend__drawer'), 'top-right');
      mapView.ui.add(document.getElementById('upload__shapefile'), 'top-right');
      mapView.ui.add(document.getElementById('layer__list'), 'top-right');
      mapView.ui.add(document.getElementById('open__drawer'), 'top-right');

      // mapView.ui.add(layerList, 'top-right');
      // mapView.ui.add(legend, 'top-right');
      mapView.ui.add(printExpand, 'top-left');
    },
  },
};
</script>
<style>
@import '../home/assets/css/style.css';

.webgis__container {
  display: flex;
  flex-direction: column;
  height: 100%;
  width: 100%;
  margin: 0;
  padding: 0;
  position: absolute;
}

div#input__select__kegiatan {
    width: 100%;
}

div#legend__drawer {
    width: 100%;
}

div#option__legend {
    display: flex;
    flex-direction: column;
}

div#option__legend .el-checkbox {
    margin-top: 10px;
}

div#option__legend .el-checkbox img {
    width: 20px;
    vertical-align: middle;
}

div#upload__shapefile {
    width: 100%;
}

input#inFile {
    outline: seagreen;
    border: 1px solid seagreen;
    padding: 2px;
    margin: 10px 0;
}

div#layer__list {
  width: 100%;
}

div#list__item {
  width: 100%;
}

#calcite_list_item {
  width: 100%;
}

.inline-input {
  width: 100%;
  z-index: 100;
}

#mapViewDiv {
  width: 100%;
  height: 100%;
}

.esri-feature-content tr td {
  padding: 5px !important;
}

.esri-feature-content table {
  margin-top: 10px !important;
}
</style>
