<template>
  <div>
    <div id="bagan" class="main__wrapper" style="overflow: auto">
      <ol class="process_diagram">
        <li>
          <ul>
            <li>
              <div><span class="header__text">Rencana Kegiatan</span>
                <hr>
                <div v-for="rencana in data.rencana_kegiatan" :key="rencana.id" class="text item">
                  <p>- {{ rencana.name }}</p>
                </div>
              </div>
            </li>
            <li>
              <div>
                <span class="header__text">Kegiatan Lain</span>
                <hr>
                <div v-for="rencana in data.kegiatan_lain" :key="rencana.id" class="text item">
                  <p>- {{ rencana.name }}</p>
                </div>
              </div>
            </li>
            <li>
              <div>
                <span class="header__text">Rona Lingkungan Hidup</span>
                <hr>
                <div v-for="rencana in data.rona_awal" :key="rencana.id" class="text item">
                  <p>- {{ rencana.rona_name }}</p>
                </div>

              </div>
            </li>
            <li>
              <div>
                <span class="header__text">Saran Tanggapan dan Pendapat Masyarakat</span>
                <hr>
                <p class="sub_text">Kekhawatiran : </p>
                <div v-for="rencana in data.feedback" :key="rencana.id" class="text item">
                  <p v-if="rencana.concern != null">- {{ rencana.concern }}</p>
                </div>
                <p class="sub_text">Harapan : </p>
                <div v-for="rencana in data.feedback" :key="rencana.id" class="text item">
                  <p v-if="rencana.expectation != null">- {{ rencana.expectation }}</p>
                </div>
              </div>
            </li>
          </ul>
        </li>
        <li>
          <ul>
            <li class="bottom">
              <div class="bottom_content">
                <span class="header__text">Identifikasi Dampak Potensial</span>
                <hr>
                <div v-for="item in identifikasiDampaks" :key="item.id">
                  <div v-if="item.type === 'stage'">
                    <p class="sub_text">{{ item.component_name }} :</p>
                  </div>
                  <div v-if="item.type === 'component'">
                    <p class="component__content">{{ item.component_name }}</p>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </li>
        <li>
          <div>
            <span class="header__text">Dampak Potensial</span>
            <hr>
            <div>
              <p class="sub_text">Pra-konstruksi : </p>
              <div v-for="item in data.dampak_penting_potensi" :key="item.id">
                <p v-if="item.id_project_stage === 4">- {{ item.change_type_name }} {{ item.rona_awal_name }} akibat {{ item.component_name }}.</p>
              </div>
            </div>
            <div>
              <p class="sub_text">Konstruksi  : </p>
              <div v-for="item in data.dampak_penting_potensi" :key="item.id">
                <p v-if="item.id_project_stage === 1">- {{ item.change_type_name }} {{ item.rona_awal_name }} akibat {{ item.component_name }}.</p>
              </div>
            </div>
            <div>
              <p class="sub_text">Operasi : </p>
              <div v-for="item in data.dampak_penting_potensi" :key="item.id">
                <p v-if="item.id_project_stage === 2">- {{ item.change_type_name }} {{ item.rona_awal_name }} akibat {{ item.component_name }}.</p>
              </div>
            </div>
            <div>
              <p class="sub_text">Pasca Operasi  : </p>
              <div v-for="item in data.dampak_penting_potensi" :key="item.id">
                <p v-if="item.id_project_stage === 3">- {{ item.change_type_name }} {{ item.rona_awal_name }} akibat {{ item.component_name }}.</p>
              </div>
            </div>
          </div>
        </li>
        <li>
          <ul>
            <li class="bottom">
              <div class="bottom_content">
                <span class="header__text">Evaluasi Dampak Penting</span>
                <hr>
                <p>- Besaran rencana Usaha dan/atau Kegiatan yang menyebabkan dampak tersebut dan rencana pengelolaan lingkungan awal yang menjadi bagian rencana Usaha dan/atau kegiatan untuk menanggulangi dampak</p>
                <p>- Kondisi rona lingkungan yang ada termasuk kemampuan mendukung Usaha dan/atau kegiatan tersebut atau tidak</p>
              </div>
            </li>
          </ul>
        </li>
        <li>
          <div>
            <span class="header__text">Dampak Penting Hipotetik</span>
            <hr>
            <div>
              <p class="sub_text">Pra-konstruksi : </p>
              <div v-for="item in data.dampak_penting_potensi" :key="item.id">
                <p v-if="item.id_project_stage === 4 && item.is_managed === false">- {{ item.change_type_name }} {{ item.rona_awal_name }} akibat {{ item.component_name }}.</p>
              </div>
            </div>
            <div>
              <p class="sub_text">Konstruksi  : </p>
              <div v-for="item in data.dampak_penting_potensi" :key="item.id">
                <p v-if="item.id_project_stage === 1 && item.is_managed === false">- {{ item.change_type_name }} {{ item.rona_awal_name }} akibat {{ item.component_name }}.</p>
              </div>
            </div>
            <div>
              <p class="sub_text">Operasi : </p>
              <div v-for="item in data.dampak_penting_potensi" :key="item.id">
                <p v-if="item.id_project_stage === 2 && item.is_managed === false">- {{ item.change_type_name }} {{ item.rona_awal_name }} akibat {{ item.component_name }}.</p>
              </div>
            </div>
            <div>
              <p class="sub_text">Pasca Operasi  : </p>
              <div v-for="item in data.dampak_penting_potensi" :key="item.id">
                <p v-if="item.id_project_stage === 3 && item.is_managed === false">- {{ item.change_type_name }} {{ item.rona_awal_name }} akibat {{ item.component_name }}.</p>
              </div>
            </div>
          </div>
        </li>
      </ol>
      <el-col :span="24" style="text-align:right; margin:2em 0;"><el-button size="small" type="warning" @click="download">Export PDF</el-button></el-col>
      <div id="pdf" />
    </div>
  </div>
