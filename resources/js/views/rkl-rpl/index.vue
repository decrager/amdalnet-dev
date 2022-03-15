<template>
  <div class="app-container">
    <el-card>
      <WorkFlow />
      <el-tabs v-model="activeName" type="card">
        <el-tab-pane label="Matriks RKL RPL" name="matriks">
          <Matriks v-if="activeName === 'matriks'" />
        </el-tab-pane>
        <!-- <el-tab-pane label="Dokumen RKL RPL" name="dokumen">
          <el-row v-if="activeName === 'dokumen'" :gutter="32"> -->
        <!-- <el-col :sm="12" :md="7">
              <MapList />
            </el-col> -->
        <!-- <el-col :sm="24" :md="15">
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
        </el-tab-pane> -->
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Matriks from '@/views/rkl-rpl/components/Matriks';
// import MapList from '@/views/rkl-rpl/components/MapList';
// import DocsFrame from '@/views/rkl-rpl/components/DocsFrame';
// import Comment from '@/views/rkl-rpl/components/Comment';
import WorkFlow from '@/components/Workflow';

export default {
  name: 'MatriksRKLRPL',
  components: {
    Matriks,
    // MapList,
    // DocsFrame,
    // Comment,
    WorkFlow,
  },
  data() {
    return {
      idProject: this.$route.params.id,
      userInfo: {
        roles: [],
      },
      activeName: 'matriks',
    };
  },
  computed: {
    ...mapGetters({
      'userInfo': 'user',
      'userId': 'userId',
    }),
  },
  created() {
    // this.getUserInfo();
    this.$store.dispatch('getStep', 5);
  },
  methods: {
    // async getUserInfo() {
    //   this.userInfo = await this.$store.dispatch('user/getInfo');
    // },
  },
};
</script>
