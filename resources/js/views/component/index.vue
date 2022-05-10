<template>
  <div v-loading="loading" class="app-container" style="padding: 24px">
    <el-card>
      <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="Komponen Baku" name="standardised" style="padding: 1em;">
          <standardised-component v-if="(activeName === 'standardised') && (componentOptions.length > 0)" :component-options="componentOptions" />
        </el-tab-pane>
        <el-tab-pane label="Komponen Tidak Baku" name="nonstandardised" style="padding: 1em;">
          <non-standardised-component v-if="(activeName === 'nonstandardised') && (componentOptions.length > 0)" :component-options="componentOptions" />
        </el-tab-pane>
      </el-tabs>

    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
/*  import Pagination from '@/components/Pagination';
import ComponentTable from './components/ComponentTable.vue';
import ComponentDialog from './components/ComponentDialog.vue';
const componentResource = new Resource('components'); */
const projectStageResource = new Resource('project-stages');

import StandardisedComponent from './Baku.vue';
import NonStandardisedComponent from './BelumBaku.vue';

export default {
  name: 'ComponentList',
  components: {
    StandardisedComponent,
    NonStandardisedComponent,
  },
  data(){
    return {
      activeName: 'standardised',
      projectStages: [],
      componentOptions: [],
      loading: false,
    };
  },
  created() {
    this.getProjectStages();
  },
  methods: {
    /* async getProjectStages(){
      this.loading = true;
      const { data } = await projectStageResource.list({ limit: 1000 });
      this.componentOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    }, */
    async getProjectStages(){
      this.loading = true;
      await projectStageResource.list({ limit: 1000 })
        .then(res => {
          const data = res.data;
          this.componentOptions = data.map((i) => {
            return { value: i.id, label: i.name };
          });
        }).finally(() => {
          this.loading = false;
        });
    },
    handleClick(e){

    },
  },
};
</script>
