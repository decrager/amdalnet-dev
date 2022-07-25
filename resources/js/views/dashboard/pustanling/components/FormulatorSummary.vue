<template>
  <el-card class="box-card">
    <div slot="header" class="clearfix">
      <span>Jumlah Penyusun</span>
    </div>
    <div class="user-summary-cards">
      <el-row :gutter="10">
        <el-col :span="6">
          <el-card
            id="total"
            v-loading="loading"
            class="box-card"
            style="background: #eb8a00; color: white"
          >
            <span class="title">Total Penyusun</span>
            <span class="value">{{ total }}</span>
          </el-card>
        </el-col>
        <el-col :span="6">
          <el-card
            id="total"
            v-loading="loading"
            class="box-card"
            style="background: #fac400; color: white"
          >
            <span class="title">Aktif</span>
            <span class="value">{{ active }}</span>
          </el-card>
        </el-col>
        <el-col :span="6">
          <el-card
            id="total"
            v-loading="loading"
            class="box-card"
            style="background: #efcc4d; color: white"
          >
            <span class="title">NonAktif</span>
            <span class="value">{{ nonActive }}</span>
          </el-card>
        </el-col>
        <el-col :span="6">
          <el-card
            id="total"
            v-loading="loading"
            class="box-card"
            style="background: #f7d96c; color: white"
          >
            <span class="title">Sertifikasi</span>
            <span class="value">{{ certificated }}</span>
          </el-card>
        </el-col>
      </el-row>
    </div>
  </el-card>
</template>
<script>
import axios from 'axios';

export default {
  name: 'FormulatorSummary',
  data() {
    return {
      total: 0,
      active: 0,
      nonActive: 0,
      certificated: 0,
      loading: false,
    };
  },
  created() {
    this.getData();
  },
  methods: {
    getData() {
      this.loading = true;
      axios
        .get('/api/dashboard/formulator-amount')
        .then((data) => {
          this.total = data.data.total;
          this.active = data.data.active;
          this.nonActive = data.data.non_active;
          this.certificated = data.data.certificated;
          this.loading = false;
        })
        // eslint-disable-next-line handle-callback-err
        .catch((err) => {
          this.loading = false;
        });
    },
  },
};
</script>
<style lang="scss" scoped>
.user-summary-cards {
  .el-card {
    height: 150px;
    margin: 0;
    padding: 0;
    overflow: hidden;
    overflow: -moz-hidden-unscrollable;

    span {
      display: block;
      text-align: center;
    }

    .title {
      font-weight: bold;
    }
    .value {
      margin-top: 0.8em;
      font-size: 250%;
    }
    .smaller {
      font-size: 86%;
    }
  }
}
</style>
