<template>
  <div class="app-container">
    <el-card>
      <WorkflowUkl />
      <el-tabs v-model="activeName" type="card">
        <el-tab-pane
          label="Pemeriksaan Berkas Administrasi"
          name="verifikasi"
        >
          <Verifikasi
            v-if="activeName === 'verifikasi'"
            @changeIsComplete="changeIsComplete($event)"
          />
        </el-tab-pane>
        <el-tab-pane
          v-if="isComplete"
          label="Undangan Rapat"
          name="undanganrapat"
        >
          <UndanganRapat v-if="activeName === 'undanganrapat'" />
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Verifikasi from '@/views/pengujian-ukl-upl/components/verifikasi/index';
import UndanganRapat from '@/views/pengujian-ukl-upl/components/undanganRapat/index';
import WorkflowUkl from '@/components/WorkflowUkl';
import Resource from '@/api/resource';
const verifikasiRapatResource = new Resource('test-verif-rkl-rpl');

export default {
  name: 'PengujianUKLUPL',
  components: {
    Verifikasi,
    UndanganRapat,
    WorkflowUkl,
  },
  data() {
    return {
      activeName: 'verifikasi',
      // userInfo: {
      //   roles: [],
      // },
      isComplete: false,
    };
  },
  computed: {
    ...mapGetters({
      'userInfo': 'user',
      'userId': 'userId',
    }),
  },
  async created() {
    // this.userInfo = await this.$store.dispatch('user/getInfo');
    this.isComplete = await verifikasiRapatResource.list({
      checkComplete: 'true',
      idProject: this.$route.params.id,
      uklUpl: 'true',
    });
    this.$store.dispatch('getStep', 5);
  },
  methods: {
    changeIsComplete({ isComplete }) {
      this.isComplete = isComplete;
    },
  },
};
</script>
