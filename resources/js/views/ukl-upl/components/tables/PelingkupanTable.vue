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
              icon="el-icon-caret-right"
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
              icon="el-icon-caret-right"
              @click="handleViewComponentRonaAwals(scope.row.id)"
            />
          </template>
        </el-table-column>
      </el-table>
    </el-col>
    <el-col :span="18" :xs="24">
      <table class="title" style="border-collapse: collapse; width:100%;">
        <thead>
          <tr>
            <th rowspan="2">Komponen Kegiatan</th>
            <th colspan="6">Komponen Lingkungan</th>
          </tr>
          <tr>
            <th>Geofisika Kimia</th>
            <th>Biologi</th>
            <th>Sosial Budaya</th>
            <th>Kesehatan Masyarakat</th>
            <th>Kegiatan Lain Sekitar</th>
            <th>Lainnya</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <div v-for="comp in subProjectComponents" :key="comp.id" style="margin:.5em 0;">
                <el-tag :closable="closable" type="info">{{ comp.name }}</el-tag>
                <el-input v-model="comp.description_specific" size="mini" placeholder="Definisi" style="clear:both; display:block;margin-top:.5em;width:10em;" />
              </div>

              <el-button icon="el-icon-plus" circle style="margin-top:3em;display:block;" @click="kKDialogueVisible = true" />
            </td>
            <td>
              <div v-for="ra in subProjectRonaAwals[0].rona_awals" :key="ra.id" style="margin:.5em 0;">
                <el-tag :closable="closable" type="info">{{ ra.name }}</el-tag>
                <el-input v-model="ra.description_specific" size="mini" placeholder="Definisi" style="clear:both; display:block;margin-top:.5em;width:10em;" />
              </div>

              <el-button icon="el-icon-plus" circle style="margin-top:3em;display:block;" @click="kLDialogueVisible = true" />
            </td>
            <td>
              <div v-for="ra in subProjectRonaAwals[1].rona_awals" :key="ra.id" style="margin:.5em 0;">
                <el-tag :closable="closable" type="info">{{ ra.name }}</el-tag>
                <el-input v-model="ra.description_specific" size="mini" placeholder="Definisi" style="clear:both; display:block;margin-top:.5em;width:10em;" />
              </div>
              <el-button icon="el-icon-plus" circle style="margin-top:3em;display:block;" @click="kLDialogueVisible = true" />
            </td>
            <td>
              <div v-for="ra in subProjectRonaAwals[2].rona_awals" :key="ra.id" style="margin:.5em 0;">
                <el-tag :closable="closable" type="info">{{ ra.name }}</el-tag>
                <el-input v-model="ra.description_specific" size="mini" placeholder="Definisi" style="clear:both; display:block;margin-top:.5em;width:10em;" />
              </div>
              <el-button icon="el-icon-plus" circle style="margin-top:3em;display:block;" @click="kLDialogueVisible = true" />
            </td>
            <td>
              <div v-for="ra in subProjectRonaAwals[3].rona_awals" :key="ra.id" style="margin:.5em 0;">
                <el-tag :closable="closable" type="info">{{ ra.name }}</el-tag>
                <el-input v-model="ra.description_specific" size="mini" placeholder="Definisi" style="clear:both; display:block;margin-top:.5em;width:10em;" />
              </div>
              <el-button icon="el-icon-plus" circle style="margin-top:3em;display:block;" @click="kLDialogueVisible = true" />
            </td>
            <td>
              <div v-for="ra in subProjectRonaAwals[4].rona_awals" :key="ra.id" style="margin:.5em 0;">
                <el-tag :closable="closable" type="info">{{ ra.name }}</el-tag>
                <el-input v-model="ra.description_specific" size="mini" placeholder="Definisi" style="clear:both; display:block;margin-top:.5em;width:10em;" />
              </div>
              <el-button icon="el-icon-plus" circle style="margin-top:3em;display:block;" @click="kLDialogueVisible = true" />
            </td>
            <td>
              <div v-for="ra in subProjectRonaAwals[5].rona_awals" :key="ra.id" style="margin:.5em 0;">
                <el-tag :closable="closable" type="info">{{ ra.name }}</el-tag>
                <el-input v-model="ra.description_specific" size="mini" placeholder="Definisi" style="clear:both; display:block;margin-top:.5em;width:10em;" />
              </div>
              <el-button icon="el-icon-plus" circle style="margin-top:3em;display:block;" @click="kLDialogueVisible = true" />
            </td>
          </tr>
        </tbody>
      </table>
    </el-col>
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
      closable: true,
      subProjects: {
        utama: [],
        pendukung: [],
      },
      subProjectComponents: [],
      // componentTypes: [],
      subProjectRonaAwals: [],
      kkTags: [{ id: 1, name: '1.1 Pengadaan Lahan' }, { id: 2, name: '1.2 Land Clearing' }],
      gFTags: [{ id: 1, name: 'Sumber Daya Geologi' }, { id: 2, name: 'Air Permukaan' }, { id: 3, name: 'Udara' }],
      bioTags: [{ id: 1, name: 'Vegetasi Flora' }, { id: 2, name: 'Tipe Ekosistem' }],
      sosBudTags: [{ id: 1, name: 'Tingkat Pendapatan' }, { id: 2, name: 'Mata Pencaharian' }, { id: 3, name: 'Situs Arkeologi' }],
      kesMasTags: [{ name: 'Kesehatan Masyarakat' }],
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
      components.data.map((comp) => {
        if (comp.name === null) {
          comp.name = comp.component.name;
        }
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
      ronaAwals.data.map((ra) => {
        ra.rona_awals.map((r) => {
          if (r.name === null) {
            r.name = r.rona_awal.name;
          }
        });
      });
      this.subProjectRonaAwals = ronaAwals.data;
      console.log(this.subProjectRonaAwals);
    },
  },
};
</script>

<style scoped>
table.el-table__header {
  background-color: #6cc26f !important;
}

table th, table td {word-break: normal !important; padding:.5em; line-height:1.2em; border: 1px solid #eee; text-align:center;}
table td { vertical-align: top !important;}
table thead  {background-color:#6cc26f !important; color: white !important;}
table td.title, table tr.title td, table.title td { text-align:left;}
</style>
