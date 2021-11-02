<template>
  <div class="app-container">
    <el-row :gutter="4">
      <el-col v-for="type of componentTypes" :key="type.id" :span="6" :xs="24">
        <aside align="center" style="margin-bottom: 0px;">
          {{ type.name }}
        </aside>
        <el-table
          :data="data[type.id]"
          :show-header="false"
          border
          fit
          highlight-current-row
        >
          <el-table-column align="left">
            <template slot-scope="scope">
              <el-row :gutter="4">
                <el-col :span="20" :xs="24">
                  {{ scope.row.name }}
                </el-col>
                <el-col :span="4" :xs="24" align="right">
                  <el-button type="danger" size="mini" icon="el-icon-close" @click="handleDelete(scope.row.id)" />
                </el-col>
              </el-row>
            </template>
          </el-table-column>
        </el-table>
        <el-row :gutter="4" style="margin-top:5px;">
          <el-col :span="24" :xs="24" align="left">
            <el-button type="success" size="mini" icon="el-icon-plus" @click="handleAddDialog()" />
          </el-col>
        </el-row>
      </el-col>
    </el-row>
  </div>
</template>
<script>
import Resource from '@/api/resource';
const componentTypesResource = new Resource('component-types');
const ronaAwalResource = new Resource('rona-awals');

export default {
  name: 'RonaAwalTable',
  data() {
    return {
      showAddDialog: false,
      componentTypes: [],
      data: {},
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    handleAddDialog() {
      this.showAddDialog = true;
    },
    handleDelete(id_component) {
    },
    async getData(){
      const compTypes = await componentTypesResource.list({});
      this.componentTypes = compTypes.data;
      const ronaAwalList = await ronaAwalResource.list({
        all: true,
      });
      const ronaAwals = ronaAwalList.data;
      const n = [];
      const dataPerStep = {};
      this.componentTypes.map((s) => {
        n.push(1);
        dataPerStep[s.id] = [];
      });
      ronaAwals.map((k) => {
        const i = k.id_component_type - 1;
        k.no = n[i];
        n[i]++;
      });
      const data = {};
      this.componentTypes.map((s) => {
        ronaAwals.map((k) => {
          if (k.id_component_type === s.id){
            dataPerStep[k.id_component_type].push(k);
          }
        });
        data[s.id] = dataPerStep[s.id];
      });
      this.data = data;

      this.$emit('handleSaveRonaAwals', ronaAwals);
    },
  },
};
</script>
