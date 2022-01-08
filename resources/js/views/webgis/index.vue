<template>
  <div class="webgis__container">
    <DarkHeaderHome />
    <el-button id="open__drawer" type="primary" style="margin-left: 16px;" @click="drawer = true">
      Layer Peta Tematik Dokumen Lingkungan
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
        <div style="padding: 10px; width: 100%;">
          <el-radio
            v-for="item in filterByKegiatan"
            :id="item.id_project"
            :key="item.id_project"
            v-model="radioProject"
            :label="item.id_project"
            style="margin-top: 10px; overflow-x: auto;"
            @change="getIdProject($event)"
          >{{ item.project_title }}</el-radio>
        </div>
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
          <div style="margin-top: 15px">
            <h3>PETA RTRW PROPINSI</h3>
          </div>
          <div style="display: flex; flex-direction: column;">
            <el-radio
              v-for="item in layerRtrw"
              :id="item.id"
              :key="item.id"
              v-model="radioRTRW"
              :label="item.id"
              style="margin-top: 10px; overflow-x: auto;"
              @change="getLayerRtrw($event)"
            >{{ item.title }}</el-radio>
          </div>
          <div style="margin-top: 15px">
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
            <div>
              <el-button id="remove__layer" type="warning" @click="removeShapefile">Hapus Shapefile</el-button>
            </div>
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
import DarkHeaderHome from '../home/section/DarkHeader';
import axios from 'axios';
import rupabumis from './scripts/mapRupabumi';
import rtrwProvMap from './scripts/mapRtrw';
import { generateFeatureCollection, layerIn } from './scripts/uploadShapefile';
import widgetMap from './scripts/widgetMap';
import * as urlUtils from '@arcgis/core/core/urlUtils';
import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';
import popupTemplate from './scripts/popupTemplate';
import urlBlob from './scripts/urlBlob';

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
      isToggled: false,
      mapRtrwGroup: [],
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
    getLayerRtrw(e) {
      const checked = document.getElementById(e);

      if (checked) {
        this.layerRtrw.forEach(item => {
          if (item.id === e) {
            const sub = item.sublayers.items;
            for (let i = 0; i < sub.length; i++) {
              const element = sub[i];
              element.visible = true;
              this.mapRtrwGroup.push(element);
            }
            this.mapInit.addMany(this.mapRtrwGroup);
            console.log(this.mapRtrwGroup);
          }
        });
      }
    },
    removeShapefile() {
      this.tempLayer.forEach(item => {
        this.mapInit.removeMany(item);
      });
      document.getElementById('inFile').value = '';
    },
    getIdProject(ids) {
      this.idProjectItem = ids;

      if (this.idProjectItem) {
        this.drawer = false;

        axios.get(`api/map-geojson?id=${this.idProjectItem}`)
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
                });

                this.$parent.mapView.on('layerview-create', async(event) => {
                  await this.$parent.mapView.goTo({
                    target: geojsonLayerArray.fullExtent,
                  });
                });

                this.mapGeojsonArrayProject.push(geojsonLayerArray);
                this.checkedTapak = true;
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
                this.checkedEcology = true;
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
                this.checkedSosial = true;
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
                this.checkedStudi = true;
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
                this.checkedPantau = true;
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
                this.checkedKelola = true;
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
    loadMap() {
      const map = new Map({
        basemap: 'topo',
      });
      this.mapInit = map;

      urlUtils.addProxyRule({
        proxyUrl: 'proxy/proxy.php',
        urlPrefix: 'https://gistaru.atrbpn.go.id/',
      });

      axios.get(`api/map-geojson`)
        .then((response) => {
          response.data.forEach((item) => {
            const getType = JSON.parse(item.feature_layer);
            const propType = getType.features[0].properties.type;
            const propFields = getType.features[0].properties.field;
            const propStyles = getType.features[0].properties.styles;

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

              this.mapGeojsonArray.push(geojsonLayerArray);
              const toggle = document.getElementById('layerPantauCheckBox');

              toggle.addEventListener('change', () => {
                if (this.checkedPantau === true) {
                  geojsonLayerArray.visible = true;
                } else {
                  map.removeMany(this.mapGeojsonArrayProject);
                  geojsonLayerArray.visible = false;
                }
              });
            }

            // Pengelolaan
            if (propType === 'study') {
              const geojsonLayerArray = new GeoJSONLayer({
                url: urlBlob(item.feature_layer),
                outFields: ['*'],
                visible: true,
                title: 'Layer Titik Pengelolaan',
                renderer: propStyles,
                popupTemplate: popupTemplate(propFields),
              });

              this.mapGeojsonArray.push(geojsonLayerArray);
              const toggle = document.getElementById('layerKelolaCheckBox');

              toggle.addEventListener('change', () => {
                if (this.checkedKelola === true) {
                  geojsonLayerArray.visible = true;
                } else {
                  map.removeMany(this.mapGeojsonArrayProject);
                  geojsonLayerArray.visible = false;
                }
              });
            }

            // Ecology
            if (propType === 'ecology') {
              const geojsonLayerArray = new GeoJSONLayer({
                url: urlBlob(item.feature_layer),
                outFields: ['*'],
                title: 'Layer Batas Ekologis',
                visible: false,
                renderer: propStyles,
                popupTemplate: popupTemplate(propFields),
              });

              this.mapGeojsonArray.push(geojsonLayerArray);
              const ecologyToggle = document.getElementById('layerEcologyCheckBox');

              ecologyToggle.addEventListener('change', () => {
                if (this.checkedEcology === true) {
                  geojsonLayerArray.visible = true;
                } else {
                  map.removeMany(this.mapGeojsonArrayProject);
                  geojsonLayerArray.visible = false;
                }
              });
            }

            // Social
            if (propType === 'social') {
              const geojsonLayerArray = new GeoJSONLayer({
                url: urlBlob(item.feature_layer),
                outFields: ['*'],
                visible: false,
                title: 'Layer Batas Sosial',
                renderer: propStyles,
                popupTemplate: popupTemplate(propFields),
              });

              this.mapGeojsonArray.push(geojsonLayerArray);
              const toggle = document.getElementById('layerSosialCheckBox');

              toggle.addEventListener('change', () => {
                if (this.checkedSosial === true) {
                  geojsonLayerArray.visible = true;
                } else {
                  map.removeMany(this.mapGeojsonArrayProject);
                  geojsonLayerArray.visible = false;
                }
              });
            }

            // Study
            if (propType === 'study') {
              const geojsonLayerArray = new GeoJSONLayer({
                url: urlBlob(item.feature_layer),
                outFields: ['*'],
                visible: false,
                title: 'Layer Batas Studi',
                renderer: propStyles,
                popupTemplate: popupTemplate(propFields),
              });

              this.mapGeojsonArray.push(geojsonLayerArray);
              const toggle = document.getElementById('layerStudiCheckBox');

              toggle.addEventListener('change', () => {
                if (this.checkedStudi === true) {
                  geojsonLayerArray.visible = true;
                } else {
                  map.removeMany(this.mapGeojsonArrayProject);
                  geojsonLayerArray.visible = false;
                }
              });
            }

            // Tapak
            if (propType === 'tapak') {
              const geojsonLayerArray = new GeoJSONLayer({
                url: urlBlob(item.feature_layer),
                outFields: ['*'],
                visible: false,
                title: 'Layer Tapak Proyek',
                renderer: propStyles,
                popupTemplate: popupTemplate(propFields),
              });

              this.mapGeojsonArray.push(geojsonLayerArray);
              const toggle = document.getElementById('layerTapakCheckBox');

              toggle.addEventListener('change', () => {
                if (this.checkedTapak === true) {
                  geojsonLayerArray.visible = true;
                } else {
                  map.removeMany(this.mapGeojsonArrayProject);
                  geojsonLayerArray.visible = false;
                }
              });
            }

            map.addMany(this.mapGeojsonArray);
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

      for (let i = 0; i < rtrwProvMap.length; i++) {
        this.layerRtrw.push(rtrwProvMap[i]);
      }

      map.addMany(this.layerRtrw);

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

      const mapView = new MapView({
        container: 'mapViewDiv',
        map: map,
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
