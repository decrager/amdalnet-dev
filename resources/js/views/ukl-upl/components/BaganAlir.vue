<template>
  <div>
    <div id="bagan" class="card__wrapper">
      <div class="card_first">
        <div @click="rencanaKegiatan">
          <el-card class="box-card" style="margin: 10px 0;">
            <span>Rencana Kegiatan</span>
          </el-card>
        </div>
        <el-card class="box-card" style="margin: 10px 0;">
          <span>Kegiatan Lain</span>
        </el-card>
        <el-card class="box-card" style="margin: 10px 0;">
          <span>Rona Lingkungan Hidup</span>
        </el-card>
        <el-card class="box-card" style="margin: 10px 0;">
          <span>Saran Tanggapan dan Pendapat Masyarakat</span>
        </el-card>
      </div>

      <div class="card_second">
        <el-card class="box-card" style="margin: 10px 0;">
          <span>Identifikasi Dampak Potensial</span>
        </el-card>
      </div>

      <div class="card_third">
        <el-card class="box-card" style="margin: 10px 0;">
          <span>Dampak Potensial</span>
        </el-card>
      </div>

      <div class="card_fourth">
        <el-card class="box-card" style="margin: 10px 0;">
          <span>Evaluasi Dampak Penting</span>
        </el-card>
      </div>

      <div class="card_fifth">
        <el-card class="box-card" style="margin: 10px 0;">
          <span>Dampak Penting Hipotetik</span>
        </el-card>
      </div>

      <div v-show="showRencanaKegiatan">
        <RencanaKegiatan
          :selected-rencana-kegiatan="selectedRencanaKegiatan"
          :show="showRencanaKegiatanDialog"
        />
      </div>

    </div>

    <el-col :span="24" style="text-align:right; margin:2em 0;"><el-button size="small" type="warning" @click="download">Export PDF</el-button></el-col>
    <div id="pdf" />
  </div>
</template>

<script>
import axios from 'axios';
import * as html2canvas from 'html2canvas';
import JsPDF from 'jspdf';
import RencanaKegiatan from './modal-bagan-alir/RencanaKegiatan.vue';

export default {
  components: {
    RencanaKegiatan,
  },
  data() {
    return {
      projectId: this.$route.params && this.$route.params.id,
      data: [],
      selectedRencanaKegiatan: {},
      showRencanaKegiatanDialog: false,
      showRencanaKegiatan: false,
    };
  },
  methods: {
    getData() {
      axios.get('api/bagan-alir/' + this.projectId)
        .then((response) => {
          this.data = response.data;
        });
    },
    rencanaKegiatan() {
      this.selectedRencanaKegiatan = {};
      this.showRencanaKegiatanDialog = true;
      axios.get('/api/bagan-alir/' + this.projectId)
        .then(response => {
          this.selectedRencanaKegiatan = response.data.rencana_kegiatan;
          console.log('rencana : ' + this.selectedRencanaKegiatan);
        });
    },
    download() {
      html2canvas(document.querySelector('#bagan'), { imageTimeout: 1000, useCORS: true }).then(canvas => {
        const img = canvas.toDataURL('image/png');
        const pdf = new JsPDF('landscape', 'mm', 'a3');
        pdf.addImage(img, 'PNG', 5, 5, 400, 95);
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
.flow__chart {
    margin: 0;
    padding: 0;
    height: 100%;
    width: 100%;
}

.card__wrapper {
    display: flex;
    flex-direction: row;
    column-gap: 50px;
}

.card__wrapper .el-card{
    background-color: #3FDC6B;
    color: white;
    cursor: pointer;
    text-align: center
}

.card__wrapper .card_first {
    flex: 1;
    justify-content: flex-start;
    align-content: flex-start;
    display: flex;
    flex-direction: column;
}

.card__wrapper
.card_first::before{
  content: "";
    position: absolute;
    top: 550px;
    left: 370px;
    width: 2px;
    height: 27.5%;
    background-color: #3FDC6B
}

.card__wrapper ul li {
    z-index: 10;
    position: relative;
}

.card__wrapper
.card_first
.el-card
.el-card__body::after {
    width: 4%;
    height: 2px;
    content: '';
    position: absolute;
    background-color: #3FDC6B;
    left: 18%;
    z-index: 1
}

.card__wrapper
.card_third
.el-card
.el-card__body::after {
    width: 60%;
    height: 2px;
    content: '';
    position: absolute;
    background-color: #3FDC6B;
    left: 22%;
    z-index: 1;
    top: 705px;
}

.card__wrapper .card_second {
    flex: 1;
    justify-content: center;
    align-content: center;
    display: flex;
    flex-direction: column;
    margin-top: 150px;
}

.card__wrapper
.card_second::before{
    content: "";
    position: absolute;
    top: 705px;
    left: 530px;
    width: 2px;
    height: 8%;
    background-color: #3FDC6B
}

.card__wrapper .card_third {
    flex: 1;
    justify-content: center;
    align-content: center;
    display: flex;
    flex-direction: column;
}

.card__wrapper .card_fourth {
    flex: 1;
    justify-content: center;
    align-content: center;
    display: flex;
    flex-direction: column;
    margin-top: 150px;
}

.card__wrapper
.card_fourth::before{
    content: "";
    position: absolute;
    top: 705px;
    left: 1160px;
    width: 2px;
    height: 8%;
    background-color: #3FDC6B
}

.card__wrapper .card_fifth {
    flex: 1;
    justify-content: center;
    align-content: center;
    display: flex;
    flex-direction: column;
}
/* .card__wrapper {
    max-height: 800px;
} */
.card__wrapper .el-card .el-card__header {
    background-color: #3FDC6B;
    font-weight: bold;
    color: white;
}

/* @media screen and (max-width: 1280px) {
  .card__wrapper .card_first .el-card {
      font-weight: 8px;
  }

  .card__wrapper .card_second .el-card {
      font-weight: 8px;
  }

  .card__wrapper .card_third .el-card {
      font-weight: 8px;
  }

  .card__wrapper .card_fourth .el-card {
      font-weight: 8px;
  }

  .card__wrapper .card_fifth .el-card {
      font-weight: 8px;
  }

  .card__wrapper
  .card_fourth::before{
      display: none;
  }

  .card__wrapper
  .card_second::before{
      display: none;
  }

  .card__wrapper
  .card_first
  .el-card:nth-child(2)
  .el-card__body:after {
      display: none;
  }

  .card__wrapper
  .card_first
  .el-card
  .el-card__body::after {
      display: none;
  }

  .card__wrapper
  .card_first::before{
    display: none;
  }
} */
</style>
