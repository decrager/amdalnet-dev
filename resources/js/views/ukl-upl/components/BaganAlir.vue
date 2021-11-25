<template>
  <div>
    <div id="bagan" class="main__wrapper">
      <div class="card__wrapper">
        <div class="card_first">
          <el-card class="box-card" style="margin: 10px 0;">
            <div slot="header" class="clearfix">
              <span>Rencana Kegiatan</span>
            </div>
            <div v-for="rencana in data.rencana_kegiatan" :key="rencana.id" class="text item">
              {{ rencana.name }}
            </div>
          </el-card>
          <el-card class="box-card" style="margin: 10px 0;">
            <div slot="header" class="clearfix">
              <span>Kegiatan Lain</span>
            </div>
            <div v-for="rencana in data.kegiatan_lain" :key="rencana.id" class="text item">
              {{ rencana.name }}
            </div>
          </el-card>
          <el-card class="box-card">
            <div slot="header" class="clearfix">
              <span>Rona Lingkungan Hidup</span>
            </div>
            <div v-for="rencana in data.rona_awal" :key="rencana.id" class="text item">
              {{ rencana.name }}
            </div>
          </el-card>
          <el-card class="box-card" style="margin: 10px 0;">
            <div slot="header" class="clearfix">
              <span>Saran Tanggapan dan Pendapat Masyarakat</span>
            </div>
            <div v-for="rencana in data.feedback" :key="rencana.id" class="text item">
              <span>Kekhawatiran :</span>
              <ul>
                <li>{{ rencana.concern }}</li>
              </ul>

              <br>
              <span>Harapan :</span>
              <ul>
                <li>{{ rencana.expectation }}</li>
              </ul>
            </div>
          </el-card>
          <div class="first_horizontal_line" />
        </div>

        <div class="card_second">
          <el-card class="box-card" style="margin: 10px 0;">
            <div slot="header" class="clearfix">
              <span>Identifikasi Dampak Potensial</span>
            </div>
            <div class="text item">
              <ul>
                <li>-</li>
              </ul>
            </div>
          </el-card>
        </div>

        <div class="card_third">
          <el-card class="box-card" style="margin: 10px 0;">
            <div slot="header" class="clearfix">
              <span>Dampak Potensial</span>
            </div>
            <div class="text item">
              <p>Pra-konstruksi :</p>
              <ul>
                <li>Peningkatan kebisingan akibat mobilisasi alat berat</li>
                <li>Penurunan mata pencaharian akibat mobilitas pekerja</li>
              </ul>
              <p>Konstruksi :</p>
              <ul>
                <li>Peningkatan kebisingan akibat mobilisasi alat berat</li>
                <li>Penurunan mata pencaharian akibat mobilitas pekerja</li>
              </ul>
            </div>
          </el-card>
        </div>

        <div class="card_fourth">
          <el-card class="box-card" style="margin: 10px 0;">
            <div slot="header" class="clearfix">
              <span>Evaluasi Dampak Penting</span>
            </div>
            <div class="text item">
              <ul>
                <li>Besaran rencana Usaha dan/atau Kegiatan yang menyebabkan dampak tersebut dan rencana pengelolaan lingkungan awal yang menjadi bagian rencana Usaha dan/atau kegiatan untuk menanggulangi dampak</li>
                <li>Kondisi rona lingkungan yang ada termasuk kemampuan mendukung Usaha dan/atau kegiatan tersebut atau tidak</li>
              </ul>
            </div>
          </el-card>
        </div>

        <div class="card_fifth">
          <el-card class="box-card" style="margin: 10px 0;">
            <div slot="header" class="clearfix">
              <span>Dampak Penting Hipotetik</span>
            </div>
            <div class="text item">
              <p>Pra-konstruksi :</p>
              <ul>
                <li>Peningkatan kebisingan akibat mobilisasi alat berat</li>
                <li>Penurunan mata pencaharian akibat mobilitas pekerja</li>
              </ul>
              <p>Konstruksi :</p>
              <ul>
                <li>Peningkatan kebisingan akibat mobilisasi alat berat</li>
                <li>Penurunan mata pencaharian akibat mobilitas pekerja</li>
              </ul>
            </div>
          </el-card>
        </div>
      </div>
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
.main__wrapper {
  margin: 0;
  padding: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
}

.card__wrapper {
    display: flex;
    flex-direction: row;
    column-gap: 50px;
    margin: 0;
    padding: 0;
    width: 1570px;
    height: 1000px;
    /* overflow-x: scroll;
    overflow-y: scroll; */

}

.card__wrapper .card_first {
    flex: 1;
    justify-content: flex-start;
    align-content: flex-start;
    display: flex;
    flex-direction: column;
}

.card__wrapper .first_horizontal_line {
  position: absolute;
  top: 677px;
  left: 370px;
  width: 2px;
  height: 44%;
  background-color: #55BF73
}
/*
.card_first::before{
  content: "";

} */

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
    background-color: #55BF73;
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
    background-color: #55BF73;
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
    height: 22%;
    background-color: #55BF73
}

.card__wrapper .card_third {
    flex: 1;
    justify-content: flex-start;
    align-content: flex-start;
    display: flex;
    flex-direction: column;
    margin-top: 50px;
}

.card__wrapper .card_fourth {
    flex: 1;
    justify-content: flex-end;
    align-content: flex-end;
    display: flex;
    flex-direction: column;
    margin-bottom: 100px;
}

.card__wrapper
.card_fourth::before{
    content: "";
    position: absolute;
    top: 705px;
    left: 1160px;
    width: 2px;
    height: 19%;
    background-color: #55BF73
}

.card__wrapper .card_fifth {
    flex: 1;
    justify-content: flex-start;
    align-content: flex-start;
    display: flex;
    flex-direction: column;
    margin-top: 50px;
}
/* .card__wrapper {
    max-height: 800px;
} */
.card__wrapper .el-card .el-card__header {
    background-color: #55BF73;
    font-weight: bold;
    color: white;
}
.card__wrapper .el-card .el-card__body {
    background-color: #55BF73;
    color: white
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
