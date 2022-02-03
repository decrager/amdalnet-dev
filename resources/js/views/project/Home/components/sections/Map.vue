<template>
  <div>
    <div id="mapView" />
  </div>
</template>
<script>

import Map from '@arcgis/core/Map';
import MapView from '@arcgis/core/views/MapView';
import Attribution from '@arcgis/core/widgets/Attribution';
// import Expand from '@arcgis/core/widgets/Expand';
// import Legend from '@arcgis/core/widgets/Legend';
// import LayerList from '@arcgis/core/widgets/LayerList';
import GeoJSONLayer from '@arcgis/core/layers/GeoJSONLayer';
import shp from 'shpjs';
// import Workflow from '@/components/Workflow';
import axios from 'axios';
// import popupTemplate from '@/view/webgis/scripts/popupTemplate';

export default {
  name: 'ProjectMap',
  components: {
    /* Map,
    MapView,
    Attribution,
    Expand,
     // Legend,
     // LayerList,
     GeoJSONLayer, */

  },
  props: {
    id: {
      type: Number,
      default: 0,
    },
  },
  data() {
    return {
      geomFromGeojson: {},
      geomProperties: {},
      readonly: true,
    };
  },
  mounted() {
    this.loadMap();
  },
  methods: {
    async loadMap() {
      const map = new Map({
        basemap: 'satellite',
      });

      // console.log('map: ', this.id);

      axios.get('api/map/' + this.id)
        .then(response => {
          const projects = response.data;
          for (let i = 0; i < projects.length; i++) {
            if (projects[i].attachment_type === 'tapak') {
              shp(window.location.origin + '/storage/map/' + projects[i].stored_filename).then(data => {
                const blob = new Blob([JSON.stringify(data)], {
                  type: 'application/json',
                });
                const url = URL.createObjectURL(blob);

                const renderer = {
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

                const geojsonLayer = new GeoJSONLayer({
                  url: url,
                  outFields: ['*'],
                  title: 'Peta Tapak',
                  renderer: renderer,
                });
                map.add(geojsonLayer);
                mapView.on('layerview-create', (event) => {
                  mapView.goTo({
                    target: geojsonLayer.fullExtent,
                  });
                });
              });
            }
          }
        });

      const mapView = new MapView({
        container: 'mapView',
        map: map,
        center: [115.287, -1.588],
        zoom: 4,
      });

      const attribution = new Attribution({
        view: mapView,
      });
      mapView.ui.add(attribution, 'manual');
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
<style lang="scss" scoped>
div#mapView {
  width: 100%;
  height: 480px;
}
</style>
