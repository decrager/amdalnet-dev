<template>
  <div class="user-summary">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Persetujuan</span>
      </div>
      <!-- flexbox -->
      <el-skeleton v-if="isLoading" :rows="6" />
      <div v-else class="user-summary-cards">
        <!-- total, AMDAL, UKL-UPL -->
        <el-row :gutter="20">
          <el-col :span="8">
            <el-card class="box-card">
              <span class="title">Total</span>
              <span class="value">{{ summary.total }}</span>
            </el-card>
          </el-col>
          <el-col :span="8">
            <el-card class="box-card" style="background: #638761; color: white;">
              <span class="title">AMDAL</span>
              <span class="value">{{ summary.amdal }}</span>
            </el-card>
          </el-col>
          <el-col :span="8">
            <el-card class="box-card" style="background: #61929d; color: white;">
              <span class="title">UKL-UPL</span>
              <span class="value">{{ summary.uklupl }}</span>
            </el-card>
          </el-col>
        </el-row>

        <!-- SPPL, Addendum AMDAL, Addendum UKL-UPL-->
        <el-row :gutter="20">
          <el-col :span="8">
            <el-card class="box-card" style="background: #54abab; color: white;">
              <span class="title">SPPL</span>
              <span class="value">{{ summary.sppl }}</span>
            </el-card>
          </el-col>
          <el-col :span="8">
            <el-card class="box-card" style="background: #a7c5a5; color: white;">
              <span class="title smaller">Addendum AMDAL</span>
              <span class="value">{{ summary.addendum_amdal }}</span>
            </el-card>
          </el-col>
          <el-col :span="8">
            <el-card class="box-card" style="background: #a5c5bc; color: white;">
              <span class="title smaller">Addendum UKL-UPL</span>
              <span class="value">{{ summary.addendum_uklupl }}</span>
            </el-card>
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
      data: [
        { label: 'Total', value: 18 },
        { label: 'AMDAL', value: 3 },
        { label: 'UKL-UPL', value: 5 },
        { label: 'SPPL', value: 7 },
        { label: 'Adendum AMDAL', value: 3 },
        { label: 'Adendum UKL-UPL', value: 3 },
      ],
      summary: { total: 18, amdal: 3, uklupl: 5, sppl: 7, addendum_uklupl: 3, addendum_amdal: 3 },

    };
  },
  mounted() {
    this.isLoading = false;
  },
};
</script>
<style lang="scss" scoped>

.user-summary-cards {
  .el-card span {
    display:block;
    text-align: center;
    height: 2em;
  }
  .el-card .title {
    font-weight: bold;
  }
  .el-card .value {
    margin-top: 0.6em;
    font-size: 200%;
  }
  .el-card .smaller {
    font-size: 90%;
  }
}

</style>
