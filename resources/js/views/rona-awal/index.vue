<template>
  <div v-loading="loading" class="app-container" style="padding: 24px;">
    <el-card>
      <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="Rona Awal Baku" name="formalised" style="padding: 1em;">
          <formalised-hue v-if="(activeName === 'formalised') && (componentTypes.length > 0)" :component-options="componentTypes" />
        </el-tab-pane>
        <el-tab-pane label="Rona Awal Tidak Baku" name="non-formalised" style="padding: 1em;">
          <non-formalised-hue v-if="(activeName === 'non-formalised') && (componentTypes.length > 0)" :component-options="componentTypes" />
        </el-tab-pane>
      </el-tabs>

    </el-card>
  </div>
</template>
<script>
import Resource from '@/api/resource';
import FormalisedHue from './Baku.vue';
import NonFormalisedHue from './BelumBaku.vue';
const componentTypeResource = new Resource('component-types');

export default {
  name: 'HueList',
  components: {
    FormalisedHue,
    NonFormalisedHue,
  },
  data(){
    return {
      loading: false,
      componentTypes: [],
      activeName: 'formalised',
    };
  },
  mounted() {
    this.getComponentTypes();
  },
  methods: {
    async getComponentTypes(){
      this.loading = true;
      this.componentTypes = [];
      await componentTypeResource.list({})
        .then(res => {
          this.componentTypes = res.data;
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
};
</script>
\
