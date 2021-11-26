<template>
  <div class="webgis__container">
    <DarkHeaderHome />
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
    };
  },
  mounted: function() {
    console.log('Map Component Mounted');
    this.loadMap();
  },
  methods: {
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

      const mapGeojson = [];
      const mapGeojsonArray = [];
      axios.get('api/projects')
        .then(response => {
          const projects = response.data.data;
          for (let i = 0; i < projects.length; i++) {
            if (projects[i].map) {
              const splitMap = projects[i].map.split(',');
              for (let i = 0; i < splitMap.length; i++) {
                shp(window.location.origin + '/storage/map/' + splitMap[i]).then(data => {
                  if (data.length > 1) {
                    for (let i = 0; i < data.length; i++) {
                      const getProjectDetails = {
                        title: 'Details',
                        id: 'get-details',
                        image: 'information-24-f.svg',
                      };
                      const arrayJsonTemplate = {
                        title: projects[i].project_title + ' (' + projects[i].project_year + ').',
                        content: '<table style="border-collapse: collapse !important">' +
                            '<thead>' +
                              '<tr style="margin: 5px 0;">' +
                                '<td style="width: 35%">KBLI Code</td>' +
                                '<td> : </td>' +
                                '<td>' + projects[i].kbli + '</td>' +
                              '</tr>' +
                              '<tr style="margin: 5px 0; background-color: #CFEEFA">' +
                                '<td style="width: 35%">Pemrakarsa</td>' +
                                '<td> : </td>' +
                                '<td>' + projects[i].applicant + '</td>' +
                              '</tr>' +
                              '<tr style="margin: 5px 0;">' +
                                '<td style="width: 35%">Provinsi</td>' +
                                '<td> : </td>' +
                                '<td>' + projects[i].province + '</td>' +
                              '</tr>' +
                              '<tr style="margin: 5px 0; background-color: #CFEEFA">' +
                                '<td style="width: 35%">Kota</td>' +
                                '<td> : </td>' +
                                '<td>' + projects[i].district + '</td>' +
                              '</tr>' +
                              '<tr style="margin: 5px 0;">' +
                                '<td style="width: 35%">Alamat</td>' +
                                '<td> : </td>' +
                                '<td>' + projects[i].address + '</td>' +
                              '</tr>' +
                              '<tr style="margin: 5px 0; background-color: #CFEEFA">' +
                                '<td style="width: 35%">Deskripsi</td>' +
                                '<td> : </td>' +
                                '<td>' + projects[i].description + '</td>' +
                              '</tr>' +
                              '<tr style="margin: 5px 0;">' +
                                '<td style="width: 35%">Skala</td>' +
                                '<td> : </td>' +
                                '<td>' + projects[i].scale + ' ' + projects[i].scale_unit + '</td>' +
                              '</tr>' +
                            '</thead>' +
                          '</table>',
                        actions: [getProjectDetails],
                      };

                      const blob = new Blob([JSON.stringify(data[i])], {
                        type: 'application/json',
                      });
                      const url = URL.createObjectURL(blob);
                      const geojsonLayerArray = new GeoJSONLayer({
                        url: url,
                        outFields: ['*'],
                        title: projects[1].project_title,
                        popupTemplate: arrayJsonTemplate,
                      });
                      mapGeojsonArray.push(geojsonLayerArray);
                    }
                    const kegiatanGroupLayer = new GroupLayer({
                      title: projects[1].project_title,
                      visible: false,
                      visibilityMode: 'exclusive',
                      layers: mapGeojsonArray,
                      opacity: 0.90,
                    });

                    map.add(kegiatanGroupLayer);
                  } else {
                    const blob = new Blob([JSON.stringify(data)], {
                      type: 'application/json',
                    });
                    const url = URL.createObjectURL(blob);
                    const geojsonLayer = new GeoJSONLayer({
                      url: url,
                      visible: false,
                      outFields: ['*'],
                      title: projects[i].project_title,
                    });
                    mapGeojson.push(geojsonLayer);
                    map.addMany(mapGeojson);
                  }
                });
              }
            }
          }
        });

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
      mapView.ui.add(legend, 'bottom-left');
    },
  },
};
</script>
<style scoped>
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
