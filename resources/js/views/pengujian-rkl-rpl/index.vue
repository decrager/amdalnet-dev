<template>
  <div class="app-container">
    <el-card>
      <WorkFlow />
      <el-tabs v-model="activeName" type="card">
        <el-tab-pane
          v-if="isAdmin"
          label="Pemeriksaan Berkas Administrasi Formulir Andal RKL RPL"
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
        <el-tab-pane
          v-if="isSubtance && isComplete"
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
import Verifikasi from '@/views/pengujian-rkl-rpl/components/verifikasi/index';
import UndanganRapat from '@/views/pengujian-rkl-rpl/components/undanganRapat/index';
import BeritaAcara from '@/views/pengujian-rkl-rpl/components/beritaAcara/index';
import WorkFlow from '@/components/Workflow';
import Resource from '@/api/resource';
const verifikasiRapatResource = new Resource('test-verif-rkl-rpl');

export default {
  name: 'PengujianRKLRPL',
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
    isExaminer() {
      return this.userInfo.roles.includes('examiner');
    },
    isFormulator() {
      return this.userInfo.roles.includes('formulator');
    },
  },
  async created() {
    this.userInfo = await this.$store.dispatch('user/getInfo');
    this.isComplete = await verifikasiRapatResource.list({
      checkComplete: 'true',
      idProject: this.$route.params.id,
    });
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
  methods: {
    changeIsComplete({ isComplete }) {
      this.isComplete = isComplete;
    },
  },
};
</script>
