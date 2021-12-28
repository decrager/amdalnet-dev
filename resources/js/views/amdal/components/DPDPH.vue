<template>
  <div>
    <dampak-hipotetik-master-table />
    <dampak-hipotetik-detail-form />
  </div>
</template>
<script>
import DampakHipotetikMasterTable from './tables/DPHMasterTable.vue';
import DampakHipotetikDetailForm from './forms/DPHDetailForm.vue';
export default {
  name: 'DampakHipotetikMD',
  components: { DampakHipotetikDetailForm, DampakHipotetikMasterTable },
};
</script>
<style lang="scss" scoped>

</style>
