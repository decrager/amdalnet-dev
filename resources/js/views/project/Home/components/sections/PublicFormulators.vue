<template>
  <div style="margin-bottom: 3em;">
    <template v-for="person in data">
      <el-row :key="'person_'+ person.id" :gutter="10" style="margin-bottom:1em;">
        <el-col :span="18">
          {{ person.name }}<br>
          <span v-if="person.membership_status">[{{ person.membership_status }}]</span> <span v-if="person.reg_no">No. Cert {{ person.reg_no }}</span>
        </el-col>
        <el-col :span="6">
          <el-link v-if="person.certificate" :href="person.certificate" target="_blank" icon="el-icon-download" type="primary">Sertifikat</el-link>&nbsp;&nbsp;&nbsp;&nbsp;
          <el-link v-if="(mode === 1) && (person.file !== null)" :href="person.file" target="_blank" icon="el-icon-download" type="primary">CV</el-link>
          <el-link v-else-if="(mode === 2) && (person.cv !== null)" :href="person.cv" target="_blank" icon="el-icon-download" type="primary">CV</el-link>
        </el-col>
      </el-row>
    </template>
  </div>
</template>
<script>
export default {
  name: 'PublicFormulatorPersons',
  props: {
    data: {
      type: Array,
      default: function() {
        return [];
      },
    },
    mode: {
      type: Number,
      default: 1, // 1: penyusun, 2: expert
    },
  },
};
</script>

