<template>
  <div class="app-container pustanling-container">
    <el-row :gutter="32">
      <el-col :md="12" :sm="24">
        <LpjpSummary />
        <FormulatorSummary />
      </el-col>
      <el-col :md="12" :sm="24">
        <FormulatorList />
      </el-col>
    </el-row>
    <el-row :gutter="32">
      <el-col :md="24" :sm="24">
        <LpjpList class="lpjp-list-card" />
      </el-col>
    </el-row>
  </div>
</template>

<script>
import LpjpSummary from './components/LpjpSummary.vue';
import FormulatorSummary from './components/FormulatorSummary.vue';
import FormulatorList from './components/FormulatorList.vue';
import LpjpList from './components/LpjpList.vue';

export default {
  name: 'PustanlingDashboard',
  components: {
    LpjpSummary,
    FormulatorSummary,
    FormulatorList,
    LpjpList,
  },
};
</script>

<style>
div.pustanling-container div.el-card__header {
  background: #afc7af;
  font-weight: 700;
  font-size: 90%;
}

div.pustanling-container .avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 50%;
  position: relative;
  overflow: hidden;
  cursor: default;
}
div.pustanling-container .avatar-uploader .el-upload:hover {
  border-color: #409eff;
}
div.pustanling-container .avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  line-height: 178px;
  text-align: center;
}
div.pustanling-container .avatar {
  width: 5em;
  height: 5em;
  display: block;
}

div.pustanling-container .box-card.formulir-list > .el-card__body {
  height: 26.9em;
  overflow: auto;
}
</style>
