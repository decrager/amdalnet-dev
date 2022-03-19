<template>
  <div class="master-komponen">
    <el-card shadow="never">
      <div slot="header" class="clearfix card-header" style="text-align:center; font-weight:bold; text-transform: uppercase;">
        <span>Komponen Lingkungan ({{ this.components.length }})</span>
      </div>
      <components-list />

      <!-- form -->
      <el-button icon="el-icon-plus" size="mini" circle type="primary" plain @click="addComponent" />
      <!-- <form-komponen-kegiatan
        :show="showForm"
        :mode="mode"
        :input="selectedData"
        @onSave="onSaveData"
        @onClose="showForm = false"
      /> -->

    </el-card>
  </div>
</template>
<script>
export default {
  name: 'KomponenLingkungan',
  data(){
    return {
      components: [],
    };
  },
};
</script>
<style>

</style>
