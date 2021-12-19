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
// import MapImageLayer from '@arcgis/core/layers/MapImageLayer';
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
import rdtrMap from './mapRdtr';
import rupabumis from './mapRupabumi';

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
                      title: 'Peta Tematik AMDAL',
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

      // const kawasanHutan = new MapImageLayer({
      //   url: 'https://sigap.menlhk.go.id/server/rest/services/B_Kawasan_Hutan/Kawasan_Hutan/MapServer',
      //   imageTransparency: true,
      //   visible: false,
      //   visibilityMode: '',
      // });

      // const batasKabupaten = new MapImageLayer({
      //   title: 'Batas Kabupaten Indonesia',
      //   url: 'https://sigap.menlhk.go.id/server/rest/services/Batas_Kabupaten_Kota_50K/MapServer',
      //   imageTransparency: true,
      //   visible: false,
      //   visibilityMode: '',
      // });

      // const batasProvinsi = new MapImageLayer({
      //   title: 'Batas Provinsi Indonesia',
      //   url: 'https://sigap.menlhk.go.id/server/rest/services/Batas_Provinsi_Indonesia/MapServer',
      //   imageTransparency: true,
      //   visible: false,
      //   visibilityMode: '',
      // });

      const tematikGroup = new GroupLayer({
        title: 'Peta Tematik Status',
        visible: false,
        layers: rdtrMap,
        opacity: 0.90,
      });

      map.add(tematikGroup);

      const rupabumiGroup = new GroupLayer({
        title: 'Peta Rupa Bumi',
        visible: true,
        layers: rupabumis,
        opacity: 0.90,
      });

      map.add(rupabumiGroup);

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
