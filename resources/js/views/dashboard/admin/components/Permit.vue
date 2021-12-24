<template>
  <el-card class="box-card" style="margin-left:1.5em; min-height: 490px;">
    <div style="margin: 0 0 1em 0; font-weight:bold;">Kewenangan Izin</div>
    <el-skeleton :rows="3" animated class="item" />
    <el-skeleton :rows="3" animated class="item" />
    <el-skeleton :rows="3" animated class="item" />
  </el-card>
</template>
<script>
export default {
  name: 'PermitByRegion',
};
</script>
<style lang="scss" scoped>
.item {
  margin: 1em 0 1em;
  padding: 1em 0.5em;
  border: 1px solid #ddd;
  border-radius: 0.8em;
}
.item:last-child {
  margin-bottom: 0;
}
</style>
