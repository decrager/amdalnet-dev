<template>
  <div class="app-container">
    <el-card>
      <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
        <el-tab-pane label="Materi" name="materi">
          <Materi />
        </el-tab-pane>
        <el-tab-pane label="Kebijakan" name="kebijakan">
          <Kebijakan />
        </el-tab-pane>
        <el-tab-pane label="Peraturan" name="peraturan">
          <Peraturan />
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import Materi from '../materi/index';
import Kebijakan from '../kebijakan/index';
import Peraturan from '../peraturan/index';
export default {
  name: 'MateriDanKebijakan',
  components: {
    Materi,
    Kebijakan,
    Peraturan,
  },
  data() {
    return {
      activeName: 'materi',
    };
  },
  created() {
    if (this.$route.params.tabActive){
      this.activeName = this.$route.params.tabActive;
    }
  },
  methods: {
    handleClick(tab, event) {
      this.activeName = tab.name;
    },
  },
};
</script>
