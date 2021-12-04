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
              @click="handleViewComponents(scope.row.id)"
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
              @click="handleViewComponents(scope.row.id)"
            />
          </template>
        </el-table-column>
      </el-table>
    </el-col>
    <el-col :span="18" :xs="24">
      <table :key="tableKey" class="title" style="border-collapse: collapse; width:100%;">
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
                <el-row>
                  <el-button
                    type="default"
                    size="medium"
                    class="button-medium"
                  >
                    <el-button
                      v-if="!isAndal"
                      type="danger"
                      size="mini"
                      icon="el-icon-close"
                      style="margin-left: 0px; margin-right: 10px;"
                      class="button-action-mini"
                      @click="handleDeleteComponent(comp.id)"
                    />
                    <span>{{ comp.name }}</span>
                    <el-button
                      type="success"
                      size="mini"
                      class="pull-right button-action-mini"
                      icon="el-icon-caret-right"
                      @click="handleViewRonaAwals(comp.id)"
                    />
                  </el-button>
                </el-row>
                <el-input v-model="comp.description_specific" size="mini" placeholder="Deskr. khusus" style="clear:both; display:block;margin-top:.5em;width:100%;" :disabled="true" />
              </div>

              <el-button v-if="!isAndal" icon="el-icon-plus" circle style="margin-top:3em;display:block;" round @click="handleAddComponent()" />
            </td>
            <td>
              <div v-for="ra in subProjectRonaAwals[0].rona_awals" :key="ra.id" style="margin:.5em 0;">
                <el-tag key="ra.id" type="info" :closable="closable && !isAndal" @close="handleDeleteRonaAwal(ra.id)">{{ ra.name }}</el-tag>
                <el-input v-model="ra.description_specific" size="mini" placeholder="Definisi" style="clear:both; display:block;margin-top:.5em;width:10em;" :disabled="true" />
              </div>

              <el-button v-if="!isAndal" icon="el-icon-plus" circle style="margin-top:3em;display:block;" round @click="handleAddRonaAwal(1)" />
            </td>
            <td>
              <div v-for="ra in subProjectRonaAwals[1].rona_awals" :key="ra.id" style="margin:.5em 0;">
                <el-tag :key="ra.id" type="info" :closable="closable && !isAndal" @close="handleDeleteRonaAwal(ra.id)">{{ ra.name }}</el-tag>
                <el-input v-model="ra.description_specific" size="mini" placeholder="Definisi" style="clear:both; display:block;margin-top:.5em;width:10em;" :disabled="true" />
              </div>
              <el-button v-if="!isAndal" icon="el-icon-plus" circle style="margin-top:3em;display:block;" round @click="handleAddRonaAwal(2)" />
            </td>
            <td>
              <div v-for="ra in subProjectRonaAwals[2].rona_awals" :key="ra.id" style="margin:.5em 0;">
                <el-tag :key="ra.id" type="info" :closable="closable && !isAndal" @close="handleDeleteRonaAwal(ra.id)">{{ ra.name }}</el-tag>
                <el-input v-model="ra.description_specific" size="mini" placeholder="Definisi" style="clear:both; display:block;margin-top:.5em;width:10em;" :disabled="true" />
              </div>
              <el-button v-if="!isAndal" icon="el-icon-plus" circle style="margin-top:3em;display:block;" round @click="handleAddRonaAwal(3)" />
            </td>
            <td>
              <div v-for="ra in subProjectRonaAwals[3].rona_awals" :key="ra.id" style="margin:.5em 0;">
                <el-tag :key="ra.id" type="info" :closable="closable && !isAndal" @close="handleDeleteRonaAwal(ra.id)">{{ ra.name }}</el-tag>
                <el-input v-model="ra.description_specific" size="mini" placeholder="Definisi" style="clear:both; display:block;margin-top:.5em;width:10em;" :disabled="true" />
              </div>
              <el-button v-if="!isAndal" icon="el-icon-plus" circle style="margin-top:3em;display:block;" round @click="handleAddRonaAwal(4)" />
            </td>
            <td>
              <div v-for="ra in subProjectRonaAwals[4].rona_awals" :key="ra.id" style="margin:.5em 0;">
                <el-tag :key="ra.id" type="info" :closable="closable && !isAndal" @close="handleDeleteRonaAwal(ra.id)">{{ ra.name }}</el-tag>
                <el-input v-model="ra.description_specific" size="mini" placeholder="Definisi" style="clear:both; display:block;margin-top:.5em;width:10em;" :disabled="true" />
              </div>
              <el-button v-if="!isAndal" icon="el-icon-plus" circle style="margin-top:3em;display:block;" round @click="handleAddRonaAwal(5)" />
            </td>
            <td>
              <div v-for="ra in subProjectRonaAwals[5].rona_awals" :key="ra.id" style="margin:.5em 0;">
                <el-tag :key="ra.id" type="info" :closable="closable && !isAndal" @close="handleDeleteRonaAwal(ra.id)">{{ ra.name }}</el-tag>
                <el-input v-model="ra.description_specific" size="mini" placeholder="Definisi" style="clear:both; display:block;margin-top:.5em;width:10em;" :disabled="true" />
              </div>
              <el-button v-if="!isAndal" icon="el-icon-plus" circle style="margin-top:3em;display:block;" round @click="handleAddRonaAwal(6)" />
            </td>
          </tr>
        </tbody>
      </table>
    </el-col>
    <add-component-dialog
      v-if="!isAndal"
      :key="componentDialogKey"
      :show="kKDialogueVisible"
      :sub-projects="subProjects"
      :id-project-stage="idProjectStage"
      :current-id-sub-project="currentIdSubProject"
      @handleCloseAddComponent="handleCloseAddComponent"
      @handleSetCurrentIdSubProjectComponent="handleSetCurrentIdSubProjectComponent"
    />
    <add-rona-awal-dialog
      v-if="!isAndal"
      :key="ronaAwalDialogKey"
      :show="kLDialogueVisible"
      :sub-projects="subProjects"
      :id-project="idProject"
      :id-project-stage="idProjectStage"
      :current-id-sub-project="currentIdSubProject"
      :current-id-sub-project-component="currentIdSubProjectComponent"
      :current-id-component-type="currentIdComponentType"
      :sub-project-components="subProjectComponents"
      @handleCloseAddRonaAwal="handleCloseAddRonaAwal"
    />
  </el-row>
