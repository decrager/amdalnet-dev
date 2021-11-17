<template>
  <div class="app-container">
    <el-card>
      <h3 align="center">Surat Keputusan Kelayakan Lingkungan</h3>
      <el-row :gutter="32">
        <el-col :sm="24" :md="12">
          <h4>Informasi Rencana Usaha/Kegiatan</h4>
          <Information />
        </el-col>
        <el-col :sm="24" :md="12">
          <h4>Dokumen Persetujuan Lingkungan</h4>
          <DokumenPersetujuan />
        </el-col>
      </el-row>
      <el-row :gutter="32">
        <el-col :sm="24" :md="24">
          <h4>Peta Lokasi</h4>
          <div>
            <div id="mapView" />
          </div>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const skklResource = new Resource('skkl');

import Information from '@/views/skkl/components/Information';
import DokumenPersetujuan from '@/views/skkl/components/DokumenPersetujuan';

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
import L from 'leaflet';

export default {
  name: 'SKKL',
  components: {
    Information,
    DokumenPersetujuan,
  },
  data() {
    return {
      idProject: this.$route.params.id,
      readonly: true,
    };
  },
  mounted() {
    this.loadMap();
  },
  methods: {
    async loadMap() {
      const project = await skklResource.list({
        map: 'true',
        idProject: this.idProject,
      });

      if (this.readonly === true) {
        const map = new Map({
          basemap: 'topo',
        });

        const batasProv = new MapImageLayer({
          url: 'https://regionalinvestment.bkpm.go.id/gis/rest/services/Administrasi/batas_wilayah_provinsi/MapServer',
          sublayers: [
            {
              id: 0,
              title: 'Batas Provinsi',
            },
          ],
        });

        const graticuleGrid = new MapImageLayer({
          url: 'https://gis.ngdc.noaa.gov/arcgis/rest/services/graticule/MapServer',
        });

        map.addMany([batasProv, graticuleGrid]);

        const baseGroupLayer = new GroupLayer({
          title: 'Base Layer',
          visible: true,
          layers: [batasProv, graticuleGrid],
          opacity: 0.9,
        });

        map.add(baseGroupLayer);

        const mapGeojsonArray = [];
        const splitMap = project.map.split('|');
        console.log(splitMap);
        shp(window.location.origin + project.map).then((data) => {
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
              title: project.project_title,
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
              title: project.project_title,
            });

            map.add(geojsonLayer);
            mapView.on('layerview-create', (event) => {
              mapView.goTo({
                target: geojsonLayer.fullExtent,
              });
            });
          }
        });

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
        console.log(this.mapUpload);
        const map = L.map('mapView');
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);

        for (let i = 0; i < this.mapUpload.length; i++) {
          const reader = new FileReader(); // instantiate a new file reader
          reader.onload = (event) => {
            const geo = L.geoJSON().addTo(map);
            const base = event.target.result;
            shp(base).then((data) => {
              const feature = geo.addData(data);
              console.log(feature);

              map.fitBounds(feature.getBounds());
            });
          };

          reader.readAsArrayBuffer(this.mapUpload[i]);
        }
      }
    },
  },
};
</script>
