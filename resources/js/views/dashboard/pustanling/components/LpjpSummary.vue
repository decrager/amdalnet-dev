<template>
  <el-card class="box-card" style="margin-bottom: 1.5em">
    <div slot="header" class="clearfix">
      <span>Jumlah LPJP</span>
    </div>
    <div class="user-summary-cards">
      <el-row :gutter="10">
        <el-col :span="8">
          <el-card
            id="total"
            v-loading="loading"
            class="box-card"
            style="background: #033022; color: white"
          >
            <span class="title">Total LPJP</span>
            <span class="value">{{ total }}</span>
            <div class="link-list">
              <span>
                More List <el-button icon="el-icon-right" circle size="small" />
              </span>
            </div>
          </el-card>
        </el-col>
        <el-col :span="8">
          <el-card
            id="total"
            v-loading="loading"
            class="box-card"
            style="background: #347437; color: white"
          >
            <span class="title">Aktif</span>
            <span class="value">{{ active }}</span>
            <div class="link-list">
              <span>
                More List <el-button icon="el-icon-right" circle size="small" />
              </span>
            </div>
          </el-card>
        </el-col>
        <el-col :span="8">
          <el-card
            id="total"
            v-loading="loading"
            class="box-card"
            style="background: #449748; color: white"
          >
            <span class="title">NonAktif</span>
            <span class="value">{{ nonActive }}</span>
            <div class="link-list">
              <span>
                More List <el-button icon="el-icon-right" circle size="small" />
              </span>
            </div>
          </el-card>
        </el-col>
      </el-row>
    </div>
  </el-card>
</template>

<script>
import axios from 'axios';

export default {
  name: 'LpjpSummary',
  data() {
    return {
      total: 0,
      active: 0,
      nonActive: 0,
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
        .get('/api/dashboard/lpjp-amount')
        .then((data) => {
          this.total = data.data.total;
          this.active = data.data.active;
          this.nonActive = data.data.non_active;
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
    height: 167px;
    margin: 0;
    padding: 0;
    overflow: hidden;
    overflow: -moz-hidden-unscrollable;

    span {
      display: block;
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

    .link-list {
      text-align: right;
      font-size: 80%;
      cursor: pointer;

      &:hover {
        text-decoration: underline;
      }
    }
  }
}
</style>