</template>

<script>
import Resource from '@/api/resource';
import AddComponentDialog from '../dialogs/AddComponentDialog.vue';
import AddRonaAwalDialog from '../dialogs/AddRonaAwalDialog.vue';
const scopingResource = new Resource('scoping');
const subProjectComponentResource = new Resource('sub-project-components');
const subProjectRonaAwalResource = new Resource('sub-project-rona-awals');

export default {
  name: 'PelingkupanTable',
  components: { AddComponentDialog, AddRonaAwalDialog },
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
      tableKey: 0,
      componentDialogKey: 1,
      ronaAwalDialogKey: 1,
      kKDialogueVisible: false,
      kLDialogueVisible: false,
      closable: true,
      subProjects: {
        utama: [],
        pendukung: [],
      },
      subProjectComponents: [],
      subProjectRonaAwals: [
        { rona_awals: [] },
        { rona_awals: [] },
        { rona_awals: [] },
        { rona_awals: [] },
        { rona_awals: [] },
        { rona_awals: [] },
      ],
      currentIdSubProject: 0,
      currentIdSubProjectComponent: 0,
      currentIdComponentType: 0,
    };
  },
  computed: {
    isAndal() {
      return this.$route.name === 'penyusunanAndal';
    },
  },
  mounted() {
    this.getData();
  },
  methods: {
    reloadData() {
      this.getComponents(this.currentIdSubProject);
      this.getRonaAwals(this.currentIdSubProjectComponent);
      // reload dialogs
      this.componentDialogKey = this.componentDialogKey + 1;
      this.ronaAwalDialogKey = this.ronaAwalDialogKey + 1;
    },
    handleAddComponent() {
      this.kKDialogueVisible = true;
    },
    handleAddRonaAwal(idComponentType) {
      this.currentIdComponentType = idComponentType;
      this.kLDialogueVisible = true;
    },
    handleCloseAddComponent(reload) {
      this.kKDialogueVisible = false;
      // reload table
      if (reload) {
        this.reloadData();
      }
    },
    handleSetCurrentIdSubProjectComponent(idSubProjectComponent) {
      this.currentIdSubProjectComponent = idSubProjectComponent;
      this.ronaAwalDialogKey = this.ronaAwalDialogKey + 1;
    },
    handleCloseAddRonaAwal(reload) {
      this.kLDialogueVisible = false;
      if (reload) {
        this.reloadData();
      }
    },
    handleDeleteComponent(id) {
      subProjectComponentResource
        .destroy(id)
        .then((response) => {
          this.$message({
            message: 'Komponen Kegiatan berhasil dihapus',
            type: 'success',
            duration: 5 * 1000,
          });
          // reload PelingkupanTable
          this.kKDialogueVisible = false;
          this.reloadData();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    handleDeleteRonaAwal(id) {
      subProjectRonaAwalResource
        .destroy(id)
        .then((response) => {
          this.$message({
            message: 'Komponen Lingkungan berhasil dihapus',
            type: 'success',
            duration: 5 * 1000,
          });
          // reload PelingkupanTable
          this.kLDialogueVisible = false;
          this.reloadData();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async handleViewComponents(idSubProject) {
      this.currentIdSubProject = idSubProject;
      this.getComponents(idSubProject);
      this.subProjectComponents = [];
      const sp = await subProjectComponentResource.list({
        id_sub_project: idSubProject,
        id_project_stage: this.idProjectStage,
      });
      this.subProjectRonaAwals = [
        { rona_awals: [] },
        { rona_awals: [] },
        { rona_awals: [] },
        { rona_awals: [] },
        { rona_awals: [] },
        { rona_awals: [] },
      ];
      if (sp.data.length > 0){
        this.getRonaAwals(sp.data[0].id);
      }
      // reload table
      this.tableKey = this.tableKey + 1;
      // reload dialogs
      this.componentDialogKey = this.componentDialogKey + 1;
      this.ronaAwalDialogKey = this.ronaAwalDialogKey + 1;
    },
    async handleViewRonaAwals(idSubProjectComponent) {
      this.currentIdSubProjectComponent = idSubProjectComponent;
      this.getRonaAwals(idSubProjectComponent);
      // reload dialogs
      this.componentDialogKey = this.componentDialogKey + 1;
      this.ronaAwalDialogKey = this.ronaAwalDialogKey + 1;
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
      // init rona awals array
      for (let i = 0; i < 6; i++){
        this.subProjectRonaAwals.push({
          rona_awals: [],
        });
      }
      // get first sub project's components & rona_awals
      if (this.subProjects.utama.length > 0) {
        const firstSubProject = this.subProjects.utama[0];
        this.getComponents(firstSubProject.id);
        const sp = await subProjectComponentResource.list({
          id_sub_project: firstSubProject.id,
          id_project_stage: this.idProjectStage,
        });
        if (sp.data.length > 0){
          this.getRonaAwals(sp.data[0].id);
        }
        this.currentIdSubProject = firstSubProject.id;
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
      if (this.subProjectComponents.length > 0) {
        this.currentIdSubProjectComponent = this.subProjectComponents[0].id;
      }
    },
    async getRonaAwals(idSubProjectComponent) {
      const ronaAwals = await scopingResource.list({
        sub_project_rona_awals: true,
        id_sub_project_component: idSubProjectComponent,
      });
      ronaAwals.data.map((ra) => {
        ra.rona_awals.map((r) => {
          if (r.name === null) {
            r.name = r.rona_awal.name;
          }
        });
      });
      this.subProjectRonaAwals = ronaAwals.data;
    },
  },
};
</script>

<style scoped>
table.el-table__header {
  background-color: #6cc26f !important;
}

table th, table td {word-break: normal !important; padding:.5em; line-height:1.2em; border: 1px solid #eee;}
table td { vertical-align: top !important;}
table thead  {background-color:#6cc26f !important; color: white !important;}
table td.title, table tr.title td, table.title td { text-align:left;}

.button-action-mini {
  margin-left: 20px;
  padding: 1px 1px;
}

.button-medium {
  padding: 5px 5px 5px 5px;
}

</style>
