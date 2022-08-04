<template>
  <el-table
    v-loading="loading"
    :data="data"
  >
    <el-table-column
      type="index"
      width="50"
    />
    <el-table-column label="Lampiran" prop="label" />
    <el-table-column label="" align="center">
      <template slot-scope="scope">
        <el-link :href="scope.row.url" target="_blank" icon="el-icon-download" type="primary">{{ scope.row.filename }}</el-link>
      </template>
    </el-table-column>
  </el-table>
</template>
<script>
export default {
  name: 'AttachmentTable',
  data(){
    return {
      data: [
        {
          label: 'File Lampiran',
          filename: 'nama_file.ext',
          url: 'link.ext',
        },
      ],
    };
  },
};
</script>
