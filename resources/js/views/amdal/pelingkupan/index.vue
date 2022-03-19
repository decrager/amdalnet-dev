<template>
  <div>
    <master-komponen />
    <tabel-pelingkupan />
  </div>
</template>
<script>
import TabelPelingkupan from './TabelPelingkupan.vue';
import MasterKomponen from './MasterKomponen.vue';

export default {
  name: 'ModulPelingkupan',
  components: { TabelPelingkupan, MasterKomponen },
  data(){
    return {
    };
  },
  mounted() {
  },
  methods: {

  },
};
</script>
<style>

.master-komponen .el-card__header {
      padding: 5px 0 !important;
      background: #39773b;
      color: #ffffff;

}

.master-komponen  .el-card__body {
  background: #f5f5f5;
}

.master-komponen .el-card__body .components-container {
  max-height: 350px;
  overflow-y: auto;
  padding: 5px 0;
  margin: 0 auto 0.6em;

}

.master-komponen .el-card__body p {
  font-size:90%;
  margin: 0 0 0.2em 0;
}

</style>

