<template>
  <div class="app-container">
    <el-card>
      <WorkflowUkl />
      <el-tabs v-model="activeName" type="card">
        <el-tab-pane
          v-if="isAdmin"
          label="Pemeriksaan Berkas Administrasi"
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
          v-else-if="isSubtance && isComplete"
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
import Verifikasi from '@/views/pengujian-ukl-upl/components/verifikasi/index';
import UndanganRapat from '@/views/pengujian-ukl-upl/components/undanganRapat/index';
import BeritaAcara from '@/views/pengujian-ukl-upl/components/beritaAcara/index';
import UjiKelayakan from '@/views/pengujian-rkl-rpl/components/ujiKelayakan/index';
import WorkflowUkl from '@/components/WorkflowUkl';
import Resource from '@/api/resource';
const verifikasiRapatResource = new Resource('test-verif-rkl-rpl');

export default {
  name: 'PengujianUKLUPL',
  components: {
    Verifikasi,
    UndanganRapat,
    BeritaAcara,
    UjiKelayakan,
    WorkflowUkl,
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
      uklUpl: 'true',
    });
    this.$store.dispatch('getStep', 5);
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
