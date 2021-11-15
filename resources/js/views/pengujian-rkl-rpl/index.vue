<template>
  <div class="app-container">
    <el-card>
      <el-tabs v-model="activeName" type="card">
        <el-tab-pane label="Verifikasi & Rapat" name="verifikasi">
          <VerifikasiRapat v-if="activeName === 'verifikasi'" />
        </el-tab-pane>
        <el-tab-pane label="Berita Acara" name="beritaacara">
          <BeritaAcara v-if="activeName === 'beritaacara'" />
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import VerifikasiRapat from '@/views/pengujian-rkl-rpl/components/verifikasiRapat/index';
import BeritaAcara from '@/views/pengujian-rkl-rpl/components/beritaAcara/index';

export default {
  name: 'PengujianRKLRPL',
  components: {
    VerifikasiRapat,
    BeritaAcara,
  },
  data() {
    return {
      activeName: 'verifikasi',
    };
  },
};
</script>
