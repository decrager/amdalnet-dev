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
        <el-tab-pane label="Analisa Dampak Lingkungan">
          <div class="filter-container">
            <el-button
              class="filter-item"
              type="primary"
              style="font-size: 0.8rem"
              @click="handleSubmit"
            >
              {{ 'Simpan Perubahan' }}
            </el-button>
            <span style="font-size: 0.8rem">
              <em>{{ lastTime }}</em>
            </span>
          </div>
          <PenyusunanAndalTable
            :loading="loading"
            :list="compose"
            @handleSubmitTanggapan="handleSubmitTanggapan"
          />
        </el-tab-pane>
        <el-tab-pane label="Dokumen ANDAL">
          <el-row :gutter="32">
            <el-col :sm="12" :md="7">
              <MapList />
            </el-col>
            <el-col :sm="12" :md="12">
              <DocsFrame />
            </el-col>
            <el-col :sm="12" :md="5">
              <el-button
                type="primary"
                style="font-size: 0.8rem; display: block"
              >
                {{ 'Simpan Perubahan' }}
              </el-button>
              <small>
                <em>{{ lastTime }}</em>
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
const andalComposingResource = new Resource('andal-composing');
import PenyusunanAndalTable from '@/views/penyusunan-andal/components/Table';
import MapList from '@/views/penyusunan-andal/components/MapList';
import DocsFrame from '@/views/penyusunan-andal/components/DocsFrame';

export default {
  name: 'PenyusunanAndal',
  components: {
    PenyusunanAndalTable,
    MapList,
    DocsFrame,
  },
  data() {
    return {
      loading: false,
      projects: [],
      idProject: null,
      compose: [],
      lastTime: null,
    };
  },
  created() {
    this.getProjects();
  },
  methods: {
    async getProjects() {
      const data = await andalComposingResource.list({ project: 'true' });
      this.projects = data;
    },
    async handleChange(val) {
      this.loading = true;
      this.idProject = val;
      await this.getCompose();
      await this.getLastTime(val);
      this.loading = false;
    },
    async getLastTime(idProject) {
      this.lastTime = await andalComposingResource.list({
        lastTime: 'true',
        idProject,
      });
    },
    async getCompose() {
      this.compose = await andalComposingResource.list({
        idProject: this.idProject,
      });
    },
    async handleSubmit() {
      if (!this.idProject) {
        return;
      }

      const sendForm = this.compose.filter((com) => com.type !== 'title');
      const time = await andalComposingResource.store({
        analysis: sendForm,
        type: this.lastTime ? 'update' : 'new',
      });
      this.lastTime = time;
      await this.getCompose();
      this.$message({
        message: 'Data is saved Successfully',
        type: 'success',
        duration: 5 * 1000,
      });
    },
    handleSubmitTanggapan() {
      this.handleSubmit();
    },
  },
};
</script>
