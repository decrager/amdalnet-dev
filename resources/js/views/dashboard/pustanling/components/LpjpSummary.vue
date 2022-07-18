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
            class="box-card"
            style="background: #033022; color: white"
          >
            <span class="title">Total LPJP</span>
            <span class="value">18</span>
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
            class="box-card"
            style="background: #347437; color: white"
          >
            <span class="title">Aktif</span>
            <span class="value">10</span>
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
            class="box-card"
            style="background: #449748; color: white"
          >
            <span class="title">NonAktif</span>
            <span class="value">8</span>
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
export default {
  name: 'LpjpSummary',
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
