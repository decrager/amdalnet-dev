<template>
  <el-card class="box-card">
    <div slot="header" class="clearfix">
      <span>Persetujuan</span>
    </div>
    <div class="user-summary-cards">

      <el-row :gutter="10">
        <el-col :span="8">
          <el-card id="total" class="box-card" style="background: #303030; color: white;">
            <span class="title">Total</span>
            <span class="value">{{ summary.total }}</span>
          </el-card>
        </el-col>
        <el-col :span="8">
          <el-card id="total" class="box-card" style="background: #347437; color: white;">
            <span class="title">Amdal</span>
            <span class="value">{{ summary.amdal }}</span>
          </el-card>
        </el-col>
        <el-col :span="8">
          <el-card id="total" class="box-card" style="background: #449748; color: white;">
            <span class="title">UKL UPL MT/T</span>
            <span class="value">{{ summary.uklupl_mtt }}</span>
          </el-card>
        </el-col>
      </el-row>
      <el-row :gutter="10" style="margin-top: 0.6em;">
        <el-col :span="8">
          <el-card id="total" class="box-card" style="background: #EB8A00; color: white;">
            <span class="title">SPPL</span>
            <span class="value">{{ summary.sppl }}</span>
          </el-card>
        </el-col>
        <el-col :span="8">
          <el-card id="total" class="box-card" style="background: #FAC400; color: white;">
            <span class="title">Adendum Andal dan RKL RPL</span>
            <span class="value">{{ summary.addendum }}</span>
          </el-card>
        </el-col>
        <el-col :span="8">
          <el-card id="total" class="box-card" style="background: #449748; color: white;">
            <span class="title">UKL UPL R/MR</span>
            <span class="value">{{ summary.uklupl_rmr }}</span>
          </el-card>
        </el-col>
      </el-row>

    </div>
  </el-card>
</template>
<script>
import Resource from '@/api/resource';
const countResource = new Resource('dashboard/lpjp-count');

export default {
  name: 'LpjpSummary',
  props: {
    user: {
      type: Object,
      default: () => {},
    },
  },
  data(){
    return {
      summary: {},
      loading: false,
    };
  },
  watch: {
    user: function(val) {
      console.log('user? ', val);
      this.getCount();
    },
  },
  methods: {
    async getCount(){
      this.loading = true;
      await countResource.list({ lpjpId: this.user.id })
        .then((res) => {
          this.summary = res;
        })
        .finally(() => {
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
    margin:0;
    padding:0;
    overflow: hidden;
    overflow: -moz-hidden-unscrollable;

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
  }
}

</style>
