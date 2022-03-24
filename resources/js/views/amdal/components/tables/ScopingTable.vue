<template>
  <div class="scoping">
    <el-tabs v-if="projectStages.length > 0" v-model="activeName" @tab-click="handleTabClick">
      <el-tab-pane
        v-for="(stage, index) in projectStages"
        :key="'stage'+ index +'_tab'"
        :label="stage.name"
        :name="'stage'+stage.id"
      >
        {{ stage.name }}
      <!-- <components-list
      /> -->
      </el-tab-pane>
    </el-tabs>
  </div>
</template>
<script>
// import Resource from '@/api/resource';
// import ComponentsList from '../../pelingkupan/components/tables/ComponentsList.vue';
export default {
  name: 'ScopingTable',
  // components: { ComponentsList },
  props: {
    masterComponents: {
      type: Array,
      default: () => [],
    },
    masterHues: {
      type: Array,
      default: () => [],
    },
    projectStages: {
      type: Array,
      default: () => [],
    },
    componentTypes: {
      type: Array,
      default: () => [],
    },
  },
  data(){
    return {
      id_project: null,
      subProjects: [],
      projectComponents: [],
      projectHues: [],
      // projectActivities: [],
    };
  },
  mounted(){
    this.id_project = parseInt(this.$route.params && this.$route.params.id);
    this.getSubProjects();
  },
  methods: {

  },
};
</script>