</template>

<script>
// import go from 'gogogojsvue';
import axios from 'axios';
import * as html2canvas from 'html2canvas';
import JsPDF from 'jspdf';

export default {
  data() {
    return {
      flowChart: null,
      projectId: this.$route.params && this.$route.params.id,
      data: [],
      identifikasiDampaks: [],
      rona_mappings: [],
      impacts: [],
    };
  },
  created() {
    this.getData();
    html2canvas(document.querySelector('#bagan'), { imageTimeout: 1000, useCORS: true }).then(canvas => {
      document.getElementById('pdf').appendChild(canvas);
      const img = canvas.toDataURL('image/png');
      const pdf = new JsPDF('landscape', 'mm', 'a3');
      pdf.addImage(img, 'PNG', 5, 5, 410, 240);
      document.getElementById('pdf').innerHTML = '';
    });
  },
  methods: {
    getData() {
      axios.get('api/bagan-alir/' + this.projectId)
        .then((response) => {
          this.data = response.data;
        });
      axios.get('api/matriks-dampak/table/' + this.projectId)
        .then((response) => {
          this.identifikasiDampaks = response.data;
        });
      axios.get('api/matriks-dampak/rona-mapping/' + this.projectId)
        .then((response) => {
          this.ronaMappings = response.data;
        });
      axios.get(`api/impact-identifications?id_project=${this.projectId}&join_tables=true`)
        .then(response => {
          this.impacts = response.data.data;
        });
    },
    download() {
      html2canvas(document.querySelector('#bagan'), { imageTimeout: 1000, useCORS: true }).then(canvas => {
        document.getElementById('pdf').appendChild(canvas);
        const img = canvas.toDataURL('image/png');
        const pdf = new JsPDF('landscape', 'mm', 'a3');
        pdf.addImage(img, 'PNG', 5, 5, 410, 240);
        pdf.save('Bagan Alir Formulir KA.pdf');
        document.getElementById('pdf').innerHTML = '';
      });
    },
    handleCancelComponent(){
      this.showRencanaKegiatan = false;
    },
  },
};
</script>

<style>
/* All parameters */
:root {
  --linethick: 3px;
  --linewidth: 1.8em;
}

.header__text {
  font-size: 18px;
  font-weight: 700;
  margin: 0;
  color: #464646;
}

.sub_text {
  font-size: 15px;
  font-weight: 600;
  color: #464646;
}

.component__content {
  margin-left: 15px;
  font-style: italic;
}

hr {
  border: .5px solid #099C4B
}

/* node style */
.process_diagram li>div {
  background-color: #55BF73;
  color: white;
  border-style: solid;
  border-color: #55BF73;
  border-radius: 5px;
  text-align: left;
  width: 350px
}

/* connecting lines between nodes */
.process_diagram li:before,
.process_diagram li:after,
.process_diagram ul:before,
.process_diagram ul:after,
.process_diagram div:before,
.process_diagram div:after {
  border-style: solid;
  border-color: #55BF73;
}

