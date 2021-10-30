<template>
  <div class="app-container">
    <el-tabs type="card" @tab-click="handleClick">
      <el-tab-pane label="Lokasi Tapak Proyek">
        <lokasi-tapak-proyek />
      </el-tab-pane>
      <el-tab-pane label="Sumber Dampak">
        <sumber-dampak />
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import LokasiTapakProyek from './components/LokasiTapakProyek.vue';
import SumberDampak from './components/SumberDampak.vue';

export default {
  name: 'UklUpl',
  components: { LokasiTapakProyek, SumberDampak },
  data() {
    return {

    };
  },
  mounted() {

  },
  methods: {
    handleClick(tab, event) {
      console.log(tab, event);
    },
  },
};
</script>
