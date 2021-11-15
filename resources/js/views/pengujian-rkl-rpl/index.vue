<template>
  <div class="app-container">
    <el-card>
      <el-tabs type="card">
        <el-tab-pane label="Verifikasi & Rapat">
          <VerifikasiRapat />
        </el-tab-pane>
        <el-tab-pane label="Berita Acara">
          <BeritaAcara />
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
};
</script>
