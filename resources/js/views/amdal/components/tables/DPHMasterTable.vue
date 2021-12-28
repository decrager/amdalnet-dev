<template>
  <div id="dpdph-master-table">

    <el-table
      :data="tableData"
      height="250"
      highlight-current-row
      header-row-class-name="dpdph-table"
      style="width: 100%"
    >
      <el-table-column
        type="index"
        label="No."
        width="100"
      />
      <el-table-column
        prop="date"
        label="Date"
        width="180"
      />
      <el-table-column
        prop="name"
        label="Name"
        width="180"
      />
      <el-table-column
        prop="address"
        label="Address"
      />
    </el-table>

  </div>
</template>
<script>
export default {
  name: 'DampakHipotetikMasterTable',
  data() {
    return {
      tableData: [{
        date: '2016-05-03',
        name: 'Tom',
        address: 'No. 189, Grove St, Los Angeles',
      }, {
        date: '2016-05-02',
        name: 'Tom',
        address: 'No. 189, Grove St, Los Angeles',
      }, {
        date: '2016-05-04',
        name: 'Tom',
        address: 'No. 189, Grove St, Los Angeles',
      }, {
        date: '2016-05-01',
        name: 'Tom',
        address: 'No. 189, Grove St, Los Angeles',
      }, {
        date: '2016-05-08',
        name: 'Tom',
        address: 'No. 189, Grove St, Los Angeles',
      }, {
        date: '2016-05-06',
        name: 'Tom',
        address: 'No. 189, Grove St, Los Angeles',
      }, {
        date: '2016-05-07',
        name: 'Tom',
        address: 'No. 189, Grove St, Los Angeles',
      }],
    };
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
  padding: 2em;

  .el-table {
    .current-row { font-weight: bold; }
  }

  /* .dpdph-table th{
    background: none !important;
    color: inherit !important;

  }*/
}

</style>
