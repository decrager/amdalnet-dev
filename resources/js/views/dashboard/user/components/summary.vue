<template>
  <div class="user-summary">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Persetujuan</span>
      </div>
      <!-- flexbox -->
      <el-skeleton v-if="isLoading" :rows="6" />
      <div v-else class="user-summary-cards">
        <!-- -->
        <el-row>
          <el-col :span="8">
            <el-card id="total" class="box-card" style="background: #0A2F08; color: white;">
              <span class="title">Total</span>
              <span class="value">{{ summary.total }}</span>
            </el-card>
          </el-col>
          <el-col :span="16">
            <el-row>
              <el-col :span="12">
                <el-card class="box-card" style="background: #638761; color: white;">
                  <span class="title">AMDAL</span>
                  <span class="value">{{ summary.amdal }}</span>
                </el-card>
              </el-col>
              <el-col :span="12">
                <el-card class="box-card" style="background: #61929d; color: white;">
                  <span class="title">UKL-UPL</span>
                  <span class="value">{{ summary.uklupl }}</span>
                </el-card>
              </el-col>
            </el-row>
            <el-row>
              <el-col :span="12">
                <el-card class="box-card" style="background: #54abab; color: white;">
                  <span class="title">SPPL</span>
                  <span class="value">{{ summary.sppl }}</span>
                </el-card>
              </el-col>
              <el-col :span="12">
                <el-card class="box-card" style="background: #a5c5bc; color: white;">
                  <span class="title smaller">Addendum Andal dan RKL RPL</span>
                  <span class="value" style="margin-top: 0.5em;">{{ summary.addendum }}</span>
                </el-card>
              </el-col>
            </el-row>
          </el-col>
        </el-row>
      </div>
    </el-card>
  </div>
</template>
<script>
export default {
  name: 'UserSummary',
  props: {
    user: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      isLoading: true,
      /* data: [
        { label: 'Total', value: 18 },
        { label: 'AMDAL', value: 3 },
        { label: 'UKL-UPL', value: 5 },
        { label: 'SPPL', value: 7 },
        { label: 'Adendum AMDAL', value: 0 },
        { label: 'Adendum UKL-UPL', value: 0 },
      ],*/
      // summary: { total: 18, amdal: 3, uklupl: 5, sppl: 7, addendum_uklupl: 0, addendum_amdal: 0 },
      summary: { total: 18, amdal: 3, uklupl: 5, sppl: 7, addendum: 0 },

    };
  },
  mounted() {
    this.isLoading = false;
  },
};
</script>
<style lang="scss" scoped>

.user-summary-cards {
  .el-card {
    height: 150px;
    margin:0;
    padding:0;

    span {
      display:block;
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

    &#total {
      height: 300px;
      .value {
        position: relative;
        top: 80px;
        font-weight: bolder;
      }
    }
  }
}

</style>
