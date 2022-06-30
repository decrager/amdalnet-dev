<template>
  <div>
    <el-table
      :data="data"
    >
      <el-table-column label="No." />
      <el-table-column label="Lampiran" />
    </el-table>
  </div>
</template>
<script>
export default {
  name: 'ProjectAttachments',
  data(){
    return {
      data: [],
      stages: ['Penapisan', 'Kerangka Acuan', 'Andal RKL RPL'],
    };
  },
};
</script>
