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
<style lang="scss" scoped>

</style>

