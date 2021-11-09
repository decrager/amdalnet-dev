<template>
  <div style="font-size: 10pt;">
    <table>
      <tr class="tr-header">
        <td v-for="comp of header[0]" :key="comp.id" :colspan="comp.colspan" :rowspan="comp.rowspan" align="center" class="td-header">
          <span>{{ comp.name }}</span>
        </td>
      </tr>
      <tr class="tr-header">
        <td v-for="comp of header[1]" :key="comp.id" style="width: 100px;" class="td-header">
          <span>{{ comp.name }}</span>
        </td>
      </tr>
      <tr v-for="r of checked" :key="r.id" class="tr-data">
        <td class="td-data">{{ r.name }}</td>
        <td v-for="c of r.sub" :key="c.id" style="width: 100px;" align="center" class="td-data">
          <input v-model="c.checked" type="checkbox">
        </td>
      </tr>
    </table>
  </div>
</template>
<script>
import Resource from '@/api/resource';
const prjStageResource = new Resource('project-stages');
const componentResource = new Resource('components');
const ronaAwalResource = new Resource('rona-awals');
const impactIdtResource = new Resource('impact-identifications');

export default {
  name: 'MatrikIdentifikasiDampak',
  data() {
    return {
      idProject: 0,
      header: [],
      projectStages: [],
      components: [],
      ronaAwals: [],
      impacts: [],
      checked: [], // matriks
      data: {},
    };
  },
  mounted() {
    this.idProject = parseInt(this.$route.params && this.$route.params.id);
    this.getData();
  },
  methods: {
    async getChecked() {
      const dataArray = [];
      this.ronaAwals.map((r) => {
        const subDataArray = [];
        this.components.map((c) => {
          var checked = false;
          this.impacts.map((i) => {
            if (i.id_rona_awal === r.id && i.id_component === c.id){
              checked = true;
            }
          });
          subDataArray.push({
            id: c.id,
            checked: checked,
          });
        });
        dataArray.push({
          id: r.id,
          name: r.name,
          sub: subDataArray,
        });
      });
      this.checked = dataArray;
    },
    sortComponents(comps) {
      const ordered = [];
      var stageIds = [4, 1, 2, 3];
      stageIds.map((id) => {
        comps.map((c) => {
          if (c.id_project_stage === id){
            ordered.push(c);
          }
        });
      });
      return ordered;
    },
    async getData() {
      const { data } = await prjStageResource.list({});
      this.projectStages = data;
      const listC = await componentResource.list({
        all: true,
      });
      this.components = this.sortComponents(listC.data);
      const listR = await ronaAwalResource.list({
        all: true,
      });
      this.ronaAwals = listR.data;
      const iList = await impactIdtResource.list({
        id_project: this.id_project,
      });
      this.impacts = iList.data;
      const dataRow1 = [
        {
          id: 0,
          name: 'Komponen Lingkungan/Sumber Dampak',
          rowspan: 2,
        },
      ];
      this.projectStages.map((s) => {
        var numComps = 0;
        this.components.map((c) => {
          if (c.id_project_stage === s.id){
            numComps++;
          }
        });
        s['rowspan'] = 1;
        s['colspan'] = numComps;
        dataRow1.push(s);
      });
      this.header = [
        dataRow1,
        this.components,
      ];
      this.getChecked();
    },
  },
};
</script>

<style scoped>
table {
  border-collapse: collapse;
}
.tr-header {
  border: 1px solid white;
  background-color: #3AB06F;
  color: white;
}
.td-header {
  border: 1px solid white;
  padding: 10px;
}
.tr-data, .td-data {
  border: 1px solid gray;
  padding: 10px;
}
</style>
