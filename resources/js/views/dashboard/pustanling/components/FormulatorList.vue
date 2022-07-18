<template>
  <div>
    <el-input
      v-model="listQuery.search"
      suffix-icon="el-icon search"
      placeholder="Cari Penyusun..."
      style="margin-bottom: 0.8em"
      @input="inputSearch"
    >
      <el-button slot="append" icon="el-icon-search" @click="handleSearch" />
    </el-input>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Daftar Penyusun</span>
      </div>
      <div class="user-summary-cards">
        <el-card>
          <el-row :gutter="32">
            <el-col :md="16" :sm="16">
              <h3>hehey</h3>
            </el-col>
            <el-col :md="8" :sm="8" align="right">
              <el-button type="primary">Profil</el-button>
            </el-col>
          </el-row>
        </el-card>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  name: 'FormulatorList',
  data() {
    return {
      listQuery: {
        search: null,
      },
    };
  },
  methods: {
    inputSearch() {},
    handleSearch() {},
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
    border-radius: 22px;

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
