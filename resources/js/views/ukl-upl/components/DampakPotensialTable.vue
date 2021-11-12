<template>
  <table>
    <tr class="tr-header">
      <td class="td-header">
        <span>Komponen Dampak</span>
      </td>
      <td class="td-header" align="center">
        <span>Komponen Rona Lingkungan Awal</span>
      </td>
      <td class="td-header" width="130">
        <span />
      </td>
      <td class="td-header" align="center">
        <span>Sumber Dampak</span>
      </td>
    </tr>
    <tr v-for="impact of data" :key="impact.id" class="tr-data">
      <td v-if="impact.is_stage" colspan="5" class="td-data">
        <span>{{ impact.project_stage_name }}</span>
      </td>
      <td v-if="!impact.is_stage" class="td-data">
        <el-select
          v-model="impact.id_change_type"
          placeholder="Perubahan"
          style="width: 100%"
        >
          <el-option
            v-for="item of changeTypeOptions"
            :key="item.id"
            :label="item.name"
            :value="item.id"
          />
        </el-select>
      </td>
      <td v-if="!impact.is_stage" class="td-data" align="center">
        <span>{{ impact.rona_awal_name }}</span>
      </td>
      <td v-if="!impact.is_stage" class="td-data" align="center">
        <span>akibat</span>
      </td>
      <td v-if="!impact.is_stage" class="td-data" align="center">
        <span>{{ impact.component_name }}</span>
      </td>
    </tr>
  </table>
</template>

<script>
import Resource from '@/api/resource';
const changeTypeResource = new Resource('change-types');
const unitResource = new Resource('units');

export default {
  name: 'DampakPotensialTable',
  props: {
    data: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      changeTypeOptions: [],
      unitOptions: [],
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    async getData() {
      const changeTypeList = await changeTypeResource.list({});
      this.changeTypeOptions = changeTypeList.data;
      const unitList = await unitResource.list({});
      this.unitOptions = unitList.data;
    },
  },
};
</script>

<style scoped>
table {
  border-collapse: collapse;
  font-size: 14px;
}
.tr-header {
  border: 1px solid gray;
  background-color: #def5cf;
}
.td-header {
  border: 1px solid gray;
  padding: 10px;
}
.tr-data, .td-data {
  border: 1px solid gray;
  padding: 10px;
}
</style>
