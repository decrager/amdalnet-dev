<!-- eslint-disable vue/html-self-closing -->
<template>
  <div id="bagan_wrapper" v-loading="loading" style="padding: 15px 0">
    <div id="tree" style="overflow: auto">
      <GChart
        id="g-chart"
        :settings="{
          packages: ['orgchart'],
          callback: () => {
            drawChart();
          },
        }"
        type="OrgChart"
        :data="chartData"
      />
    </div>
    <el-col :span="24" style="text-align: right; margin: 2em 0">
      <el-button
        :loading="loadingPDF"
        size="small"
        type="warning"
        @click="download"
      >
        Export PDF
      </el-button>
    </el-col>
    <div id="pdf" />
  </div>
</template>

<script>
/* eslint-disable no-undef */
/* eslint-disable vue/this-in-template */
import axios from 'axios';
import { GChart } from 'vue-google-charts';
import * as html2canvas from 'html2canvas';
import JsPDF from 'jspdf';

export default {
  components: {
    GChart,
  },
  data() {
    return {
      chartData: null,
      loading: false,
      loadingPDF: false,
    };
  },
  methods: {
    async drawChart() {
      this.loading = true;
      const dataChart = await axios.get(
        `/api/bagan-alir-dampak-penting/${this.$route.params.id}`
      );

      const data = dataChart.data.data;

      const rowData = [
        ['Dampak Primer', '', ''],
        ['Dampak Sekunder', '', ''],
        ['Dampak Tersier', '', ''],
        [{ v: '', f: `${dataChart.data.title}` }, '', ''],
        ['Tahap Pra Konstruksi', `${dataChart.data.title}`, ''],
        ['Tahap Konstruksi', `${dataChart.data.title}`, ''],
        ['Tahap Operasi', `${dataChart.data.title}`, ''],
        ['Tahap Pasca Operasi', `${dataChart.data.title}`, ''],
      ];

      const dampakSekunder = [];
      const dampakTersier = [];
      const primerIdx = [];
      const sekunderIdx = [];
      const tersierIdx = [];

      for (const tahap in data) {
        for (const component in data[tahap]) {
          rowData.push([component, `Tahap ${tahap}`, '']);

          data[tahap][component].forEach((x) => {
            if (x.type === 'Primer') {
              rowData.push([x.dampak, component, '']);
              primerIdx.push(rowData.length - 1);
            } else if (x.type === 'Sekunder') {
              dampakSekunder.push(x);
            } else if (x.type === 'Tersier') {
              dampakTersier.push(x);
            }
          });
        }
      }

      dampakSekunder.forEach((x) => {
        x.parents.forEach((y) => {
          rowData.push([x.dampak, y, '']);
          sekunderIdx.push(rowData.length - 1);
        });
      });

      dampakTersier.forEach((x) => {
        x.parents.forEach((y) => {
          rowData.push([x.dampak, y, '']);
          tersierIdx.push(rowData.length - 1);
        });
      });

      // eslint-disable-next-line no-undef
      this.chartData = new google.visualization.DataTable();
      this.chartData.addColumn('string', 'Name');
      this.chartData.addColumn('string', 'Kegiatan');
      this.chartData.addColumn('string', 'ToolTip');

      this.chartData.addRows(rowData);

      // set background color to label
      this.chartData.setRowProperty(
        0,
        'style',
        'background:green; color:white'
      );
      this.chartData.setRowProperty(
        1,
        'style',
        'background:orange; color:white'
      );
      this.chartData.setRowProperty(2, 'style', 'background:blue; color:white');

      // set background color to column
      primerIdx.forEach((x) => {
        this.chartData.setRowProperty(
          x,
          'style',
          'background:green; color:white'
        );
      });

      sekunderIdx.forEach((x) => {
        this.chartData.setRowProperty(
          x,
          'style',
          'background:orange; color:white'
        );
      });

      tersierIdx.forEach((x) => {
        this.chartData.setRowProperty(
          x,
          'style',
          'background:blue; color:white'
        );
      });

      // Create the chart.
      const chart = new google.visualization.OrgChart(
        document.getElementById('tree')
      );
      chart.draw(this.chartData, {
        allowHtml: true,
      });

      this.loading = false;
    },
    download() {
      this.loadingPDF = true;
      var w = document.getElementById('tree').scrollWidth;
      var h = document.getElementById('tree').scrollHeight;
      html2canvas(document.querySelector('#tree table'), {
        imageTimeout: 4000,
        useCORS: true,
      }).then((canvas) => {
        document.getElementById('pdf').appendChild(canvas);
        const img = canvas.toDataURL('image/png');
        const pdf = new JsPDF('landscape', 'px', [w, h]);
        pdf.addImage(img, 'PNG', 0, 0, w, h);
        this.loadingPDF = false;
        pdf.save('Bagan Alir Dampak Penting.pdf');
        document.getElementById('pdf').innerHTML = '';
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
</style>
