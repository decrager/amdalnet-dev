<!-- eslint-disable vue/html-self-closing -->
<template>
  <div id="bagan_wrapper" style="padding: 15px 0">
    <BaganTable @refreshBagan="refreshChart" />
    <div v-loading="loading">
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
  </div>
</template>

<script>
/* eslint-disable no-undef */
/* eslint-disable vue/this-in-template */
import axios from 'axios';
import SimpleFlowChart from 'vue-simple-flowchart';
import 'vue-simple-flowchart/dist/vue-flowchart.css';
import * as html2canvas from 'html2canvas';
import JsPDF from 'jspdf';
import BaganTable from '@/views/penyusunan-andal/components/BaganTable.vue';
// import { Canvg } from 'canvg';

export default {
  components: {
    SimpleFlowChart,
    BaganTable,
  },
  data() {
    return {
      chartData: null,
      loading: false,
      loadingPDF: false,
      height: 10,
      data: {
        centerX: 924,
        centerY: 140,
        scale: 1,
        nodes: [],
        links: [],
      },
    };
  },
  created() {
    this.refreshChart();
  },
  methods: {
    refreshChart() {
      this.drawChart().then(() => {
        const flowChart = document.querySelector('.flowchart-container');
        this.height = flowChart.scrollHeight;
        document.querySelector(
          '.flowchart-container svg'
        ).style.width = `${flowChart.scrollWidth}px`;
        document.querySelector('.flowchart-container svg').id = 'dampak-svg';
        document.querySelectorAll('.node-type').forEach((com) => {
          if (com.innerHTML === 'title') {
            com.parentElement.parentElement.style.backgroundColor = '#5a6260';
            com.parentElement.parentElement.style.color = 'white';
          } else if (com.innerHTML === 'tahap_pra_konstruksi') {
            com.parentElement.parentElement.style.backgroundColor = '#f0c28c';
          } else if (com.innerHTML === 'tahap_konstruksi') {
            com.parentElement.parentElement.style.backgroundColor = '#8bcde8';
          } else if (com.innerHTML === 'tahap_operasi') {
            com.parentElement.parentElement.style.backgroundColor = '#b5d4b5';
          } else if (com.innerHTML === 'tahap_pasca_operasi') {
            com.parentElement.parentElement.style.backgroundColor = '#f0de21';
          } else if (com.innerHTML === 'primer') {
            com.parentElement.parentElement.style.backgroundColor = '#e4556e';
          } else if (com.innerHTML === 'sekunder') {
            com.parentElement.parentElement.style.backgroundColor = '#d0d013';
          } else if (com.innerHTML === 'tersier') {
            com.parentElement.parentElement.style.backgroundColor = '#1fb692';
          }
        });
      });
    },
    async drawChart() {
      this.loading = true;
      this.data.nodes = [
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
          type: 'tahap_pra_konstruksi',
        },
        {
          id: 3,
          x: -510,
          y: 100,
          label: 'Tahap Konstruksi',
          type: 'tahap_konstruksi',
        },
        {
          id: 4,
          x: -310,
          y: 100,
          label: 'Tahap Operasi',
          type: 'tahap_operasi',
        },
        {
          id: 5,
          x: -110,
          y: 100,
          label: 'Tahap Pasca Operasi',
          type: 'tahap_pasca_operasi',
        },
      ];
      this.data.links = [
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
      ];
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
                parents: dampak.parents,
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
          type: 'tahap_' + component.tahap.replace(' ', '_').toLowerCase(),
          component: true,
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
        const to = this.data.nodes.find(
          (node) => node.label === dampak.dampak && node.type === 'primer'
        );

        dampak.parents.forEach((parent) => {
          id++;
          const from = this.data.nodes.find(
            (node) => node.label === parent && node.component === true
          );
          links.push({
            id,
            from: from.id,
            to: to.id,
          });
        });
      });

      dampakSekunder.forEach((dampak) => {
        const to = this.data.nodes.find(
          (node) => node.label === dampak.dampak && node.type === 'sekunder'
        );

        dampak.parents.forEach((parent) => {
          const fromArray = this.data.nodes.filter(
            (node) => node.label === parent && node.type === 'primer'
          );
          fromArray.forEach(fA => {
            id++;
            links.push({
              id,
              from: fA.id,
              to: to.id,
            });
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

      var w = document.querySelector('#tree .flowchart-container').scrollWidth;
      var wo = document.querySelector('#tree .flowchart-container').offsetWidth;
      var h = document.querySelector('#tree').scrollHeight;
      html2canvas(document.querySelector('#tree .flowchart-container'), {
        imageTimeout: 4000,
        useCORS: true,
      }).then((canvas) => {
        console.log('second', canvas);
        document.getElementById('pdf').appendChild(canvas);
        const img = canvas.toDataURL('image/png');
        const pdf = new JsPDF('landscape', 'px', [w, h]);
        pdf.addImage(img, 'PNG', 0, 0, w, h);
        this.loadingPDF = false;
        pdf.save('Bagan Alir Dampak Penting.pdf');
        document.getElementById('pdf').innerHTML = '';
      });
      document.querySelector('.html2canvas-container').style.width = `${
        w + wo / 2
      }px`;
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
#tree,
#tree .flowchart-container {
  overflow: auto !important;
}
/* .html2canvas-container {
  width: 3000px !important;
} */
</style>
