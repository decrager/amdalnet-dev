<template>
  <div style="font-size: 10pt;">
    <el-button
      v-if="!isAndal"
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
          <span><b>{{ comp.name }}</b></span>
        </td>
      </tr>
      <tr class="tr-header">
        <td v-for="comp of header[1]" :key="comp.id" style="width: 100px;" class="td-header">
          <span><b>{{ comp.name }}</b></span>
        </td>
      </tr>
      <tr v-for="r of checked" :key="r.id" class="tr-data">
        <td v-if="r.is_component_type" :colspan="r.colspan" class="td-data">
          <span><b>{{ r.index }}. {{ r.name }}</b></span>
        </td>
        <td v-if="!r.is_component_type" class="td-data">
          <span>{{ r.index }}. {{ r.name }}</span>
        </td>
        <td v-for="c of r.sub" :key="c.id" style="width: 100px;" align="center" class="td-data">
          <template v-if="c.exists">
            <input v-model="c.checked" type="checkbox" :disabled="isAndal">
            <span>DPH</span>
          </template>
          <template v-if="!c.exists">
            <span>-</span>
          </template>
        </td>
      </tr>
    </table>
  </div>
</template>
<script>
import Resource from '@/api/resource';
const prjStageResource = new Resource('project-stages');
const subProjectComponentResource = new Resource('sub-project-components');
const subProjectRonaAwalResource = new Resource('sub-project-rona-awals');
const impactIdtResource = new Resource('impact-identifications');

export default {
  name: 'MatrikDampakPentingHipotetik',
  data() {
    return {
      idProject: 0,
      header: [],
      projectStages: [],
      components: [],
      ronaAwals: [],
      impacts: [],
      checked: [], // matriks
      colspan: 0,
      data: {},
    };
  },
  computed: {
    isAndal() {
      return this.$route.name === 'penyusunanAndal';
    },
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
          save_dph: true,
        })
        .then((response) => {
          var message = (response.code === 200) ? 'Matriks Identifikasi Dampak berhasil disimpan' : response.error;
          var message_type = (response.code === 200) ? 'success' : 'error';
          this.$message({
            message: message,
            type: message_type,
            duration: 5 * 1000,
          });
          // reload accordion
          this.$emit('handleReloadVsaList', 'bagan-alir');
        })
        .catch((error) => {
          console.log(error);
        });
    },
    async getChecked() {
      const dataArray = [];
      var rIndex = 0;
      var rIndex2 = 1;
      this.ronaAwals.map((r) => {
        const subDataArray = [];
        if (!r.is_component_type) {
          this.components.map((c) => {
            var checked = false;
            var exists = false;
            this.impacts.map((i) => {
              if (i.id_sub_project_rona_awal === r.id && i.id_sub_project_component === c.id){
                exists = true;
                if (i.is_hypothetical_significant) {
                  checked = true;
                }
              }
            });
            subDataArray.push({
              id: c.id,
              exists: exists,
              checked: checked,
              colspan: 1,
            });
          });
        }
        if (r.is_component_type) {
          r.name = r.component_type_name;
          r.index = String.fromCharCode('A'.charCodeAt(0) + rIndex);
          rIndex++;
          rIndex2 = 1;
        } else {
          r.index = rIndex2;
          rIndex2++;
        }
        dataArray.push({
          index: r.index,
          id: r.id,
          is_component_type: r.is_component_type,
          name: r.name,
          sub: subDataArray,
          colspan: this.colspan,
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
      const listC = await subProjectComponentResource.list({
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
      const listR = await subProjectRonaAwalResource.list({
        id_project: this.idProject,
        with_component_type: true,
      });
      this.ronaAwals = listR.data;
      this.colspan = listR.colspan;

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
  text-align: left;
}
.tr-header {
  border: 1px solid gray;
  background-color: #6cc26f;
  color: white;
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
