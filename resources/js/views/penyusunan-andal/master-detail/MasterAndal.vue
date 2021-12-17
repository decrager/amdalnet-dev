<template>
  <el-tabs v-model="activeName" type="card">
    <el-tab-pane label="Pra Konstruksi" name="pra-konstruksi">
      <TableMaster
        :loading="loading"
        :list="praKonstruksi"
        @showDetail="showDetail($event)"
      />
    </el-tab-pane>
    <el-tab-pane label="Konstruksi" name="konstruksi">
      <TableMaster
        :loading="loading"
        :list="konstruksi"
        @showDetail="showDetail($event)"
      />
    </el-tab-pane>
    <el-tab-pane label="Operasi" name="operasi">
      <TableMaster
        :loading="loading"
        :list="operasi"
        @showDetail="showDetail($event)"
      />
    </el-tab-pane>
    <el-tab-pane label="Pasca Operasi" name="pasca-operasi">
      <TableMaster
        :loading="loading"
        :list="pascaOperasi"
        @showDetail="showDetail($event)"
      />
    </el-tab-pane>
  </el-tabs>
</template>

<script>
import TableMaster from '@/views/penyusunan-andal/master-detail/TableMaster.vue';

export default {
  name: 'MasterAndal',
  components: {
    TableMaster,
  },
  props: {
    list: {
      type: Array,
      default: () => [],
    },
    loading: Boolean,
  },
  data() {
    return {
      activeName: 'pra-konstruksi',
    };
  },
  computed: {
    praKonstruksi() {
      const praKonstruksi = this.list.filter((pk) => {
        return pk.type === 'subtitle' && pk.stage === 'Pra Konstruksi';
      });
      return praKonstruksi;
    },
    konstruksi() {
      const konstruksi = this.list.filter((kons) => {
        return kons.type === 'subtitle' && kons.stage === 'Konstruksi';
      });
      return konstruksi;
    },
    operasi() {
      const operasi = this.list.filter((op) => {
        return op.type === 'subtitle' && op.stage === 'Operasi';
      });
      return operasi;
    },
    pascaOperasi() {
      const pascaOperasi = this.list.filter((po) => {
        return po.type === 'subtitle' && po.stage === 'Pasca Operasi';
      });
      return pascaOperasi;
    },
  },
  methods: {
    showDetail({ stage, id }) {
      this.$emit('showDetail', { stage, id });
    },
  },
};
</script>

<style>
.el-table .cell {
  word-break: break-word;
}
</style>
