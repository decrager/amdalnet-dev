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
  </div>
</template>

<script>
import LpjpSummary from './components/LpjpSummary.vue';
import FormulatorSummary from './components/FormulatorSummary.vue';
import FormulatorList from './components/FormulatorList.vue';

export default {
  name: 'PustanlingDashboard',
  components: {
    LpjpSummary,
    FormulatorSummary,
    FormulatorList,
  },
};
</script>

<style>
div.pustanling-container div.el-card__header {
  background: #afc7af;
  font-weight: 700;
  font-size: 90%;
}
</style>
