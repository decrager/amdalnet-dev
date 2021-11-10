<template>
  <el-collapse v-model="activeName" accordion>
    <el-collapse-item name="1">
      <template slot="title">
        <span class="title">MATRIKS RENCANA PENGELOLAAN (RKL)</span>
      </template>
      <TableRKL />
    </el-collapse-item>
    <el-collapse-item name="2">
      <template slot="title">
        <span class="title">MATRIKS RENCANA PEMANTAUAN LINGKUNGAN (RPL)</span>
      </template>
      <TableRPL />
    </el-collapse-item>
  </el-collapse>
</template>

<script>
import TableRKL from '@/views/rkl-rpl/components/matriks-table/TableRKL';
import TableRPL from '@/views/rkl-rpl/components/matriks-table/TableRPL';

export default {
  name: 'Matriks',
  components: {
    TableRKL,
    TableRPL,
  },
  data() {
    return {
      activeName: '1',
    };
  },
};
</script>
