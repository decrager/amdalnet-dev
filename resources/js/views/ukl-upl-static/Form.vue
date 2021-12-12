<template>
  <div id="formUKLUPL" class="app-container">

    <workflow-ukl />
    <h2>Formulir UKL UPL</h2>
    <el-button
      class="pull-right"
      type="success"
      size="small"
      icon="el-icon-check"
      @click="handleSaveForm()"
    > Simpan &amp; Lanjutkan </el-button>
    <p style="clear:both; ">&nbsp;</p>
    <el-collapse v-model="activeName" :accordion="true">
      <el-collapse-item name="1" title="Pelingkupan"><pelingkupan :stages="stages" /></el-collapse-item>
      <el-collapse-item name="2" title="Matriks Identifikasi Dampak"><matriks-identifikasi-dampak :stages="stages" /></el-collapse-item>
      <el-collapse-item name="3" title="Jenis dan Besaran Dampak"><jenis-besaran-dampak :stages="stages" /></el-collapse-item>

    </el-collapse>
  </div>
</template>
<script>
import WorkflowUkl from '@/components/WorkflowUkl';
import Pelingkupan from './components/Pelingkupan';
import MatriksIdentifikasiDampak from './components/MatriksIdentifikasiDampak';
import JenisBesaranDampak from './components/JenisBesaranDampak';

export default {
  name: 'FormulirUKLUPL',
  components: {
    WorkflowUkl,
    Pelingkupan,
    MatriksIdentifikasiDampak,
    JenisBesaranDampak,
  },
  data() {
    return {
      stages: ['Pra Konstruksi', 'Konstruksi', 'Operasi', 'Pasca Operasi'],
    };
  },
  methods: {
    handleSaveForm() {
    },
  },

};
</script>
<style scoped>
div#formUKLUPL {
    padding: 1em;

}

</style>
