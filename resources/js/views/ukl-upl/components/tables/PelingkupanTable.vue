<template>
  <el-row :gutter="4">
    <el-col :span="6" :xs="24">
      <el-table
        :data="subProjects.utama"
        fit
        highlight-current-row
        :header-cell-style="{ background: '#def5cf' }"
        style="width: 100%"
      >
        <el-table-column label="No." width="54px">
          <template slot-scope="scope">
            {{ scope.$index + 1 }}
          </template>
        </el-table-column>
        <el-table-column label="Kegiatan Utama">
          <template slot-scope="scope">
            <span>{{ scope.row.name }}</span>
            <el-button
              type="success"
              size="mini"
              class="pull-right"
              icon=""
              @click="handleViewComponentRonaAwals(scope.row.id)"
            />
          </template>
        </el-table-column>
      </el-table>
      <el-table
        :data="subProjects.pendukung"
        fit
        highlight-current-row
        :header-cell-style="{ background: '#def5cf' }"
        style="width: 100%"
      >
        <el-table-column label="No." width="54px">
          <template slot-scope="scope">
            {{ scope.$index + 1 }}
          </template>
        </el-table-column>
        <el-table-column label="Kegiatan Pendukung">
          <template slot-scope="scope">
            <span>{{ scope.row.name }}</span>
            <el-button
              type="success"
              size="mini"
              class="pull-right"
              icon=""
              @click="handleViewComponentRonaAwals(scope.row.id)"
            />
          </template>
        </el-table-column>
      </el-table>
    </el-col>
    <el-col :span="18" :xs="24" />
  </el-row>
</template>

<script>
import Resource from '@/api/resource';
// const componentTypeResource = new Resource('component-types');
const scopingResource = new Resource('scoping');

export default {
  name: 'PelingkupanTable',
  props: {
    idProject: {
      type: Number,
      default: () => 0,
    },
    idProjectStage: {
      type: Number,
      default: () => 0,
    },
  },
  data() {
    return {
      subProjects: {
        utama: [],
        pendukung: [],
      },
      subProjectComponents: [],
      // componentTypes: [],
      subProjectRonaAwals: [],
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    handleViewComponentRonaAwals(idSubProject) {
      this.getComponents(idSubProject);
      this.getRonaAwals(idSubProject);
    },
    async getData() {
      const subProjectUtama = await scopingResource.list({
        id_project: this.idProject,
        sub_project_type: 'utama',
      });
      this.subProjects.utama = subProjectUtama.data;

      const subProjectPendukung = await scopingResource.list({
        id_project: this.idProject,
        sub_project_type: 'pendukung',
      });
      this.subProjects.pendukung = subProjectPendukung.data;
      // get first sub project's components & rona_awals
      if (this.subProjects.utama.length > 0) {
        const firstSubProject = this.subProjects.utama[0];
        this.getComponents(firstSubProject.id);
        this.getRonaAwals(firstSubProject.id);
      }
    },
    async getComponents(idSubProject) {
      const components = await scopingResource.list({
        id_sub_project: idSubProject,
        id_project_stage: this.idProjectStage,
        sub_project_components: true,
      });
      this.subProjectComponents = components.data;
      console.log(this.subProjectComponents);
    },
    async getRonaAwals(idSubProject) {
      const ronaAwals = await scopingResource.list({
        id_sub_project: idSubProject,
        id_project_stage: this.idProjectStage,
        sub_project_rona_awals: true,
      });
      this.subProjectRonaAwals = ronaAwals.data;
      console.log(this.subProjectRonaAwals);
    },
  },
};
</script>
