<template>
  <div>
    <el-button style="font-weight: bold;" :loading="loading" type="warning" @click.prevent="showIframe = true">Daftar Rekap SPPL dan UKL-UPL Menengah Rendah</el-button>
    <el-dialog title="" width="90%" :visible.sync="showIframe">
      <iframe
        :src="iframeSrc"
        width="100%"
        height="600px"
        frameborder="0"
      />
    </el-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showIframe: false,
      iframeSrc: 'https://amdalnet-dev.menlhk.go.id/dashboard_mr',
    };
  },
};
</script>
