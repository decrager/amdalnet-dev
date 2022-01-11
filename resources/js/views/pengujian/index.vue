<template>
  <div class="app-container">
    <el-card>
      <WorkFlow />
      <el-tabs v-model="activeName" type="card">
        <el-tab-pane
          v-if="isAdmin"
          label="Pemeriksaan Berkas Administrasi Formulir KA"
          name="verifikasi"
        >
          <Verifikasi v-if="activeName === 'verifikasi'" />
        </el-tab-pane>
        <el-tab-pane
          v-else-if="isSubtance"
          label="Berita Acara"
          name="beritaacara"
        >
          <BeritaAcara v-if="activeName === 'beritaacara'" />
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import Verifikasi from '@/views/pengujian/components/verifikasi/index';
import BeritaAcara from '@/views/pengujian/components/beritaAcara/index';
import WorkFlow from '@/components/Workflow';

export default {
  name: 'Dump',
  components: {
    Verifikasi,
    BeritaAcara,
    WorkFlow,
  },
  data() {
    return {
      activeName: 'verifikasi',
      userInfo: {
        roles: [],
      },
    };
  },
  computed: {
    isSubtance() {
      return this.userInfo.roles.includes('examiner-substance');
    },
    isAdmin() {
      return this.userInfo.roles.includes('examiner-administration');
    },
  },
  async created() {
    this.userInfo = await this.$store.dispatch('user/getInfo');
    if (this.userInfo.roles.includes('examiner-substance')) {
      this.activeName = 'beritaacara';
      this.$store.dispatch('getStep', 6);
    } else if (this.userInfo.roles.includes('examiner-administration')) {
      this.activeName = 'verifikasi';
      this.$store.dispatch('getStep', 3);
    }
  },
};
</script>
