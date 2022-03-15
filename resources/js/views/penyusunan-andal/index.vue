<template>
  <div class="app-container">
    <el-card>
      <WorkFlow />
      <!-- <div class="filter-container">
        <el-row :gutter="32">
          <el-col :sm="24" :md="10">
            <el-select
              v-model="idProject"
              placeholder="Pilih Kegiatan"
              style="width: 100%"
              @change="handleChange"
            >
              <el-option
                v-for="item in projects"
                :key="item.id"
                :label="item.project_title"
                :value="item.id"
              />
            </el-select>
          </el-col>
        </el-row>
      </div> -->
      <el-tabs v-model="activeName" v-loading="loading" type="card">
        <el-tab-pane label="Formulir Kerangka Acuan" name="formulir-ka">
          <FormulirKA v-if="activeName === 'formulir-ka' && !loading" />
        </el-tab-pane>
        <el-tab-pane label="Analisa Dampak Lingkungan" name="andal">
          <Andal v-if="activeName === 'andal' && !loading" />
        </el-tab-pane>
        <!-- <el-tab-pane label="Dokumen ANDAL" name="dokumen">
          <el-row v-if="activeName === 'dokumen'" :gutter="32"> -->
        <!-- <el-col :sm="12" :md="7">
              <MapList />
            </el-col> -->
        <!-- <el-col :sm="24" :md="15">
              <DocsFrame />
            </el-col>
            <el-col :sm="24" :md="9">
              <el-button
                v-if="userInfo.roles.includes('initiator')"
                type="primary"
                style="font-size: 0.8rem; display: block"
              >
                {{ 'Simpan Perubahan' }}
              </el-button>
              <small v-if="userInfo.roles.includes('initiator')">
                <em>{{ lastTime }}</em>
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
import Resource from '@/api/resource';
const cloneResource = new Resource('andal-clone');
import Andal from '@/views/penyusunan-andal/components/Andal';
import FormulirKA from '@/views/penyusunan-andal/components/FormulirKA';
// import MapList from '@/views/penyusunan-andal/components/MapList';
// import DocsFrame from '@/views/penyusunan-andal/components/DocsFrame';
// import Comment from '@/views/penyusunan-andal/components/Comment';
import WorkFlow from '@/components/Workflow';

export default {
  name: 'PenyusunanAndal',
  components: {
    Andal,
    // MapList,
    // DocsFrame,
    // Comment,
    WorkFlow,
    FormulirKA,
  },
  data() {
    return {
      idProject: this.$route.params.id,
      compose: [],
      lastTime: null,
      // userInfo: {
      //   roles: [],
      // },
      activeName: 'formulir-ka',
      loading: false,
    };
  },
  computed: {
    ...mapGetters({
      'userInfo': 'user',
      'userId': 'userId',
    }),
  },
  created() {
    this.checkExist();
    // this.getUserInfo();
    this.$store.dispatch('getStep', 4);
  },
  methods: {
    async checkExist() {
      this.loading = true;
      await cloneResource.list({
        exist: 'true',
        idProject: this.$route.params.id,
      });
      this.loading = false;
    },
    // async getUserInfo() {
    //   this.userInfo = await this.$store.dispatch('user/getInfo');
    // },
    handleSubmitTanggapan() {
      this.handleSubmit();
    },
  },
};
</script>
