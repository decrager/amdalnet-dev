<template>
  <div class="app-container">
    <el-card>
      <div class="filter-container">
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
      </div>
      <el-tabs type="card">
        <el-tab-pane label="Matriks RKL RPL">
          <Matriks
            :matriksrkl="matriksRKL"
            :lasttimerkl="lastTimeRKL"
            :loadingrkl="loadingRKL"
            @handleSubmitRKL="handleSubmitRKL"
          />
        </el-tab-pane>
        <el-tab-pane label="Dokumen RKL RPL">
          <el-row :gutter="32">
            <el-col :sm="12" :md="7">
              <MapList />
            </el-col>
            <el-col :sm="12" :md="11">
              <DocsFrame />
            </el-col>
            <el-col :sm="12" :md="6">
              <div>
                <el-button type="primary" style="font-size: 0.8rem">
                  {{ 'Simpan Perubahan' }}
                </el-button>
                <el-button type="warning" style="font-size: 0.8rem">
                  {{ 'Kirim' }}
                </el-button>
              </div>
              <small>
                <em>Terakhir diperbarui beberapa detik yang lalu</em>
              </small>
            </el-col>
          </el-row>
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const rklResource = new Resource('matriks-rkl');
import Matriks from '@/views/rkl-rpl/components/Matriks';
import MapList from '@/views/rkl-rpl/components/MapList';
import DocsFrame from '@/views/rkl-rpl/components/DocsFrame';

export default {
  name: 'MatriksRKLRPL',
  components: {
    Matriks,
    MapList,
    DocsFrame,
  },
  data() {
    return {
      loadingRKL: false,
      loadingRPL: false,
      projects: [],
      idProject: null,
      lastTimeRKL: null,
      matriksRKL: [],
    };
  },
  created() {
    this.getProjects();
  },
  methods: {
    async getProjects() {
      const data = await rklResource.list({ project: 'true' });
      this.projects = data;
    },
    async getRKL() {
      this.matriksRKL = await rklResource.list({
        idProject: this.idProject,
      });
    },
    async getLastTimeRKL(idProject) {
      this.lastTimeRKL = await rklResource.list({
        lastTime: 'true',
        idProject,
      });
    },
    async handleSubmitRKL() {
      if (!this.idProject) {
        return;
      }

      const sendForm = this.matriksRKL.filter((com) => com.type !== 'title');
      const time = await rklResource.store({
        manage: sendForm,
        type: this.lastTimeRKL ? 'update' : 'new',
      });
      this.lastTimeRKL = time;
      this.getRKL();
      this.$message({
        message: 'Data is saved Successfully',
        type: 'success',
        duration: 5 * 1000,
      });
    },
    async handleChange(val) {
      this.loadingRKL = true;
      this.loadingRPL = true;
      this.idProject = val;
      this.getRKL();
      this.loadingRKL = false;
      this.loadingRPL = false;
      this.getLastTimeRKL(val);
    },
  },
};
</script>
