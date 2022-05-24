<!-- eslint-disable vue/html-self-closing -->
<template>
  <div id="bagan_wrapper" v-loading="loading" style="padding: 15px 0">
    <div id="tree">
      <SimpleFlowChart :height="height" :scene.sync="data" />
    </div>
    <div style="margin-top: 10px; text-align: right">
      <el-button :loading="loadingPDF" type="warning" @click="download">
        Export PDF
      </el-button>
      <div id="pdf"></div>
    </div>
  </div>
</template>

<script>
/* eslint-disable no-undef */
/* eslint-disable vue/this-in-template */
import axios from 'axios';
import SimpleFlowChart from 'vue-simple-flowchart';
import 'vue-simple-flowchart/dist/vue-flowchart.css';
// import * as html2canvas from 'html2canvas';
// import JsPDF from 'jspdf';
// import { Canvg } from 'canvg';

export default {
  components: {
    SimpleFlowChart,
  },
  data() {
    return {
      chartData: null,
      loading: false,
      loadingPDF: false,
      height: 10,
      data: {
        centerX: 1024,
        centerY: 140,
        scale: 1,
        nodes: [
          {
            id: 1,
            x: -400,
            y: -69,
            label: '',
            type: 'title',
          },
          {
            id: 2,
            x: -710,
            y: 100,
            label: 'Tahap Pra Konstruksi',
            type: 'tahap',
          },
          {
            id: 3,
            x: -510,
            y: 100,
            label: 'Tahap Konstruksi',
            type: 'tahap',
          },
          {
            id: 4,
            x: -310,
            y: 100,
            label: 'Tahap Operasi',
            type: 'tahap',
          },
          {
            id: 5,
            x: -110,
            y: 100,
            label: 'Tahap Pasca Operasi',
            type: 'tahap',
          },
          {
            id: 100,
            x: 110,
            y: 100,
            label: 'hehey',
            type: 'hmm',
          },
          {
            id: 101,
            x: 310,
            y: 100,
            label: 'cuy',
            type: 'hmm',
          },
          {
            id: 102,
            x: 510,
            y: 100,
            label: 'brr',
            type: 'hmm',
          },
          {
            id: 103,
            x: 710,
            y: 100,
            label: 'ads',
            type: 'hmm',
          },
          {
            id: 104,
            x: 910,
            y: 100,
            label: 'bfsds',
            type: 'hmm',
          },
        ],
        links: [
          {
            id: 6,
            from: 1, // node id the link start
            to: 2, // node id the link end
          },
          {
            id: 7,
            from: 1, // node id the link start
            to: 3, // node id the link end
          },
          {
            id: 8,
            from: 1, // node id the link start
            to: 4, // node id the link end
          },
          {
            id: 9,
            from: 1, // node id the link start
            to: 5, // node id the link end
          },
          {
            id: 105,
            from: 1, // node id the link start
            to: 100, // node id the link end
          },
          {
            id: 106,
            from: 1, // node id the link start
            to: 101, // node id the link end
          },
          {
            id: 107,
            from: 1, // node id the link start
            to: 102, // node id the link end
          },
          {
            id: 108,
            from: 1, // node id the link start
            to: 103, // node id the link end
          },
          {
            id: 109,
            from: 1, // node id the link start
            to: 104, // node id the link end
          },
        ],
      },
    };
  },
  created() {
    this.drawChart().then(() => {
      const flowChart = document.querySelector('.flowchart-container');
      this.height = flowChart.scrollHeight;
      document.querySelector(
        '.flowchart-container svg'
      ).style.width = `${flowChart.scrollWidth}px`;
      document.querySelector('.flowchart-container svg').id = 'dampak-svg';
    });
  },
  methods: {
    async drawChart() {
      this.loading = true;
      const dataChart = await axios.get(
        `/api/bagan-alir-dampak-penting/${this.$route.params.id}`
      );

      this.data.nodes[0].label = dataChart.data.title;
      const data = dataChart.data.data;

      const components = [];
      const dampakPrimer = [];
      const dampakSekunder = [];
      const dampakTersier = [];
      let id = 10;

      for (const tahap in data) {
        for (const component in data[tahap]) {
          components.push({
            tahap,
            component,
            id,
          });
          id++;

          data[tahap][component].forEach((dampak) => {
            if (dampak.type === 'Primer') {
              dampakPrimer.push({
                tahap,
                component,
                dampak: dampak.dampak,
              });
            } else if (dampak.type === 'Sekunder') {
              dampakSekunder.push({
                tahap,
                component,
                dampak: dampak.dampak,
                parents: dampak.parents,
              });
            } else if (dampak.type === 'Tersier') {
              dampakTersier.push({
                tahap,
                component,
                dampak: dampak.dampak,
                parents: dampak.parents,
              });
            }
          });
        }
      }

      const xAxisListComponent = this.getXAxistList(components);
      const xAxisListPrimer = this.getXAxistList(dampakPrimer);
      const xAxistListSekunder = this.getXAxistList(dampakSekunder);
      const xAxistListTersier = this.getXAxistList(dampakTersier);

      const componentNodes = components.map((component, idx) => {
        return {
          id: component.id,
          x: xAxisListComponent[idx],
          y: 250,
          label: component.component,
          type: 'component',
        };
      });

      const primerNodes = dampakPrimer.map((dampak, idx) => {
        id++;
        return {
          id,
          x: xAxisListPrimer[idx],
          y: 400,
          label: dampak.dampak,
          type: 'primer',
        };
      });

      const sekunderNodes = dampakSekunder.map((dampak, idx) => {
        id++;
        return {
          id,
          x: xAxistListSekunder[idx],
          y: 550,
          label: dampak.dampak,
          type: 'sekunder',
        };
      });

      const tersierNodes = dampakTersier.map((dampak, idx) => {
        id++;
        return {
          id,
          x: xAxistListTersier[idx],
          y: 700,
          label: dampak.dampak,
          type: 'tersier',
        };
      });

      this.data.nodes = [
        ...this.data.nodes,
        ...componentNodes,
        ...primerNodes,
        ...sekunderNodes,
        ...tersierNodes,
      ];

      const links = [];
      components.forEach((component) => {
        id++;
        const from = this.data.nodes.find(
          (node) => node.label === 'Tahap ' + component.tahap
        );
        const to = this.data.nodes.find(
          (node) => node.label === component.component
        );
        links.push({
          id,
          from: from.id,
          to: to.id,
        });
      });

      dampakPrimer.forEach((dampak) => {
        id++;
        const from = this.data.nodes.find(
          (node) => node.label === dampak.component && node.type === 'component'
        );
        const to = this.data.nodes.find(
          (node) => node.label === dampak.dampak && node.type === 'primer'
        );
        links.push({
          id,
          from: from.id,
          to: to.id,
        });
      });

      dampakSekunder.forEach((dampak) => {
        const to = this.data.nodes.find(
          (node) => node.label === dampak.dampak && node.type === 'sekunder'
        );

        dampak.parents.forEach((parent) => {
          id++;
          const from = this.data.nodes.find(
            (node) => node.label === parent && node.type === 'primer'
          );
          links.push({
            id,
            from: from.id,
            to: to.id,
          });
        });
      });

      dampakTersier.forEach((dampak) => {
        const to = this.data.nodes.find(
          (node) => node.label === dampak.dampak && node.type === 'tersier'
        );
        dampak.parents.forEach((parent) => {
          id++;
          const from = this.data.nodes.find(
            (node) => node.label === parent && node.type === 'sekunder'
          );
          links.push({
            id,
            from: from.id,
            to: to.id,
          });
        });
      });

      this.data.links = [...this.data.links, ...links];

      this.loading = false;
    },
    getXAxistList(data) {
      const xAxisList = [];
      const middleValueLeft = 310;
      const middleValueRight = 510;
      const leftAxisLength = Math.round(data.length / 2);
      const rightAxisLength = data.length - leftAxisLength;
      let i = leftAxisLength;
      while (i > 0) {
        xAxisList.push(-(middleValueLeft + 200 * i));
        i--;
      }

      i = 1;
      while (i <= rightAxisLength) {
        xAxisList.push(-(middleValueRight - 200 * i));
        i++;
      }

      return xAxisList;
    },
    download() {
      this.loadingPDF = true;
      // const w = document.querySelector('.flowchart-container').scrollWidth;
      // const h = document.querySelector('.flowchart-container').scrollHeight;
      // let svg = document.querySelector('.flowchart-container svg');
      // svg = svg.replace(/\r?\n|\r/g, '').trim();

      // const canvas = document.createElement('canvas');
      // const context = canvas.getContext('2d');

      // context.clearRect(0, 0, canvas.width, canvas.height);
      // new Canvg(canvas, svg);

      // const img = canvas.toDataURL('image/png');
      // const pdf = new JsPDF('landscape', 'px', [w, h]);
      // pdf.addImage(img, 'PNG', 0, 0, w, h);
      // this.loadingPDF = false;
      // pdf.save('Bagan Alir Dampak Penting.pdf');
      // document.getElementById('pdf').innerHTML = '';

      // var w = document.querySelector('.flowchart-container').scrollWidth;
      // var h = document.querySelector('.flowchart-container').scrollHeight;
      html2canvas(document.querySelector, {
        imageTimeout: 4000,
        useCORS: true,
        scrollY: -window.scrollY,
      }).then((canvas) => {
        document.getElementById('pdf').appendChild(canvas);
        const img = canvas.toDataURL('image/png');
        window.open(img);
        // const pdf = new JsPDF('landscape', 'px', [w, h]);
        // pdf.addImage(img, 'PNG', 0, 0, w, h);
        // this.loadingPDF = false;
        // pdf.save('Bagan Alir Dampak Penting.pdf');
        // document.getElementById('pdf').innerHTML = '';
      });
    },
  },
};
</script>

<style>
.google-visualization-orgchart-node-medium {
  font-size: 16px !important;
  padding: 10px !important;
}
.node-type,
.node-port.node-input,
.node-port.node-output {
  display: none;
}
.flowchart-node {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 80px !important;
  height: auto !important;
  min-width: 80px !important;
  width: auto !important;
  max-width: 105px !important;
}
#tree .flowchart-container {
  overflow: auto !important;
}
</style>
