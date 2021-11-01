<template>
  <div>
    <h3>Komponen Kegiatan yang Menjadi Sumber Dampak</h3>
    <sumber-dampak />
    <h3 style="margin-top:20px;">Rona Lingkungan Awal</h3>
    <rona-awal-table />
  </div>
</template>

<script>
import SumberDampak from './SumberDampak.vue';
import RonaAwalTable from './RonaAwalTable.vue';

export default {
  name: 'RonaLingkunganAwal',
  components: { SumberDampak, RonaAwalTable },
  data() {
    return {
    };
  },
  mounted() {
  },
  methods: {
  },
};
</script>
