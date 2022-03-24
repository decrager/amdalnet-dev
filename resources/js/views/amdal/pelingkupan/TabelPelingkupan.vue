<template>
  <div class="scoupingModule" style="margin-top:2em;">

    <el-tabs v-if="projectStages.length > 0" v-model="activeName" @tab-click="handleTabClick">
      <el-tab-pane
        v-for="(stage, index) in projectStages"
        :key="'stage'+ index +'_tab'"
        :label="stage.name"
        :name="'stage'+stage.id"
      >
        <el-row :gutter="1" style="margin-top: 1em;">
          <el-col :span="4">
            <el-card v-if="subProjects.length > 0" shadow="never">
              <div slot="header" class="clearfix card-header" style="text-align:center; font-weight:bold;">
                <span>Kegiatan Utama</span>
              </div>
              <components-list
                v-if="subProjects.length > 0"
                :id="'main_'+stage.id"
                :components="subProjects.filter(s => s.type === 'utama')"
                :show-delete="false"
                :show-edit="false"
                :class-name="'scoping'"
                :multiple-select="true"
                :selectable="true"
                @onSelect="onSelectSubProjects"
              />
            </el-card>
            <el-card v-if="subProjects.length > 0" shadow="never">
              <div slot="header" class="clearfix card-header" style="text-align:center; font-weight:bold;">
                <span>Kegiatan Pendukung</span>
              </div>
              <components-list
                :id="'supporting_'+stage.id"
                :components="subProjects.filter(s => s.type === 'pendukung')"
                :show-delete="false"
                :show-edit="false"
                :class-name="'scoping'"
                :selectable="true"
                :multiple-select="true"
                @onSelect="onSelectSubProjects"
              />
            </el-card>
          </el-col>
          <el-col :span="4">
            <el-card shadow="never">
              <div slot="header" class="clearfix card-header" style="text-align:center; font-weight:bold;">
                <span>Komponen Kegiatan</span>
              </div>
              <components-list
                :id="'scopingComp'+index"
                :components="components.filter(c => c.id_project_stage === stage.id)"
                :show-delete="false"
                :show-edit="false"
                :selectable="true"
                :class-name="'scoping'"
                @onSelect="onSelectComponents"
              />
            <!-- <el-button v-if="(components.filter(c => c.id_project_stage === stage.id )).length > 0" icon="el-icon-plus" size="mini" circle type="primary" plain/> -->
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
                    :key="'stage_'+ index +'_body_ct_'+ct.id"
                  >
                    <components-list
                      :id="'scopingHue'+index+'_hue_'+ct.id"
                      :components="hues.filter(h => h.id_component_type === ct.id)"
                      :show-delete="false"
                      :show-edit="false"
                      :selectable="true"
                      :class-name="'scoping'"
                      @onSelect="onSelectHues"
                    />
                  </td>
                </tr>
              </tbody>
            </table>
          </el-col>
        </el-row>
      </el-tab-pane>
    </el-tabs>
    <form-pelingkupan
      :show="showForm"
      :input="activeScoping"
      @onClose="showForm = false "
      @onSave="handleSaveForm"
    />
  </div>
</template>
<script>
import Resource from '@/api/resource';
import ComponentsList from './components/tables/ComponentsList.vue';
import FormPelingkupan from './components/forms/FormPelingkupan.vue';

export default {
  name: 'TabelPelingkupan',
  components: { ComponentsList, FormPelingkupan },
  props: {
    components: {
      type: Array,
      default: function() {
        return [];
      },
    },
    hues: {
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
      mapping: [],
      subProjects: [],
      activeName: 'stage4',
      activeScoping: {
        id_project: 0,
        project_stage: null,
        component: null,
        rona_awal: null,
        sub_projects: [],
      },
      highlights: {
        components: [],
        hues: [],
        subProjects: [],
      },
      showForm: false,
      mode: 0,
    };
  },
  /* watch: {
    components: function(val) {
      console.log('components...', val);
    },
  },*/
  mounted(){
    this.id_project = this.$route.params && this.$route.params.id;
    this.getSubProjects();
    this.initMapping();
    this.initActiveScoping();
  },
  methods: {
    initActiveScoping() {
      const ps = this.projectStages.find(ps => ps.id === parseInt(this.activeName.charAt(this.activeName.length - 1)));
      this.activeScoping.id_project = this.$route.params && this.$route.params.id;
      this.activeScoping.project_stage = ps;
      this.activeScoping.component = null;
      this.activeScoping.rona_awal = null;
      this.activeScoping.sub_projects = [];
    },
    initMapping() {
      /*
        element = {
          id_project,
          component,
          hue,
          subprojects
        }
      */
    },
    async getSubProjects(){
      const id = this.$route.params && this.$route.params.id;
      const projectResource = new Resource('projects');
      await projectResource.list({ id: id })
        .then((res) => {
          if (res.list_sub_project) {
            this.subProjects = res.list_sub_project;
            console.log('ada sbproject!', this.subProjects);
          }
        })
        .finally();
    },
    dumpHues(){
      // console.log(hues);
      console.log('hues...', this.hues);
    },
    // components and hues
    onSelectComponents(sel){
      if (sel.length > 0){
        this.activeScoping.component = this.components.find(c => c.id === sel[0]);
      } else {
        this.initActiveScoping();
      }
      console.log(this.activeScoping);
    },
    onSelectHues(sel){
      if (sel.length > 0){
        this.activeScoping.rona_awal = this.hues.find(h => h.id === sel[0]);
      } else {
        this.activeScoping.rona_awal = null;
        // this.initActiveScoping();
      }
      console.log(this.activeScoping);
    },
    onSelectSubProjects(sel) {
      this.activeScoping.sub_projects = [];
      if (sel.length >= 0){
        sel.forEach(s => {
          const sp = this.activeScoping.sub_projects.find(p => p.id === s);
          if (!sp) {
            const sub = this.subProjects.find(subp => subp.id === s);
            this.activeScoping.sub_projects.push({
              id: s,
              name: sub.name,
              type: sub.type,
              com_description: '',
              com_measurement: '',
              hue_description: '',
              hue_measurement: '',
            });
          }
        });
        /*
        foreach(s in sel){
          if (!this.activeScoping.sub_projects.includes(s)) {
            this.activeScoping.sub_projects.push(s);
          }
        }*/
      }

      this.activeScoping.project_stage = this.projectStages.find(p => p.id === parseInt(this.activeName.charAt(this.activeName.length - 1)));
      this.showForm = true;
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
.scoping  p { font-size: 96%; text-align:left; }

table#scoupingTable{
  width:100%;
}
table#scoupingTable th.th-hues  {width: 16.67%;}
table#scoupingTable td { vertical-align: top; font-weight: normal; }
</style>
