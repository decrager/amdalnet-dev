<template>
  <el-collapse v-model="activeName" accordion>
    <el-collapse-item name="tableAndal">
      <template slot="title" class="head-accordion">
        <span class="title">ANALISA DAMPAK LINGKUNGAN</span>
      </template>
      <TableAndal />
    </el-collapse-item>
  </el-collapse>
</template>

<script>
import TableAndal from '@/views/penyusunan-andal/components/Table';

export default {
  name: 'Andal',
  components: {
    TableAndal,
  },
  data() {
    return {
      activeName: 'tableAndal',
    };
  },
};
</script>

<style scoped>
.title {
  background: #099c4b;
  width: 100%;
  color: white;
  font-weight: bold;
  display: block;
  padding-left: 1rem;
  margin-bottom: 1rem;
}
</style>
