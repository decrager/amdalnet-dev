<template>
  <div class="container">
    <el-table
      :data="data"
      :span-method="arraySpanMethod"
    >
      <el-table-column prop="component_name" label="Komponen Kegiatan" align="left" />
      <el-table-column v-for="ct in component_types" :key="ct" :label="ct" prop="component_types" align="center">
        <el-table-column v-for="r in ronaAwalListFiltered(ct)" :key="r.key" :label="r.name" :prop="JSON.stringify({ct, property: r.key})" align="center" :formatter="cellFormatter" />
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'MatriksDPHTable',
  data() {
    return {
      idProject: 0,
      component_types: [],
      data: [],
      ronaMapping: {},
      maxColspan: 1,
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    ronaAwalListFiltered(component_type) {
      return this.ronaMapping[component_type];
    },
    cellFormatter(row, col) {
      const key = JSON.parse(col.property);
      const d = row.component_types.find(r => r.name === key.ct);
      if (d && d[key.property]) {
        return d[key.property];
      }
      return ' ';
    },
    arraySpanMethod({ row, column, rowIndex, columnIndex }) {
      if (row.type === 'stage') {
        return [1, this.maxColspan];
      }
      if (row.type === 'component') {
        return [1, 1];
      }
    },
    setMaxColspan(){
      let length = 0;
      Object.keys(this.ronaMapping).forEach(key => {
        length += this.ronaMapping[key].length;
      });
      this.maxColspan = length + 1;
    },
    async getData() {
      this.idProject = parseInt(this.$route.params && this.$route.params.id);
      const isAndal = this.$route.name === 'penyusunanAndal' ? 'true' : 'false';
      await axios.get('api/matriks-dampak/rona-mapping/' + this.idProject)
        .then(response => {
          this.ronaMapping = response.data;
        });
      await axios.get('api/matriks-dampak/table-dph/' + this.idProject + '?isAndal=' + isAndal)
        .then(response => {
          this.data = response.data;
          const ctypes = {};
          this.data.forEach(row => {
            row.component_types.forEach(c => {
              ctypes[c.name] = 1;
            });
          });
          this.component_types = Object.keys(ctypes);
          this.setMaxColspan();
        });
    },
  },
};
</script>