.process_diagram,
.process_diagram ol,
.process_diagram ul,
.process_diagram li {
  margin: 0 auto;
  padding: 0;
  display: block;
  list-style: none;
  text-align: center;
  vertical-align: middle;
}

.bottom__process {
  margin-top: 10px;
  padding: 0;
  display: block;
  list-style: none;
  text-align: center;
  vertical-align: bottom;
}

.process_diagram li {
  position: relative;
}

.process_diagram,
.process_diagram ol {
  display: table;
  width: 130%;
  border-collapse: collapse;
}

.process_diagram>li,
.process_diagram ol>li {
  display: table-cell;
}

.process_diagram>li,
.process_diagram ol>li,
.process_diagram ul>li {
  padding: .5em 0
}

/* a dash before and behind all uls */
.process_diagram ul {
  position: relative;
  padding: 0 var(--linewidth);
}

.process_diagram ul:before,
.process_diagram ul:after {
  position: absolute;
  content: "";
  top: 50%;
  width: 100%;
  display: block;
  border-width: var(--linethick) 0 0;
}

.process_diagram ul:before {
  left: 0;
}

.process_diagram ul:after {
  right: 0;
}

/* put connecting vertical lines */
.process_diagram ul>li:after,
.process_diagram ul>li:before {
  position: absolute;
  content: "";
  top: 0;
  bottom: 0;
  width: var(--linewidth);
  height: 100%;
  display: block;
}

.process_diagram ul>li:before {
  left: 0;
  border-width: 0 0 0 var(--linethick);
}

.process_diagram ul>li:after {
  right: 0;
  border-width: 0 var(--linethick) 0 0;
}

/* correct length and position of dashes for first and last li-item in ul */
.process_diagram ul>li:first-child:before,
.process_diagram ul>li:first-child:after {
  top: 50%;
  bottom: auto;
  height: 50%;
}

.bottom:before {
  /* top: 0; */
  bottom: auto;
  height: 40% !important;
  left: 50% !important;
}

.bottom:after {
  display: none !important;
}

.bottom_content {
  margin-top: 150% !important;
  width: 400px !important;
}

.bottom_content:before,
.bottom_content:after {
  display: none !important;
}

.process_diagram ul>li:last-child:before,
.process_diagram ul>li:last-child:after {
  /* top: 0; */
  bottom: auto;
  height: 50%;
}

/* put left and right dashes */
.process_diagram li>div {
  position: relative;
  margin: 0 var(--linewidth);
  padding: 1em;
  border-width: var(--linethick);
}

.process_diagram li>div:before,
.process_diagram li>div:after {
  content: "";
  top: 50%;
  width: var(--linewidth);
  position: absolute;
  border-width: var(--linethick) 0 0;
  height: 50%;
}

.process_diagram li>div:after {
  right: calc(0em - var(--linewidth));
  margin-right: calc(0px - var(--linethick));
}

.process_diagram li>div:before {
  left: calc(0em - var(--linewidth));
  margin-left: calc(0px - var(--linethick));
}

.process_diagram li:last-child>div:after,
.process_diagram li:last-child>div:before {
  top: 0;
  border-width: 0 0 var(--linethick);
}

/* remove dash for the very first/last nodes keeping margin and padding */
.process_diagram>li:first-child>div:before,
.process_diagram>li:first-child>ul:before,
.process_diagram>li:first-child>ul>li:before,
.process_diagram>li:first-child>ul>li>div:first-child:before,
.process_diagram>li:first-child>ul>li>ol>li:first-child>div:before,
.process_diagram>li:last-child>div:after,
.process_diagram>li:last-child>ul:after {
  border: 0;
}

/* remove double dashes */
ol.process_diagram>li>div:after,
.process_diagram ol>li>div:after,
ol.process_diagram>li>ul:after,
.process_diagram ol>li>ul:after {
  display: none
}

ol.process_diagram>li>div,
.process_diagram ol>li>div {
  margin-right: 0;
}

ol.process_diagram>li>ul,
.process_diagram ol>li>ul {
  padding-right: 0;
}

/* last dashes are not double and need to be recovered */
ol.process_diagram>li:last-child>div:after,
.process_diagram ol>li:last-child>div:after,
ol.process_diagram>li:last-child>ul:after,
.process_diagram ol>li:last-child>ul:after {
  display: block;
}

ol.process_diagram>li:last-child>div,
.process_diagram ol>li:last-child>div {
  margin-right: var(--linewidth);
}

ol.process_diagram>li:last-child>ul,
.process_diagram ol>li:last-child>ul {
  padding-right: var(--linewidth);
}
</style>
