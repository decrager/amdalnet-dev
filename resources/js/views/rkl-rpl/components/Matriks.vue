<template>
  <el-collapse v-model="activeName" accordion>
    <el-collapse-item name="1">
      <template slot="title">
        <span class="title">MATRIKS RENCANA PENGELOLAAN (RKL)</span>
      </template>
      <TableRKL
        :list="matriksrkl"
        :lasttime="lasttimerkl"
        :loading="loadingrkl"
        @handleSubmit="handleSubmitRKL"
      />
    </el-collapse-item>
    <el-collapse-item name="2">
      <template slot="title">
        <span class="title">MATRIKS RENCANA PEMANTAUAN LINGKUNGAN (RPL)</span>
      </template>
      <TableRPL
        :institutions="institutions"
        :list="matriksrpl"
        :lasttime="lasttimerpl"
        :loading="loadingrpl"
        @handleSubmit="handleSubmitRPL"
      />
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
  props: {
    matriksrkl: {
      type: Array,
      default: () => [],
    },
    lasttimerkl: {
      type: String,
      default: () => null,
    },
    loadingrkl: Boolean,
    institutions: {
      type: Array,
      default: () => [],
    },
    matriksrpl: {
      type: Array,
      default: () => [],
    },
    lasttimerpl: {
      type: String,
      default: () => null,
    },
    loadingrpl: Boolean,
  },
  data() {
    return {
      activeName: '1',
    };
  },
  methods: {
    handleSubmitRKL() {
      this.$emit('handleSubmitRKL');
    },
    handleSubmitRPL() {
      this.$emit('handleSubmitRPL');
    },
  },
};
</script>
