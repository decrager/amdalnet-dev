<template>
  <el-table
    :key="refresh"
    :data="list"
    max-height="800"
    :header-cell-style="{ background: '#099C4B', color: 'white' }"
  >
    <el-table-column align="center" label="No." width="55">
      <template slot-scope="scope">
        {{ scope.$index + 1 }}
      </template>
    </el-table-column>
    <el-table-column label="Sektor" width="300px">
      <template slot-scope="scope">
        <el-select
          v-model="scope.row.sector"
          placeholder="Pilih"
          style="width: 100%"
          filterable
          @change="changeSector(scope.row)"
        >
          <el-option
            v-for="item in sectorOpt"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </template>
    </el-table-column>
    <el-table-column label="Kegiatan" width="600px">
      <template slot-scope="scope">
        <el-select
          v-model="scope.row.project"
          placeholder="Pilih"
          style="width: 100%"
          filterable
          @change="changeProject(scope.row)"
        >
          <el-option
            v-for="item in scope.row.projectOpt"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </template>
    </el-table-column>
    <el-table-column label="Kewenangan">
      <template slot-scope="scope">
        {{ scope.row.authority }}
      </template>
    </el-table-column>
    <el-table-column width="100px">
      <template slot-scope="scope">
        <el-popconfirm
          title="Hapus Kewenangan ?"
          @confirm="list.splice(scope.$index,1)"
        >
          <el-button slot="reference" type="danger" icon="el-icon-close" />
        </el-popconfirm>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
import Resource from '@/api/resource';
const authorityResource = new Resource('authorities');

export default {
  name: 'KewenanganTable',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      sectorOpt: [],
      projectOpt: [],
      refresh: 0,
      authorities: [],
    };
  },
  async created() {
    await this.getSectors();
  },
  methods: {
    changeProject(val){
      const final = this.authorities.filter(e => e.sector === val.sector && e.project === val.project);
      val.authority = final[0].authority;
    },
    async changeSector(val) {
      this.authorities = await authorityResource.list({ sectors: val.sector });
      val.projectOpt = this.authorities.map((e) => {
        return {
          value: e.project,
          label: e.project,
        };
      });
      this.refresh++;
    },
    async getSectors() {
      const authorities = await authorityResource.list();
      this.sectorOpt = authorities.map((e) => {
        return { value: e.sector, label: e.sector };
      });
      console.log(this.sectorOpt);
    },
  },
};
</script>
