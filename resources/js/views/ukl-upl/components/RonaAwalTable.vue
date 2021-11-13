<template>
  <div class="app-container">
    <el-row :gutter="4">
      <el-col v-for="type of componentTypes" :key="type.id" :span="6" :xs="24">
        <aside align="center" style="margin-bottom: 0px; background-color: #def5cf">
          Komponen {{ type.name }}
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
    <rona-awal-dialog
      :rona="ronaAwal"
      :show="showAddDialog"
      :options="componentTypeOptions"
      @handleSubmitRonaAwal="handleSubmitRonaAwal"
      @handleCancelRonaAwal="handleCancelRonaAwal"
    />
  </div>
</template>
<script>
import Resource from '@/api/resource';
import RonaAwalDialog from '@/views/rona-awal/components/RonaAwalDialog.vue';
const componentTypesResource = new Resource('component-types');
const ronaAwalResource = new Resource('rona-awals');

export default {
  name: 'RonaAwalTable',
  components: { RonaAwalDialog },
  data() {
    return {
      showAddDialog: false,
      data: {},
      ronaAwals: [],
      ronaAwal: {},
      componentTypes: [],
      componentTypeOptions: [],
    };
  },
  mounted() {
    this.getData();
    this.getComponentTypeOptions();
  },
  methods: {
    handleAddDialog() {
      this.showAddDialog = true;
    },
    async getComponentTypeOptions() {
      const { data } = await componentTypesResource.list({});
      this.componentTypeOptions = data.map((i) => {
        return { value: i.id, label: i.name };
      });
    },
    async getData(){
      const compTypes = await componentTypesResource.list({});
      this.componentTypes = compTypes.data;
      const ronaAwalList = await ronaAwalResource.list({
        all: true,
      });
      this.ronaAwals = ronaAwalList.data;
      this.reloadData();
      this.$emit('handleSaveRonaAwals', this.ronaAwals);
    },
    reloadData() {
      const dataPerStep = {};
      this.componentTypes.map((s) => {
        dataPerStep[s.id] = [];
      });
      const data = {};
      this.componentTypes.map((s) => {
        this.ronaAwals.map((k) => {
          if (k.id_component_type === s.id){
            dataPerStep[k.id_component_type].push(k);
          }
        });
        data[s.id] = dataPerStep[s.id];
      });
      this.data = data;
    },
    handleSubmitRonaAwal() {
      this.ronaAwal.id = null;
      this.ronaAwals.push(this.ronaAwal);
      this.reloadData();
      this.$emit('handleUpdateRonaAwals', this.ronaAwals);
      this.showAddDialog = false;
    },
    handleDelete(id) {
      this.ronaAwals = this.ronaAwals.filter(rAwal => rAwal.id !== id);
      this.reloadData();
      this.$emit('handleUpdateRonaAwals', this.ronaAwals);
    },
    handleCancelRonaAwal() {
      this.ronaAwal = {};
      this.showAddDialog = false;
    },
  },
};
</script>
