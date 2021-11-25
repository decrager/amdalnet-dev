<template>
  <div class="app-container">
    <el-card>
      <WorkFlow />
      <el-tabs v-model="activeName" type="card">
        <el-tab-pane
          v-if="isAdmin"
          label="Verifikasi & Rapat"
          name="verifikasi"
        >
          <VerifikasiRapat v-if="activeName === 'verifikasi'" />
        </el-tab-pane>
        <el-tab-pane
          v-else-if="isSubtance"
          label="Berita Acara"
          name="beritaacara"
        >
          <BeritaAcara v-if="activeName === 'beritaacara'" />
        </el-tab-pane>
        <el-tab-pane
          v-else-if="isExaminer || isFormulator"
          label="Uji Kelayakan"
          name="ujikelayakan"
        >
          <UjiKelayakan v-if="activeName === 'ujikelayakan'" />
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import VerifikasiRapat from '@/views/pengujian-rkl-rpl/components/verifikasiRapat/index';
import BeritaAcara from '@/views/pengujian-rkl-rpl/components/beritaAcara/index';
import UjiKelayakan from '@/views/pengujian-rkl-rpl/components/ujiKelayakan/index';
import WorkFlow from '@/components/Workflow';

export default {
  name: 'PengujianRKLRPL',
  components: {
    VerifikasiRapat,
    BeritaAcara,
    UjiKelayakan,
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
    isExaminer() {
      return this.userInfo.roles.includes('examiner');
    },
    isFormulator() {
      return this.userInfo.roles.includes('formulator');
    },
  },
  async created() {
    this.userInfo = await this.$store.dispatch('user/getInfo');
    this.$store.dispatch('getStep', 6);
    if (this.userInfo.roles.includes('examiner-substance')) {
      this.activeName = 'beritaacara';
    } else if (this.userInfo.roles.includes('examiner-administration')) {
      this.activeName = 'verifikasi';
    } else if (this.userInfo.roles.includes('examiner')) {
      this.activeName = 'ujikelayakan';
    } else if (this.userInfo.roles.includes('formulator')) {
      this.activeName = 'ujikelayakan';
    }
  },
};
</script>
