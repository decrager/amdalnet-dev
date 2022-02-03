<template>
  <div style="margin-bottom: 3em;">
    <template v-for="person in data">
      <el-row :key="'ketua_' +id+'-'+ person.id" :gutter="10" style="margin-bottom:1em;">
        <el-col :span="18">
          {{ person.name }}<br>
          [{{ person.membership_status }}] No. Cert {{ person.reg_no }}
        </el-col>
        <el-col :span="6">
          <el-link :href="person.certificate" target="_blank" icon="el-icon-download" type="primary">Sertifikat</el-link>&nbsp;&nbsp;&nbsp;&nbsp;<el-link :href="person.file" target="_blank" icon="el-icon-download" type="primary">CV</el-link>
        </el-col>
      </el-row>
    </template>
  </div>
</template>
<script>
export default {
  name: 'FormulatorPersons',
  props: {
    data: {
      type: Array,
      default: function() {
        return [];
      },
    },
  },
};
</script>
<style lang="scss" scoped>
  div.team-entry.el-row {
    margin: 0.5em 0 1em !important;
  }
</style>
