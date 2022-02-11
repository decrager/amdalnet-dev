<template>
  <div id="mapView" />
</template>

<script>
import axios from 'axios';
import Map from '@arcgis/core/Map';
import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';
import popupTemplate from '@/views/webgis/scripts/popupTemplate';
import MapView from '@arcgis/core/views/MapView';
import Attribution from '@arcgis/core/widgets/Attribution';
import Legend from '@arcgis/core/widgets/Legend';
import LayerList from '@arcgis/core/widgets/LayerList';
import Expand from '@arcgis/core/widgets/Expand';
import shp from 'shpjs';

export default {
  name: 'WebgisVerifikasi',
  data() {
    return {
      readonly: true,
      geomFromGeojson: {},
      geomProperties: {},
      mapUpload: null,
    };
  },
  mounted() {
    this.loadMap();
  },
  methods: {
    loadMap() {
      if (this.readonly === true) {
        const map = new Map({
          basemap: 'satellite',
        });

        axios
          .get(`api/map-geojson?id=${this.$route.params.id}&type=tapak`)
          .then((response) => {
            response.data.forEach((item) => {
              const getType = JSON.parse(item.feature_layer);
              const propFields = getType.features[0].properties.field;
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
                opacity: 0.7,
                popupTemplate: popupTemplate(propFields),
              });
              mapView.on('layerview-create', (event) => {
                mapView.goTo({
                  target: geojsonLayerArray.fullExtent,
                });
              });
              map.add(geojsonLayerArray);
            });
          });

        const mapView = new MapView({
          container: 'mapView',
          map: map,
          center: [115.287, -1.588],
          zoom: 6,
        });

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

        mapView.ui.add(legendExpand, 'bottom-left');
        mapView.ui.add(layerList, 'top-right');
      } else {
        const map = new Map({
          basemap: 'satellite',
        });

        const fr = new FileReader();
        fr.onload = (event) => {
          const base = event.target.result;
          shp(base).then((data) => {
            this.geomFromGeojson = data.features[0].geometry;
            this.geomProperties = data.features[0].properties;

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
  },
};
</script>

<style scoped>
#mapView {
  width: 85%;
  height: 90%;
  padding: 0;
  margin: 0 10px;
  position: absolute;
}
</style>
