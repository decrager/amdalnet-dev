<template>
  <div style="font-size: 10pt;">
    <el-button
      type="success"
      size="small"
      icon="el-icon-check"
      style="margin-bottom: 10px;"
      @click="handleSaveForm()"
    >
      Simpan Perubahan
    </el-button>
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
const projectComponentResource = new Resource('project-components');
const projectRonaAwalResource = new Resource('project-rona-awals');
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
    handleSaveForm() {
      impactIdtResource
        .store({
          checked: this.checked,
          id_project: this.idProject,
        })
        .then((response) => {
          var message = (response.code === 200) ? 'Matriks Identifikasi Dampak berhasil disimpan' : 'Terjadi kesalahan pada server';
          var message_type = (response.code === 200) ? 'success' : 'error';
          this.$message({
            message: message,
            type: message_type,
            duration: 5 * 1000,
          });
          // reload accordion
          this.$emit('handleReloadVsaList', 'dampak-potensial');
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getDefaultCheckedItems() {
      return [
        {
          id_component: 2,
          id_rona_awal: 32,
        },
        {
          id_component: 16,
          id_rona_awal: 18,
        },
        {
          id_component: 11,
          id_rona_awal: 17,
        },
        {
          id_component: 11,
          id_rona_awal: 19,
        },
        {
          id_component: 2,
          id_rona_awal: 13,
        },
        {
          id_component: 4,
          id_rona_awal: 21,
        },
        {
          id_component: 18,
          id_rona_awal: 7,
        },
        {
          id_component: 20,
          id_rona_awal: 25,
        },
      ];
    },
    async getChecked() {
      const defaultCheckedItems = this.getDefaultCheckedItems();
      const dataArray = [];
      this.ronaAwals.map((r) => {
        const subDataArray = [];
        this.components.map((c) => {
          var checked = false;
          if (this.impacts.length > 0) {
            this.impacts.map((i) => {
              if (i.id_project_rona_awal === r.id && i.id_project_component === c.id){
                checked = true;
              }
            });
          } else {
            // default values
            defaultCheckedItems.map((d) => {
              if (d.id_rona_awal === r.id_rona_awal && d.id_component === c.id_component){
                checked = true;
              }
            });
          }
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
      // get components
      const listC = await projectComponentResource.list({
        id_project: this.idProject,
      });
      listC.data.map((c) => {
        if (c['name'] === null) {
          c['name'] = c['name_master'];
        }
        if (c['id_project_stage'] === null) {
          c['id_project_stage'] = c['id_project_stage_master'];
        }
      });
      this.components = this.sortComponents(listC.data);
      const listR = await projectRonaAwalResource.list({
        id_project: this.idProject,
      });
      this.ronaAwals = listR.data;
      this.ronaAwals.map((r) => {
        if (r['name'] === null) {
          r['name'] = r['name_master'];
        }
        if (r['id_component_type'] === null) {
          r['id_component_type'] = r['id_component_type_master'];
        }
      });
      const iList = await impactIdtResource.list({
        id_project: this.idProject,
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
