<template>
  <el-dialog :title="''" :visible.sync="show" :close-on-click-modal="false" :show-close="false">
    <div class="wrapTitleModal">
      <h2>Parameter Detail</h2>
      <i class="el-icon-close closeModal" @click="handleCancelComponent()" />
    </div>
    <div class="wrapTitleModal">
      <h4>Nama Parameter : <span>{{ listViewTitle }}</span></h4>
    </div>
    <el-table
      v-loading="loading"
      :data="listView"
      border
      :header-cell-style="{ background: '#3AB06F', color: 'white', border:'0' }"
      style="width: 100%"
    >
      <el-table-column label="No." width="70">
        <template slot-scope="scope">
          <span>{{ scope.$index + 1 }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Judul">
        <template slot-scope="scope">
          <span>{{ scope.row.title }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Nilai" width="100">
        <template slot-scope="scope">
          <span>{{ scope.row.value }}</span>
        </template>
      </el-table-column>
    </el-table>
    <div slot="footer" class="dialog-footer">
      <el-button class="btnClose" @click="handleCancelComponent()">Tutup</el-button>
    </div>
  </el-dialog>
</template>

<script>
export default {
  name: 'ComponentDialog',
  props: {
    show: Boolean,
    listView: {
      type: Array,
      default: () => [],
    },
    listViewTitle: {
      type: String,
      default: '-',
    },
  },
  data(){
    return {
    };
  },
  methods: {
    handleCancelComponent() {
      this.$emit('handleCancelComponent');
    },
  },
};
</script>
<style scoped>
  h2{margin: 0 !important; padding: 0 !important;font-weight: bold;color: #595959;}
  h4{color: #595959;}
  .el-dialog__body{padding-top: 0 !important;}
  .wrapTitleModal{display: flex;}
  .wrapTitleModal span{display: inline-block;margin-left: 1rem;font-size: 1rem;color: #595959;}
  .closeModal{display: inline-block; margin-left: auto;font-weight: bold;background: red;color: #fff;padding: 0.3rem;border-radius: 50%;position: relative;top: -2rem;cursor: pointer;}
  .btnClose{background-color: #3b4eee; color: #fff;}
</style>
