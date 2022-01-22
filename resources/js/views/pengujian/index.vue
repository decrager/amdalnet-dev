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
          <Verifikasi
            v-if="activeName === 'verifikasi'"
            @changeIsComplete="changeIsComplete($event)"
          />
        </el-tab-pane>
        <el-tab-pane
          v-if="isAdmin && isComplete"
          label="Undangan Rapat"
          name="undanganrapat"
        >
          <UndanganRapat v-if="activeName === 'undanganrapat'" />
        </el-tab-pane>
        <el-tab-pane v-if="isSubtance && isComplete" label="Berita Acara" name="beritaacara">
          <BeritaAcara v-if="activeName === 'beritaacara'" />
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import Verifikasi from '@/views/pengujian/components/verifikasi/index';
import UndanganRapat from '@/views/pengujian/components/undanganRapat/index';
import BeritaAcara from '@/views/pengujian/components/beritaAcara/index';
import WorkFlow from '@/components/Workflow';
import Resource from '@/api/resource';
const verifikasiRapatResource = new Resource('testing-verification');

export default {
  name: 'Dump',
  components: {
    Verifikasi,
    UndanganRapat,
    BeritaAcara,
    WorkFlow,
  },
  data() {
    return {
      activeName: 'verifikasi',
      userInfo: {
        roles: [],
      },
      isComplete: false,
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
    this.isComplete = await verifikasiRapatResource.list({
      checkComplete: 'true',
      idProject: this.$route.params.id,
    });
    if (this.userInfo.roles.includes('examiner-substance')) {
      this.activeName = 'beritaacara';
      this.$store.dispatch('getStep', 6);
    } else if (this.userInfo.roles.includes('examiner-administration')) {
      this.activeName = 'verifikasi';
      this.$store.dispatch('getStep', 3);
    }
  },
  methods: {
    changeIsComplete({ isComplete }) {
      this.isComplete = isComplete;
    },
  },
};
</script>
