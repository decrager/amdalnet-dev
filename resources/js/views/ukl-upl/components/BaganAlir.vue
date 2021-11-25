<template>
  <div>
    <div id="bagan" class="card__wrapper">
      <div class="card_first">
        <el-card class="box-card" style="margin: 10px 0;">
          <div @click="rencanaKegiatan()">
            <span>Rencana Kegiatan</span>
          </div>
        </el-card>
        <el-card class="box-card" style="margin: 10px 0;">
          <div @click="kegiatanLain()">
            <span>Kegiatan Lain</span>
          </div>
        </el-card>

        <el-card class="box-card" style="margin: 10px 0;">
          <div @click="rona()">
            <span>Rona Lingkungan Hidup</span>
          </div>
        </el-card>

        <el-card class="box-card" style="margin: 10px 0;">
          <div @click="feedback()">
            <span>Saran Tanggapan dan Pendapat Masyarakat</span>
          </div>
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

    </div>

    <el-col :span="24" style="text-align:right; margin:2em 0;"><el-button size="small" type="warning" @click="download">Export PDF</el-button></el-col>
    <div id="pdf" />

    <div v-if="showRencanaKegiatan">
      <rencana-kegiatan
        :selected-rencana-kegiatan="selectedRencanaKegiatan"
        :open-rencana-kegiatan="showRencanaKegiatanDialog"
      />
    </div>

    <div v-if="showKegiatanLain">
      <kegiatan-lain
        :selected-kegiatan-lain="selectedKegiatanLain"
        :open-kegiatan-lain="showKegiatanLainDialog"
      />
    </div>

    <div v-if="showRona">
      <rona-lingkungan-hidup
        :selected-rona="selectedRona"
        :open-rona="showRonaDialog"
      />
    </div>

    <div v-if="showFeedback">
      <feedbacks
        :selected-feedback="selectedFeedback"
        :open-feedback="showFeedbackDialog"
      />
    </div>

  </div>
</template>

<script>
import axios from 'axios';
import * as html2canvas from 'html2canvas';
import JsPDF from 'jspdf';
import RencanaKegiatan from './modal-bagan-alir/RencanaKegiatan.vue';
import KegiatanLain from './modal-bagan-alir/KegiatanLain.vue';
import Feedbacks from './modal-bagan-alir/KegiatanLain.vue';

export default {
  components: {
    RencanaKegiatan,
    KegiatanLain,
    Feedbacks,
  },
  data() {
    return {
      projectId: this.$route.params && this.$route.params.id,
      selectedRencanaKegiatan: {},
      showRencanaKegiatanDialog: false,
      showRencanaKegiatan: false,
      selectedKegiatanLain: {},
      showKegiatanLainDialog: false,
      showKegiatanLain: false,
      selectedRona: {},
      showRonaDialog: false,
      showRona: false,
      selectedFeedback: {},
      showFeedbackDialog: false,
      showFeedback: false,
    };
  },
  methods: {
    async rencanaKegiatan() {
      this.showRencanaKegiatan = true;
      this.selectedRencanaKegiatan = {};
      this.showRencanaKegiatanDialog = true;
      await axios.get('/api/bagan-alir/' + this.projectId)
        .then(response => {
          this.selectedRencanaKegiatan = response.data.rencana_kegiatan;
        });
    },
    async kegiatanLain() {
      this.showKegiatanLain = true;
      this.selectedKegiatanLain = {};
      this.showKegiatanLainDialog = true;
      await axios.get('/api/bagan-alir/' + this.projectId)
        .then(response => {
          this.selectedKegiatanLain = response.data.kegiatan_lain;
        });
    },
    async rona() {
      this.showRona = true;
      this.selectedRona = {};
      this.showRonaDialog = true;
      await axios.get('/api/bagan-alir/' + this.projectId)
        .then(response => {
          this.selectedRona = response.data.rona_awal;
        });
    },
    async feedback() {
      this.showFeedback = true;
      this.selectedFeedback = {};
      this.showFeedbackDialog = true;
      await axios.get('/api/bagan-alir/' + this.projectId)
        .then(response => {
          this.selectedFeedback = response.data.feedback;
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
