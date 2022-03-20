<template>
  <div class="scoupingModule" style="margin-top:2em;">

    <el-tabs v-if="projectStages.length > 0" v-model="activeName">
      <el-tab-pane
        v-for="(stage, index) in projectStages"
        :key="'stage'+ index +'_tab'"
        :label="stage.name"
        :name="'stage'+index"
      >
        <el-row :gutter="1" style="margin-top: 1em;">
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
                :class-name="'scoping'"
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
                      :class-name="'scoping'"
                    />
                  </td>
                </tr>
              </tbody>
            </table>

          <!-- <el-table v-if="componentTypes.length > 0"
              style="width: 100%">
              <el-table-column label="Komponen Lingkungan">
                <el-table-column v-for="ct in componentTypes"
                                        :key="ct.name"
                                        :label="ct.name"
                                        style="width:16.6%"
                >
                <template>
                <components-list
                  :id="'scopingHue'+index+'_hue_'+ct.id"
                  :components="hues.filter(h => h.id === ct.id)"
                />

                </template>
                </el-table-column>
              </el-table-column>
            </el-table> -->
          </el-col>
          <el-col :span="4">
            <el-card shadow="never">
              <div slot="header" class="clearfix card-header" style="text-align:center; font-weight:bold;">
                <span>Kegiatan Utama</span>
              </div>
              <components-list
                :id="'main_'+stage.id"
                :components="subProjects.filter(s => s.type === 'utama')"
                :show-delete="false"
                :show-edit="false"
                :class-name="'scoping'"
              />
            </el-card>
            <el-card shadow="never">
              <div slot="header" class="clearfix card-header" style="text-align:center; font-weight:bold;">
                <span>Kegiatan Pendukung</span>
              </div>
              <components-list
                :id="'supporting_'+stage.id"
                :components="subProjects.filter(s => s.type === 'pendukung')"
                :show-delete="false"
                :show-edit="false"
                :class-name="'scoping'"
              />
            </el-card>
          </el-col>

        </el-row>
      </el-tab-pane>
    <!--
    <el-tab-pane label="Pra Konstruksi" name="first">

    </el-tab-pane>
    <el-tab-pane label="Config" name="second">Config</el-tab-pane>
    <el-tab-pane label="Role" name="third">Role</el-tab-pane>
    <el-tab-pane label="Task" name="fourth">Task</el-tab-pane>
    -->
    </el-tabs>

  </div>
</template>
<script>
import Resource from '@/api/resource';
import ComponentsList from './components/tables/ComponentsList.vue';
export default {
  name: 'TabelPelingkupan',
  components: { ComponentsList },
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
      activeName: 'stage0',
    };
  },
  /* watch: {
    components: function(val) {
      console.log('components...', val);
    },
  },*/
  mounted(){
    this.id_project = this.$route.params && this.$route.params.id;
    this.initMapping();
    this.getSubProjects();
  },
  methods: {
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
          }
        })
        .finally();
    },
    dumpHues(){
      // console.log(hues);
      console.log('hues...', this.hues);
    },
  },
};
</script>
<style>
.scoping  p { font-size: 90%; text-align:left; }

table#scoupingTable{
  width:100%;
}
table#scoupingTable th.th-hues  {width: 16.67%;}
table#scoupingTable td { vertical-align: top; font-weight: normal; }
</style>
