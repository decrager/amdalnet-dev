<template>
  <div>
    <div id="tree">
      <GChart
        :settings="{ packages: ['orgchart'], callback: ()=>{drawChart()} }"
        type="OrgChart"
        :data="chartData"
      />
    </div>
  </div>
</template>

<script>
/* eslint-disable no-undef */
/* eslint-disable vue/this-in-template */
import { GChart } from 'vue-google-charts';

export default {

  components: {
    GChart,
  },
  data() {
    return {
      chartData: null,
    };
  },
  methods: {
    drawChart() {
      // eslint-disable-next-line no-undef
      this.chartData = new google.visualization.DataTable();
      this.chartData.addColumn('string', 'Name');
      this.chartData.addColumn('string', 'Kegiatan');
      this.chartData.addColumn('string', 'ToolTip');

      // For each orgchart box, provide the name, manager, and tooltip to show.
      this.chartData.addRows([
        [{ v: 'Nama Rencana Kegiatan', f: 'Nama Rencana Kegiatan' }, '', ''],
        ['Tahap Pra Konstruksi', 'Nama Rencana Kegiatan', ''],
        ['Tahap Konstruksi', 'Nama Rencana Kegiatan', ''],
        ['Tahap Operasi', 'Nama Rencana Kegiatan', ''],
        ['Tahap Pasca Operasi', 'Nama Rencana Kegiatan', ''],

        [{ v: 'Tahap Pra Konstruksi', f: 'Tahap Pra Konstruksi' }, '', ''],
        ['Pengadaan Lahan', 'Tahap Pra Konstruksi', ''],
        ['Kepemilikan Penguasaan Lahan', 'Pengadaan Lahan', ''],
        ['Livelihood', 'Kepemilikan Penguasaan Lahan', ''],

        [{ v: 'Tahap Konstruksi', f: 'Tahap Konstruksi' }, '', ''],
        ['Penerimaan Tenaga Kerja', 'Tahap Konstruksi', ''],
        ['Mobilisasi / Demobilisasi', 'Tahap Konstruksi', ''],
        ['Penyiapan Tapak', 'Tahap Konstruksi', ''],
        ['Pemboran Sumur', 'Tahap Konstruksi', ''],

        ['Kesempatan Kerja', 'Penerimaan Tenaga Kerja', ''],
        ['Lalu Lintas Darat', 'Mobilisasi / Demobilisasi', ''],
        ['Flora', 'Penyiapan Tapak', ''],
        ['Fauna', 'Penyiapan Tapak', ''],
        ['Erosi Tanah', 'Penyiapan Tapak', ''],
        ['Laju Aliran', 'Penyiapan Tapak', ''],

        ['Sedimentasi', 'Erosi Tanah', ''],

        ['Kualitas Udara', 'Pemboran Sumur', ''],
        ['Kebisingan', 'Pemboran Sumur', ''],

        ['Kesehatan Masyarakat', 'Kualitas Udara', ''],

        [{ v: 'Tahap Operasi', f: 'Tahap Operasi' }, '', ''],
        ['Penerimaan Tenaga Kerja', 'Tahap Operasi', ''],
        ['Produksi Sumur', 'Tahap Operasi', ''],
        ['Radiasi Panas', 'Produksi Sumur', ''],

        [{ v: 'Tahap Pasca Operasi', f: 'Tahap Pasca Operasi' }, '', ''],

      ]);

      // Create the chart.
      const chart = new google.visualization.OrgChart(document.getElementById('tree'));
      chart.draw(this.chartData, { allowHtml: true });
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
