<template>
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
            <li>Pengaruh rencana Usaha dan/atau Kegiatan terhadap kondisi Usaha dan/atau kegiatan lain di sekitar lokasi</li>
            <li>Intensitas perhatian masyarakat terhadap rencana Usaha dan/atau Kegiatan baik harapan, dan kekhawatiran persetujuan atau penolakan terhadap rencana Usaha  dan/atau Kegiatan  rencana Usaha dan/atau Kegiatan atau sebaliknya</li>
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
</template>

<script>
// import go from 'gogogojsvue';
import axios from 'axios';

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
  },
  methods: {
    getData() {
      axios.get('api/bagan-alir/' + this.projectId)
        .then((response) => {
          this.data = response.data;
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

.card__wrapper .card_first {
    flex: 1;
    justify-content: flex-start;
    align-content: flex-start;
    display: flex;
    flex-direction: column
}

.card__wrapper .card_second {
    flex: 1;
    justify-content: center;
    align-content: center;
    display: flex;
    flex-direction: column
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
    flex-direction: column
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
    background-color: #3FDC6B;
    font-weight: bold;
    color: white;
}
.card__wrapper .el-card .el-card__body {
    background-color: #55BF73;
    color: white
}
</style>
