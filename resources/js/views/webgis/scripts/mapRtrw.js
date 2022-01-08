import * as urlUtils from '@arcgis/core/core/urlUtils';
import MapImageLayer from '@arcgis/core/layers/MapImageLayer';

urlUtils.addProxyRule({
  proxyUrl: 'proxy/proxy.php',
  urlPrefix: 'https://gistaru.atrbpn.go.id/',
});

const rtrwPapua = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/009_RTR_PROVINSI_PULAU_PAPUA/_9000_PROVINSI_PAPUA_PR_PERDA/MapServer',
  sublayers: [
    {
      id: 0,
    },
    {
      id: 1,
    },
  ],
  visible: false,
});
const rtrwSumatera = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/003_RTR_PROVINSI_PULAU_SUMATERA/_1000_PROVINSI_SUMATERA_PR_PERDA/MapServer',
  sublayers: [
    {
      id: 0,
    },
    {
      id: 1,
    },
    {
      id: 2,
    },
    {
      id: 3,
    },
    {
      id: 4,
    },
    {
      id: 5,
    },
    {
      id: 6,
    },
    {
      id: 7,
    },
    {
      id: 8,
    },
    {
      id: 9,
    },
    {
      id: 10,
    },
    {
      id: 11,
    },
  ],
  visible: false,
});

const rtrwJawa = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/004_RTR_PROVINSI_PULAU_JAWA/_3000_PROVINSI_JAWA_PR_PERDA/MapServer',
  sublayers: [
    {
      id: 0,
    },
    {
      id: 1,
    },
    {
      id: 2,
    },
    {
      id: 3,
    },
    {
      id: 4,
    },
    {
      id: 5,
    },
    {
      id: 6,
    },
    {
      id: 7,
    },
    {
      id: 8,
    },
    {
      id: 9,
    },
  ],
  visible: false,
});

const rtrwKalimantan = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/005_RTR_PROVINSI_PULAU_KALIMANTAN/_6000_PROVINSI_KALIMANTAN_PR_PERDA/MapServer',
  sublayers: [
    {
      id: 0,
    },
    {
      id: 1,
    },
    {
      id: 2,
    },
    {
      id: 3,
    },
    {
      id: 4,
    },
  ],
  visible: false,
});

const rtrwSulawesi = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/006_RTR_PROVINSI_PULAU_SULAWESI/_7000_PROVINSI_SULAWESI_PR_PERDA/MapServer',
  sublayers: [
    {
      id: 0,
    },
    {
      id: 1,
    },
    {
      id: 2,
    },
    {
      id: 3,
    },
    {
      id: 4,
    },
    {
      id: 5,
    },
  ],
  visible: false,
});

const rtrwBaliNusaTenggara = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/007_RTR_PROVINSI_PULAU_BALI_NUSA_TENGGARA/_5000_PROVINSI_BALI_NUSA_TENGGARA_PR_PERDA/MapServer',
  sublayers: [
    {
      id: 0,
    },
    {
      id: 1,
    },
    {
      id: 2,
    },
  ],
  visible: false,
});

const rtrwMaluku = new MapImageLayer({
  url: 'https://gistaru.atrbpn.go.id/arcgis/rest/services/008_RTR_PROVINSI_PULAU_KEPULAUAN_MALUKU/_8000_PROVINSI_MALUKU_PR_PERDA/MapServer',
  sublayers: [
    {
      id: 0,
    },
    {
      id: 1,
    },
  ],
  visible: false,
});

const rtrwProvMap = [
  rtrwSumatera,
  rtrwJawa,
  rtrwKalimantan,
  rtrwSulawesi,
  rtrwBaliNusaTenggara,
  rtrwMaluku,
  rtrwPapua,
];

export default rtrwProvMap;
