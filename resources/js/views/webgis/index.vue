<template>
  <div>
    <DarkHeaderHome />
    <div id="mapViewDiv">
      <!--    <el-button class="button-login" type="success" @click="loginModalShow()">Login</el-button>-->
      <!--    <el-button class="button-dashboard" type="success" @click="toDashboard()">Dashboard</el-button>-->
    </div>
  </div>

</template>

<script>
import MapImageLayer from '@arcgis/core/layers/MapImageLayer';
import Map from '@arcgis/core/Map';
import MapView from '@arcgis/core/views/MapView';
import Home from '@arcgis/core/widgets/Home';
import Compass from '@arcgis/core/widgets/Compass';
import BasemapToggle from '@arcgis/core/widgets/BasemapToggle';
import ScaleBar from '@arcgis/core/widgets/ScaleBar';
import Attribution from '@arcgis/core/widgets/Attribution';
import Expand from '@arcgis/core/widgets/Expand';
import Legend from '@arcgis/core/widgets/Legend';
import LayerList from '@arcgis/core/widgets/LayerList';
import DarkHeaderHome from '../home/section/DarkHeader';
// import OAuthInfo from '@arcgis/core/identity/OAuthInfo';
// import IdentityManager from '@arcgis/core/identity/OAuthInfo';

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
    loadMap() {
      const map = new Map({
        basemap: 'topo',
        // layers: [featureLayer],
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

      map.add(featureLayer);

      const batasProv = new MapImageLayer({
        url: 'https://regionalinvestment.bkpm.go.id/gis/rest/services/Administrasi/batas_wilayah_provinsi/MapServer',
        sublayers: [{
          id: 0,
          title: 'Batas Provinsi',
        }],
      });
      map.add(batasProv);

      const graticuleGrid = new MapImageLayer({
        url: 'https://gis.ngdc.noaa.gov/arcgis/rest/services/graticule/MapServer',
      });
      map.add(graticuleGrid);

      const ekoregion = new MapImageLayer({
        url: 'https://dbgis.menlhk.go.id/arcgis/rest/services/KLHK/Ekoregion_Darat_dan_Laut/MapServer',
        visible: false,
        sublayers: [
          {
            id: 1,
            visible: false,
            title: 'Ekoregion Laut',
          }, {
            id: 0,
            visible: false,
            title: 'Ekoregion Darat',
          },
        ],
      });
      map.add(ekoregion);

      const ppib = new MapImageLayer({
        url: 'https://geoportal.menlhk.go.id/server/rest/services/K_Rencana_Kehutanan/PIPPIB_2021_Revision_1st/MapServer',
        visible: false,
      });
      map.add(ppib);

      const tutupanLahan = new MapImageLayer({
        url: 'https://dbgis.menlhk.go.id/arcgis/rest/services/KLHK/Penutupan_Lahan_Tahun_2020/MapServer',
        visible: false,
      });
      map.add(tutupanLahan);

      // var rtrw = new MapImageLayer({
      //   url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/000_RTRWN/_RTRWN_PP_2017/MapServer',
      //   visible: false,
      // });
      // map.add(rtrw);

      // var oAuthInfo = new OAuthInfo({
      //   appId: '0123456789ABCDEF',
      // });

      // new IdentityManager({
      //   registerOAuthInfos: [oAuthInfo],
      // });
      //

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
      // mapView.ui.add(basemapToggle, "bottom-right");
      const scaleBar = new ScaleBar({
        view: mapView,
      });
      mapView.ui.add(scaleBar, 'bottom-left');
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
      mapView.ui.add(legendExpand, 'top-right');
      mapView.ui.add(layersExpand, 'top-right');
    },
  },
};
</script>
<style scoped>
@import '../home/assets/css/style.css';

#mapViewDiv {
  width: 100%;
  height: 100%;
  padding: 0;
  margin: 0;
  position: absolute;
}

.button-login {
  position: absolute;
  right: 60px;
  top: 15px;
  font-size: 14px;
  font-weight: 600;
  color: white;
  padding: 8px 15px;
  margin: 0;
  overflow: hidden;
  cursor: pointer;
  text-align: center;
  display: flex;
  flex-flow: row nowrap;
  justify-content: center;
  align-items: center;
  transition: background-color 125ms ease-in-out;
  border-radius: 5px;
  outline: none;
}

.button-dashboard {
  position: absolute;
  right: 150px;
  top: 15px;
  font-size: 14px;
  font-weight: 600;
  color: white;
  padding: 8px 15px;
  margin: 0;
  overflow: hidden;
  cursor: pointer;
  text-align: center;
  display: flex;
  flex-flow: row nowrap;
  justify-content: center;
  align-items: center;
  transition: background-color 125ms ease-in-out;
  border-radius: 5px;
  outline: none;
}

.button-login:hover {
  background-color: rgb(3, 68, 3);
  transform: scale(1.1);
  transition: all .3s ease-in;
  font-weight: 700;
  outline: none;
}
</style>
