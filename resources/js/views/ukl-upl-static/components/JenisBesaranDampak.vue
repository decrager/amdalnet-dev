<template>
  <div>
    <el-button
      type="success"
      size="small"
      icon="el-icon-check"
      @click="handleSaveForm()"
    > Simpan Perubahan </el-button>

    <table>
      <thead>
        <tr>
          <th>Komponen Dampak</th>
          <th>Komponen Rona Lingkungan Awal</th>
          <th>&nbsp;</th>
          <th>Sumber Dampak</th>
          <th>Besaran Dampak</th>
        </tr>
      </thead>
    </table>
  </div>
</template>
<script>
export default {
  name: 'JenisBesaranDampak',
  props: {
    stages: {
      type: Array,
      default: function(){
        return {};
      },
    },
  },
  methods: {
    handleSaveForm() {
    },
  },
};
</script>
