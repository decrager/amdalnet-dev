<template>
  <div id="dpdph-master-table">

    <div style="text-align: right; margin-bottom: 0.5em;">Total: <span style="font-weight: bold">{{ impacts.length }}</span></div>
    <el-table
      :data="impacts"
      height="250"
      highlight-current-row
      header-row-class-name="dpdph-table"
      style="width: 100%"
      @current-change="selection"
    >
      <el-table-column
        label="No."
        width="60"
        align="center"
      >
        <template slot-scope="scope">
          {{ scope.$index + 1 }}
        </template>
      </el-table-column>
      <el-table-column
        prop="stage"
        label="Tahap"
        width="150"
        :filters="stageFilters"
        :filter-method="filterTag"
        filter-placement="bottom-end"
      />

      <el-table-column
        label="Dampak"
        :filters="changeFilters"
        :filter-method="onChangefilter"
        filter-placement="bottom-end"
      >
        <template slot-scope="scope">
          <div>
            <!--
          <span v-if="scope.row.id_change_type" :class=" (scope.row.change_type_name === null) ? 'empty':'change-type'" >{{ scope.row.change_type_name || '*belum terdefinisi*'  }}</span>
          -->
            <span class="empty"> {{ scope.row.change_type_name || '*belum terdefinisi*' }}</span>
            <!-- <span v-else >{{ changeTypes.find(e => e.id = scope.row.id_change_type).name }}</span> -->

            {{ scope.row.rona_awal }} akibat {{ scope.row.komponen }}

            <span style="font-weight: 500; margin-left: 1em;">
              <el-tag v-if="scope.row.is_hypothetical_significant" class="dph">DPH</el-tag>
              <el-tag v-else-if="scope.row.is_hypothetical_significant === false" type="info" class="dtph">DTPH</el-tag>
              <el-tag v-if="(scope.row.is_hypothetical_significant === false) && (scope.row.is_managed === true)" type="info" class="dph">Dikelola</el-tag>
              <el-tag v-if="scope.row.hasChanges" type="danger">~data berubah</el-tag>
            </span>
          </div>

        </template>
      </el-table-column>
    </el-table>
    <div v-if="totalChanges > 0" style="text-align: right; margin-bottom: 0.5em;">Data berubah: <span style="font-weight: bold">{{ totalChanges }}</span></div>
  </div>
</template>
<script>
export default {
  name: 'DampakHipotetikMasterTable',
  props: {
    impacts: {
      type: Array,
      default: function(){
        return [];
      },
    },
    stages: {
      type: Array,
      default: null,
    },
    changeTypes: {
      type: Array,
      default: null,
    },
    idProject: {
      type: Number,
      default: 0,
    },
    totalChanges: {
      type: Number,
      default: 0,
    },
  },
  data() {
    return {
      stageFilters: [
        { text: 'Pra Konstruksi', value: 'Pra Konstruksi' },
        { text: 'Konstruksi', value: 'Konstruksi' },
        { text: 'Operasi', value: 'Operasi' },
        { text: 'Pasca Operasi', value: 'Pasca Operasi' },
      ],
      changeFilters: [
        { text: 'Data berubah', value: true },
      ],
    };
  },
  watch: {
    impacts: function(){

    },
  },
  methods: {
    entryGrouping({ row, column, rowIndex, columnIndex }) {
      // there's a change in stage
    },
    filterTag(value, row){
      return row.stage === value;
    },
    onChangefilter(value, row){
      if (row.hasChanges) {
        return row.hasChanges === value;
      }
      return false;
    },
    selection(e){
      this.$emit('dataSelected', e);
    },
  },
};
</script>
<style lang="scss" >
#dpdph-master-table{
  overflow: auto;
  padding: 1em ;
  border: 1px solid #e0e0e0;
  border-radius: 1em;
  margin: 2em 0;
  padding: 1em 2em 2em;

  .el-table {
    .current-row { font-weight: bold; }
  }

    span.empty { color: red; }
    span.change-type { text-decoration: underline; }

  .dpdph-table{
    th{
        background: none !important;
        color: inherit !important;

      }
  }
}

</style>
