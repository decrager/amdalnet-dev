<template>
  <div class="webgis__container">
    <DarkHeaderHome />
    <div class="input__select">
      <el-autocomplete
        v-model="projectTitle"
        class="inline-input"
        :fetch-suggestions="querySearch"
        placeholder="Please Input"
        :trigger-on-focus="false"
        @select="handleSelect"
      />
    </div>
    <div id="mapViewDiv" />
  </div>

</template>

<script>
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
import DarkHeaderHome from '../home/section/DarkHeader';
import axios from 'axios';
import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';
import shp from 'shpjs';
import GroupLayer from '@arcgis/core/layers/GroupLayer';
import * as urlUtils from '@arcgis/core/core/urlUtils';

export default {
  name: 'WebGis',
  components: {
    DarkHeaderHome,
  },
  data() {
    return {
      mapView: null,
      selectedFeedback: {},
      showIdDialog: false,
      mapGeojsonArray: [],
      projects: [],
      projectTitle: '',
      selectId: null,
    };
  },
  mounted: function() {
    console.log('Map Component Mounted');
    this.loadMap();
    this.getProjectData();
  },
  methods: {
    querySearch(queryString, cb) {
      var projects = this.projects;
      var results = queryString ? projects.filter(this.createFilter(queryString)) : projects;
      const mapResult = results.map((item) => {
        return {
          value: item.project_title,
          id: item.id_project,
        };
      });
      cb(mapResult);
    },
    createFilter(queryString) {
      return (link) => {
        const resultLink = link.project_title.toLowerCase().indexOf(queryString.toLowerCase()) === 0;
        return resultLink;
      };
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

      urlUtils.addProxyRule({
        proxyUrl: 'proxy/proxy.php',
        urlPrefix: 'https://gistaru.atrbpn.go.id/',
      });

      console.log(this.selectId);

      if (this.selectId != null) {
        axios.get('api/map/' + this.selectId)
          .then((response) => {
            if (response.data.length > 1) {
              const projects = response.data;
              for (let i = 0; i < projects.length; i++) {
              // Map Ekologi
                if (projects[i].attachment_type === 'ecology') {
                  shp(window.location.origin + '/storage/map/' + projects[i].stored_filename).then(data => {
                    const blob = new Blob([JSON.stringify(data)], {
                      type: 'application/json',
                    });
                    const url = URL.createObjectURL(blob);
                    const rendererTapak = {
                      type: 'simple',
                      field: '*',
                      symbol: {
                        type: 'simple-fill',
                        color: [0, 0, 0, 0.0],
                        outline: {
                          color: 'red',
                          width: 2,
                        },
                      },
                    };
                    const geojsonLayerArray = new GeoJSONLayer({
                      url: url,
                      outFields: ['*'],
                      visible: true,
                      title: 'Layer Batas Ekologi',
                      renderer: rendererTapak,
                    });
                    mapView.on('layerview-create', (event) => {
                      mapView.goTo({
                        target: geojsonLayerArray.fullExtent,
                      });
                    });
                    map.add(geojsonLayerArray);
                  });
                }

                // Map Sosial
                if (projects[i].attachment_type === 'social') {
                  shp(window.location.origin + '/storage/map/' + projects[i].stored_filename).then(data => {
                    const blob = new Blob([JSON.stringify(data)], {
                      type: 'application/json',
                    });
                    const url = URL.createObjectURL(blob);
                    const rendererTapak = {
                      type: 'simple',
                      field: '*',
                      symbol: {
                        type: 'simple-fill',
                        color: [0, 0, 0, 0.0],
                        outline: {
                          color: 'blue',
                          width: 2,
                        },
                      },
                    };
                    const geojsonLayerArray = new GeoJSONLayer({
                      url: url,
                      outFields: ['*'],
                      visible: true,
                      title: 'Layer Batas Sosial',
                      renderer: rendererTapak,
                    });
                    mapView.on('layerview-create', (event) => {
                      mapView.goTo({
                        target: geojsonLayerArray.fullExtent,
                      });
                    });
                    map.add(geojsonLayerArray);
                  });
                }

                // Map Studi
                if (projects[i].attachment_type === 'study') {
                  shp(window.location.origin + '/storage/map/' + projects[i].stored_filename).then(data => {
                    const blob = new Blob([JSON.stringify(data)], {
                      type: 'application/json',
                    });
                    const url = URL.createObjectURL(blob);
                    const rendererTapak = {
                      type: 'simple',
                      field: '*',
                      symbol: {
                        type: 'simple-fill',
                        color: [0, 0, 0, 0.0],
                        outline: {
                          color: 'green',
                          width: 2,
                        },
                      },
                    };
                    const geojsonLayerArray = new GeoJSONLayer({
                      url: url,
                      outFields: ['*'],
                      visible: true,
                      title: 'Layer Batas Wilayah Studi',
                      renderer: rendererTapak,
                    });
                    mapView.on('layerview-create', (event) => {
                      mapView.goTo({
                        target: geojsonLayerArray.fullExtent,
                      });
                    });
                    map.add(geojsonLayerArray);
                  });
                }

                // Map Tapak
                if (projects[i].attachment_type === 'tapak') {
                  shp(window.location.origin + '/storage/map/' + projects[i].stored_filename).then(data => {
                    const blob = new Blob([JSON.stringify(data)], {
                      type: 'application/json',
                    });
                    const url = URL.createObjectURL(blob);
                    const rendererTapak = {
                      type: 'simple',
                      field: '*',
                      symbol: {
                        type: 'simple-fill',
                        color: [0, 0, 0, 0.0],
                        outline: {
                          color: '#964B00',
                          width: 2,
                        },
                      },
                    };
                    const geojsonLayerArray = new GeoJSONLayer({
                      url: url,
                      outFields: ['*'],
                      visible: true,
                      title: 'Layer Tapak',
                      renderer: rendererTapak,
                    });
                    mapView.on('layerview-create', (event) => {
                      mapView.goTo({
                        target: geojsonLayerArray.fullExtent,
                      });
                    });
                    map.add(geojsonLayerArray);
                  });
                }
              }
            }
          });
      }

      const featureLayer = new MapImageLayer({
        url: 'https://dbgis.menlhk.go.id/arcgis/rest/services/KLHK/Kawasan_Hutan/MapServer',
        sublayers: [
          {
            id: 0,
            title: 'Layer Kawasan Hutan',
            popupTemplate: {
              title: 'Kawasan Hutan',
            },
          },
        ],
        imageTransparency: true,
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

      const ekoregion = new MapImageLayer({
        url: 'https://dbgis.menlhk.go.id/arcgis/rest/services/KLHK/Ekoregion_Darat_dan_Laut/MapServer',
        visible: false,
        sublayers: [
          {
            id: 1,
            visible: false,
            visibility: 'exclusive',
            title: 'Ekoregion Laut',
          }, {
            id: 0,
            visible: false,
            visibility: 'exclusive',
            title: 'Ekoregion Darat',
          },
        ],
      });

      const ppib = new MapImageLayer({
        url: 'https://geoportal.menlhk.go.id/server/rest/services/K_Rencana_Kehutanan/PIPPIB_2021_Revision_1st/MapServer',
        visible: false,
      });

      const tutupanLahan = new MapImageLayer({
        url: 'https://dbgis.menlhk.go.id/arcgis/rest/services/KLHK/Penutupan_Lahan_Tahun_2020/MapServer',
        visible: false,
      });
      map.addMany([featureLayer, batasProv, tutupanLahan, ekoregion, ppib, graticuleGrid]);

      const baseGroupLayer = new GroupLayer({
        title: 'Base Layer',
        visible: true,
        layers: [featureLayer, batasProv, tutupanLahan, ekoregion, ppib, graticuleGrid],
        opacity: 0.90,
      });

      map.add(baseGroupLayer);

      const gistaruLayer = new MapImageLayer({
        url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/000_RTRWN/_RTRWN_PP_2017/MapServer',
        imageTransparency: true,
        visible: false,
      });
      map.add(gistaruLayer);

      const perbatasanPapua = new MapImageLayer({
        url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/KSN/KSN_PERBATASAN_PAPUA/MapServer',
        imageTransparency: true,
        visible: false,
      });

      const acehSumut = new MapImageLayer({
        url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/002_RTR_KSN/_RTR_KSN_KPN_ACEH_SUMUT/MapServer',
        imageTransparency: true,
        visible: false,
      });

      const kalSul = new MapImageLayer({
        url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/002_RTR_KSN/_RTR_KSN_KALIMANTAN_SULAWESI_PERPRES/MapServer',
        imageTransparency: true,
        visible: false,
      });

      const bandung = new MapImageLayer({
        url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/002_RTR_KSN/_RTR_KSN_KAWASAN_PERKOTAAN_CEKUNGAN_BANDUNG/MapServer',
        imageTransparency: true,
        visible: false,
      });

      const jabodetabek = new MapImageLayer({
        url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/002_RTR_KSN/_RTR_KSN_JABODETABEKPUNJUR/MapServer',
        imageTransparency: true,
        visible: false,
      });

      const borobudur = new MapImageLayer({
        url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/002_RTR_KSN/_RTR_KSN_BOROBUDUR/MapServer',
        imageTransparency: true,
        visible: false,
      });

      const bbk = new MapImageLayer({
        url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/002_RTR_KSN/_RTR_KSN_BBK/MapServer',
        imageTransparency: true,
        visible: false,
      });

      const kedungSepur = new MapImageLayer({
        url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/002_RTR_KSN/_RTR_KSN_KEDUNGSEPUR/MapServer',
        imageTransparency: true,
        visible: false,
      });

      const toba = new MapImageLayer({
        url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/002_RTR_KSN/_RTR_KSN_TOBA/MapServer',
        imageTransparency: true,
        visible: false,
      });

      map.addMany([perbatasanPapua, bandung, jabodetabek, acehSumut, kalSul, borobudur, bbk, kedungSepur, toba]);

      const ksnGroupLayer = new GroupLayer({
        title: 'Layer Kawasan Strategis Nasional (KSN)',
        visible: false,
        layers: [perbatasanPapua, bandung, jabodetabek, acehSumut, kalSul, borobudur, bbk, kedungSepur, toba],
        opacity: 0.90,
      });

      map.add(ksnGroupLayer);

      const rtrPulau = new MapImageLayer({
        url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/001_RTR_PULAU/_RTR_PULAU_INDONESIA/MapServer',
        imageTransparency: true,
        visible: false,
        visibilityMode: '',
      });

      map.add(rtrPulau);

      // const mapGeojsonArray = [];
      axios.get('api/maps')
        .then(response => {
          const projects = response.data;
          for (let i = 0; i < projects.length; i++) {
            if (projects[i].stored_filename) {
              shp(window.location.origin + '/storage/map/' + projects[i].stored_filename).then(data => {
                const blob = new Blob([JSON.stringify(data)], {
                  type: 'application/json',
                });
                const url = URL.createObjectURL(blob);
                axios.get('api/projects/' + projects[i].id_project).then((response) => {
                  const geojsonLayerArray = new GeoJSONLayer({
                    url: url,
                    outFields: ['*'],
                    visible: false,
                    title: response.data.project_title,
                  });
                  this.mapGeojsonArray.push(geojsonLayerArray);

                  // last loop
                  if (i === projects.length - 1){
                    const kegiatanGroupLayer = new GroupLayer({
                      title: 'LAYER KEGIATAN',
                      visible: false,
                      layers: this.mapGeojsonArray,
                      opacity: 0.90,
                    });

                    map.add(kegiatanGroupLayer);
                  }
                });
              });
            }
          }
        });

      const penutupanLahan2020 = new MapImageLayer({
        url: 'https://sigap.menlhk.go.id/server/rest/services/A_Sumber_Daya_Hutan/Penutupan_Lahan_2020/MapServer',
        imageTransparency: true,
        visible: false,
        visibilityMode: '',
      });

      const kawasanHutanB = new MapImageLayer({
        url: 'https://sigap.menlhk.go.id/server/rest/services/B_Kawasan_Hutan/Kawasan_Hutan/MapServer',
        imageTransparency: true,
        visible: false,
        visibilityMode: '',
      });

      const indikatifPPTPKH = new MapImageLayer({
        url: 'https://sigap.menlhk.go.id/server/rest/services/K_Rencana_Kehutanan/Indikatif_PPTPKH/MapServer',
        imageTransparency: true,
        visible: false,
        visibilityMode: '',
      });

      const piapsRevisi = new MapImageLayer({
        url: 'https://sigap.menlhk.go.id/server/rest/services/K_Rencana_Kehutanan/PIAPS_Revisi_VI/MapServer',
        imageTransparency: true,
        visible: false,
        visibilityMode: '',
      });

      const pippib2021Periode2 = new MapImageLayer({
        url: 'https://sigap.menlhk.go.id/server/rest/services/K_Rencana_Kehutanan/PIPPIB_2021_Periode_2/MapServer',
        imageTransparency: true,
        visible: false,
        visibilityMode: '',
      });

      const arKabKota = new MapImageLayer({
        url: 'https://sigap.menlhk.go.id/server/rest/services/ADMINISTRASI_AR_KABKOTA_50K_2018/MapServer',
        imageTransparency: true,
        visible: false,
        visibilityMode: '',
      });

      const batasKabupaten = new MapImageLayer({
        url: 'https://sigap.menlhk.go.id/server/rest/services/Batas_Kabupaten_Kota_50K/MapServer',
        imageTransparency: true,
        visible: false,
        visibilityMode: '',
      });

      const batasProvinsi = new MapImageLayer({
        url: 'https://sigap.menlhk.go.id/server/rest/services/Batas_Provinsi_Indonesia/MapServerr',
        imageTransparency: true,
        visible: false,
        visibilityMode: '',
      });

      const sigapLayer = new GroupLayer({
        title: 'SIGAP KLHK',
        visible: false,
        layers: [penutupanLahan2020, kawasanHutanB, indikatifPPTPKH, piapsRevisi, pippib2021Periode2, arKabKota, batasKabupaten, batasProvinsi],
        opacity: 0.90,
      });

      map.add(sigapLayer);

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

      mapView.ui.add(layerList, 'top-right');
      mapView.ui.add(legend, 'top-right');
    },
  },
};
</script>
<style>
@import '../home/assets/css/style.css';
.input__select {
  position: absolute;
  top: 70px;
  left: 70px;
  z-index: 100;
}
.webgis__container {
  display: flex;
  flex-direction: column;
  height: 100%;
  width: 100%;
  margin: 0;
  padding: 0;
  position: absolute;
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
