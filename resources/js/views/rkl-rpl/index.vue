<template>
  <div class="app-container">
    <el-card>
      <WorkFlow />
      <el-tabs type="card">
        <el-tab-pane label="Matriks RKL RPL">
          <Matriks
            :institutions="institutions"
            :matriksrpl="matriksRPL"
            :lasttimerpl="lastTimeRPL"
            :loadingrpl="loadingRPL"
            @handleSubmitRPL="handleSubmitRPL"
          />
        </el-tab-pane>
        <el-tab-pane label="Dokumen RKL RPL">
          <el-row :gutter="32">
            <!-- <el-col :sm="12" :md="7">
              <MapList />
            </el-col> -->
            <el-col :sm="24" :md="15">
              <DocsFrame />
            </el-col>
            <el-col :sm="24" :md="9">
              <div v-if="userInfo.roles.includes('initiator')">
                <el-button type="primary" style="font-size: 0.8rem">
                  {{ 'Simpan Perubahan' }}
                </el-button>
                <el-button type="warning" style="font-size: 0.8rem">
                  {{ 'Kirim' }}
                </el-button>
              </div>
              <small v-if="userInfo.roles.includes('initiator')">
                <em>Terakhir diperbarui beberapa detik yang lalu</em>
              </small>
              <Comment />
            </el-col>
          </el-row>
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const rplResource = new Resource('matriks-rpl');
import Matriks from '@/views/rkl-rpl/components/Matriks';
// import MapList from '@/views/rkl-rpl/components/MapList';
import DocsFrame from '@/views/rkl-rpl/components/DocsFrame';
import Comment from '@/views/rkl-rpl/components/Comment';
import WorkFlow from '@/components/Workflow';

export default {
  name: 'MatriksRKLRPL',
  components: {
    Matriks,
    // MapList,
    DocsFrame,
    Comment,
    WorkFlow,
  },
  data() {
    return {
      loadingRPL: false,
      idProject: this.$route.params.id,
      institutions: [],
      matriksRPL: [],
      lastTimeRPL: null,
      userInfo: {
        roles: [],
      },
    };
  },
  created() {
    this.getInstitutions();
    this.getUserInfo();
    this.$store.dispatch('getStep', 5);
  },
  methods: {
    async getInstitutions() {
      this.institutions = await rplResource.list({ institution: 'true' });
    },

    async getRPL() {
      this.matriksRPL = await rplResource.list({
        idProject: this.idProject,
      });
    },

    async getLastimeRPL(idProject) {
      this.lastTimeRPL = await rplResource.list({
        lastTime: 'true',
        idProject,
      });
    },
    async handleSubmitRPL() {
      if (!this.idProject) {
        return;
      }

      const sendForm = this.matriksRPL.filter((com) => com.type !== 'title');
      const time = await rplResource.store({
        monitor: sendForm,
        type: this.lastTimeRPL ? 'update' : 'new',
      });
      this.lastTimeRPL = time;
      this.getRPL();
      this.$message({
        message: 'Data is saved Successfully',
        type: 'success',
        duration: 5 * 1000,
      });
    },
    async getUserInfo() {
      this.userInfo = await this.$store.dispatch('user/getInfo');
    },
  },
};
</script>
