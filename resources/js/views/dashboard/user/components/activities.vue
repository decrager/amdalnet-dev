<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Kegiatan Terbaru</span>
      </div>
      <el-skeleton :rows="6" animated />
    </el-card>
  </div>
</template>
<script>
export default {
  name: 'UserActivities',

};
</script>
