<template>
  <div class="scoupingModule" style="margin-top:2em;">

    <el-tabs v-if="projectStages.length > 0" v-model="activeName" @tab-click="handleTabClick">
      <el-tab-pane
        v-for="(stage, index) in projectStages"
        :key="'stage'+ index +'_tab'"
        :label="stage.name"
        :name="'stage'+stage.id"
      >
        <el-row
          :gutter="1"
          style="margin-top: 1em;"
        >
          <el-col :span="4">
            <el-card v-if="subProjects.length > 0" shadow="never">
              <div slot="header" class="clearfix card-header" style="text-align:center; font-weight:bold;">
                <span>Kegiatan Utama</span>
              </div>
              <components-list
                v-if="subProjects.length > 0"
                :id="'main_'+stage.id"
                :components="subProjects.filter(s => s.type === 'utama')"
                :active-component="activeScoping.sub_projects"
                :show-delete="false"
                :show-edit="false"
                :class-name="'scoping'"
                :selectable="true"
                :de-select-all="deSelectAllSPUtama"
                @onSelect="onSelectSubProjects"
              />
            </el-card>
            <el-card v-if="subProjects.length > 0" shadow="never" style="margin-top:1em;">
              <div slot="header" class="clearfix card-header" style="text-align:center; font-weight:bold;">
                <span>Kegiatan Pendukung</span>
              </div>
              <components-list
                :id="'supporting_'+stage.id"
                :components="subProjects.filter(s => s.type === 'pendukung')"
                :show-delete="false"
                :show-edit="false"
                :active-component="activeScoping.sub_projects"
                :class-name="'scoping'"
                :selectable="true"
                :de-select-all="deSelectAllSPPendukung"
                @onSelect="onSelectSubProjects"
              />
            </el-card>
          </el-col>
          <el-col :span="4">
            <el-card shadow="never">
              <div slot="header" class="clearfix card-header" style="text-align:center; font-weight:bold;">
                <span>Komponen Kegiatan</span>
              </div>
              <div style="margin-bottom:1em; ">
                <components-list
                  v-if="components && components.length > 0"
                  :id="'scopingComp_'+'stage_'+stage.id+'_'+index"
                  :key="'comp_'+ stage.id +'_'+index"
                  :components="(activeScoping.sub_projects === null) ? components.filter(c => c.id_project_stage === stage.id) : components.filter(c => (c.id_project_stage === stage.id) && (c.id_sub_project === activeScoping.sub_projects.id))"
                  :selectable="activeScoping.sub_projects !== null"
                  :class-name="'scoping'"
                  :active-component="activeComponent"
                  :show-edit="isFormulator"
                  :show-delete="isFormulator"
                  :de-select-all="activeComponent === null"
                  @edit="showForm = true "
                  @onSelect="onSelectComponents"
                  @onDeselect="deselectComponents"
                />
              </div>
              <el-button
                v-if="isFormulator && masterComponents && (masterComponents.filter(c => c.id_project_stage === stage.id ).length > 0) && (activeScoping.sub_projects !== null)"
                icon="el-icon-plus"
                size="mini"
                circle
                type="primary"
                plain
                @click="showForm = true"
              />
            </el-card>
          </el-col>
          <el-col :span="16">
            <table v-if="(componentTypes.length > 0)" id="scoupingTable">
              <thead style=" background: #216221 !important; ">
                <tr><th colspan="6">Komponen Lingkungan</th></tr>
                <tr>
                  <th
                    v-for="ct in componentTypes"
                    :key="'stage_'+ index +'header_ct_'+ct.id"
                    class="th-hues"
                  >
                    {{ ct.name }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td
                    v-for="ct in componentTypes"
                    :key="'stage_'+ stage.id +'_body_ct_'+ct.id"
                  >
                    <components-list
                      :id="'scopingHue_'+stage.id+'_hue_'+ct.id"
                      :key="'scopingHue_key_'+stage.id+'_hue_'+ct.id"
                      :components="(activeScoping.sub_projects === null) ? hues.filter(h => h.id_component_type === ct.id) : ((activeComponent === null ) ? [] : hues.filter(h => (h.id_component_type === ct.id) && (h.id_sub_project_component === activeComponent.id_sub_project_component)))"
                      :selectable="(activeScoping.sub_projects !== null) && (activeScoping.component !== null )"
                      :class-name="'scoping'"
                      :show-edit="isFormulator"
                      :show-delete="isFormulator"
                      :active-component="activeHue"
                      :de-select-all="activeHue === null"
                      @edit="showAddHue = true"
                      @onSave="onSaveHue"
                      @onSelect="onSelectHues"
                    />
                    <el-button
                      v-if="masterHues && (masterHues.filter(c => c.id_component_type === ct.id ).length > 0) && (activeScoping.sub_projects !== null) && (activeScoping.component !== null )"
                      icon="el-icon-plus"
                      size="mini"
                      circle
                      type="primary"
                      plain
                      @click="addHue(ct.id)"
                    />
                  </td>
                </tr>
              </tbody>
            </table>
          </el-col>
        </el-row>
      </el-tab-pane>
    </el-tabs>
    <form-add-component
      :show="showForm"
      :data="activeScoping"
      :master-components="masterComponents.filter(c => c.id_project_stage === id_project_stage)"
      @onClose="showForm = false"
      @onSave="onSaveComponent"
    />
    <form-add-hue
      v-if="(activeScoping.component !== null) && (activeScoping.id_component_type !== null)"
      :show="showAddHue"
      :data="activeScoping"
      :master-component="masterComponents.find(c => c.id === activeScoping.component.id)"
      :master-hues="masterHues.filter(c => c.id_component_type === activeScoping.id_component_type)"
      @onClose="showAddHue = false"
      @onSave="onSaveHue"
    />
  </div>
</template>
<script>
import Resource from '@/api/resource';
import ComponentsList from './components/tables/ComponentsList.vue';
import FormAddComponent from './components/forms/FormAddComponent.vue';
import FormAddHue from './components/forms/FormAddHue.vue';
const projectResource = new Resource('projects');
const subProjectComponent = new Resource('subproject-components');
const subProjectHue = new Resource('sub-project-rona-awals');

export default {
  name: 'TabelPelingkupan',
  components: { ComponentsList, FormAddComponent, FormAddHue /* FormPelingkupan*/ },
  props: {
    masterComponents: {
      type: Array,
      default: function() {
        return [];
      },
    },
    masterHues: {
      type: Array,
      default: function() {
        return [];
      },
    },
    componentTypes: {
      type: Array,
      default: function() {
        return [];
      },
    },
    projectStages: {
      type: Array,
      default: function() {
        return [];
      },
    },
  },
  data(){
    return {
      id_project: 0,
      id_project_stage: null,
      mapping: [],
      subProjects: [],
      activeName: 'stage4',
      activeComponent: null,
      activeHue: null,
      activeScoping: {
        id_project: 0,
        project_stage: null,
        component: null,
        rona_awal: null,
        sub_projects: null,
        id_component_type: null,
      },
      savedComponents: [],
      savedHues: [],
      components: [],
      hues: [],
      showForm: false,
      showAddHue: false,
      mode: 0,
      deSelectAllSPUtama: false,
      deSelectAllSPPendukung: false,
      deSelectAllComponents: false,
      deSelectAllHues: false,
      current_component_type: null,
    };
  },
  computed: {
    isAndal() {
      return this.$route.name === 'penyusunanAndal';
    },
    isFormulator() {
      return this.$store.getters.roles.includes('formulator');
    },
  },
  mounted(){
    this.id_project = this.$route.params && this.$route.params.id;
    this.initActiveScoping();
    this.initMapping();
  },
  methods: {
    initActiveScoping() {
      const ps = this.projectStages.find(ps => ps.id === parseInt(this.activeName.charAt(this.activeName.length - 1)));
      this.current_component_type = null;
      this.id_project_stage = parseInt(this.activeName.charAt(this.activeName.length - 1));
      this.activeScoping.id_project = this.$route.params && this.$route.params.id;
      this.activeScoping.project_stage = ps;
      this.activeScoping.component = null;
      this.activeScoping.rona_awal = null;
      this.activeScoping.sub_projects = null;
      this.activeScoping.id_component_type = null;
      this.activeComponent = null;
      this.activeHue = null;
    },
    initMapping() {
      this.getSubProjects();
      this.getSubProjectComponents();
      this.getSubProjectsHues();
    },
    async getSubProjects(){
      const id = this.$route.params && this.$route.params.id;
      await projectResource.list({ id: id })
        .then((res) => {
          if (res.list_sub_project) {
            this.subProjects = res.list_sub_project;
            // console.log('ada sbproject!', this.subProjects);
          }
        })
        .finally(() => {});
    },
    async getSubProjectComponents(){
      this.components = [];
      await subProjectComponent.list({ id_project: this.id_project, scoping: true })
        .then((res) => {
          /* const result = [];
          res.forEach((r)=>{
            const rec = {
                id_sub_project_component : r.id_project_component,
                description: r.description,
                measurement: r.measurement,
                id_sub_project : r.id_sub_project
            };
            const idx = result.findIndex(s => s.id === r.id);
            if (idx >= 0 ){
              result[idx].records.push(rec);
            }else {
              result.push({
                id: r.id,
                name: r.name,
                value: r.name,
                id_project_stage: r.id_project_stage,
                records: [rec],
              });
            }
          });
          // this.components = result;*/
          this.savedComponents = res;
          this.components = res;
          console.log('realigned: ', res);
        })
        .finally(() => {});
    },
    async getSubProjectsHues(){
      this.hues = [];
      await subProjectHue.list({ id_project: this.id_project, scoping: true })
        .then((res) => {
          this.savedHues = res;
          this.hues = res;
        })
        .finally(() => {});
    },
    dumpHues(){
      // console.log(hues);
      console.log('hues...', this.hues);
    },
    // components and hues
    onSelectComponents(sel){
      this.activeScoping.component = null;
      this.activeScoping.rona_awal = null;
      this.activeComponent = null;
      this.activeHue = null;

      if (sel.length > 0){
        this.activeScoping.component = this.components.find(c => c.id === sel[0]);
        this.activeComponent = this.activeScoping.component;
      }
      console.log('components:', this.activeScoping);
    },
    onSelectHues(sel){
      this.activeScoping.rona_awal = null;
      this.activeHue = null;
      if (sel.length > 0){
        const hue = this.hues.find(h => h.id === sel[0]);
        this.activeScoping.rona_awal = hue;
        this.activeHue = hue;
      }
      console.log('hues', this.activeScoping);
    },
    onSelectSubProjects(sel) {
      console.log('selectSubProject', sel.length);
      this.deSelectAllComponents = true;
      this.initActiveScoping();
      if (sel.length > 0){
        var sp = this.subProjects.find(sub => sub.id === sel[0]);
        console.log(sp);
        if (sp.type === 'pendukung') {
          this.deSelectAllSPUtama = true;
          this.deSelectAllSPPendukung = false;
        } else {
          this.deSelectAllSPPendukung = true;
          this.deSelectAllSPUtama = false;
        }

        this.activeScoping.sub_projects = sp;
        // get sub components
      } else {
        // clear board? or show all?

      }
      console.log('subproject', this.activeScoping);
    },
    onSaveComponent(obj){
      /* const rec = {
          id_sub_project_component : obj.id_project_component,
          description: obj.description,
          measurement: obj.measurement,
          id_sub_project : obj.id_sub_project
        };
      const idx = this.components.findIndex(c => c.id === obj.id);
      if(idx >= 0){
        this.components[idx].records.push(rec);
        this.activeComponent = this.components[idx];
      } else {
        this.components.push({
          id: obj.id,
          name: obj.name,
          value: obj.name,
          id_project_stage: obj.id_project_stage,
          records: [rec]
        });
      }*/
      this.components.push(obj);
      this.activeComponent = obj;
      this.activeScoping.component = obj;
      console.log(this.activeComponent);
    },
    onSaveHue(obj){
      this.hues.push(obj);
      this.activeHue = obj;
      this.activeScoping.rona_awal = obj;
      this.getSubProjectsHues();
      console.log('hues ', obj);
    },
    addComponent(){

    },
    addHue(id){
      this.activeScoping.id_component_type = id;
      this.current_component_type = id;
      console.log(this.masterHues.filter(c => c.id_component_type === this.current_component_type));
      this.showAddHue = true;
    },
    handleTabClick(tab, event){
      this.initActiveScoping();
    },
    handleSaveForm(val){
      if (!this.mapping.find(c => c.component.id === val.component.id) && !this.mapping.find(c => c.rona_awal.id === val.rona_awal.id)) {
        this.mapping.push({
        });
      }
    },
    spToString(arr){
      const str = [];
      arr.forEach((e) => {
        str.push(e.name);
      });
      return str.join(', ');
    },
  },
};
</script>
<style>
.scoping  p { font-size: 96%; }

table#scoupingTable{
  width:100%;
}
table#scoupingTable th.th-hues  {width: 16.67%;}
table#scoupingTable td { vertical-align: top; font-weight: normal; }
</style>
