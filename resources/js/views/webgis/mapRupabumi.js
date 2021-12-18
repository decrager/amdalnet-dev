import VectorTileLayer from '@arcgis/core/layers/VectorTileLayer';

const rbiInaportal = new VectorTileLayer({
  title: 'RBI (InaPortal)',
  url: 'https://geoservices.big.go.id/rbi/rest/services/Hosted/Rupabumi_Indonesia/VectorTileServer',
  visible: true,
});

const rupabumis = [
  rbiInaportal,
];

export default rupabumis;
