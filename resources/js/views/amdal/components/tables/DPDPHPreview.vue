<template>
  <div>
    <el-button
      v-show="(impacts) && (impacts.length > 0)"
      icon="el-icon-view"
      type="primary"
      plain
      @click="doPreview"
    > Preview </el-button>&nbsp;

    <el-dialog
      :visible.sync="preview"
      width="98%"
      top="2em"
      @close="preview = false"
    >
      <div slot="title" style="font-weight: bold;">
        EVALUASI DAMPAK POTENSIAL &amp; DAMPAK PENTING HIPOTETIK &mdash; PREVIEW
      </div>
      <!--
    <el-table
      :data="impacts"
      :span-method="spanMethod"
    >
      <el-table-column
        type="index"
        width="50">
      <el-table-column
        prop="komponen"
      />

    </el-table> -->
      <div v-loading="loading" class="dpdph-preview-table" :style="'height: '+ height + 'px;'">
        <table style="border-collapse: collapse;clear:both; margin:0; ">
          <!-- based on mockup received on Thu, 2 Dec 2021 -->
          <thead>
            <tr>
              <th colspan="2" style="width:18%">Rencana Usaha dan/atau Kegiatan yang Berpotensi Menimbulkan Dampak Lingkungan</th>
              <th>Pengelolaan yang sudah direncanakan</th>
              <th>Komponen Rona yang Terkena Dampak</th>
              <th>Dampak Potensial</th>
              <th style="width:20%;">Evaluasi Dampak Potensial</th>
              <th>Dampak Penting Hipotetik</th>
              <th>Wilayah Studi</th>
              <th>Batas Waktu Kajian</th>
            </tr>
          </thead>
          <template v-for="stage in data">
            <tr :key="'stage_'+ stage.id" :data-index="stage.name">
              <td colspan="9" class="title"><p><strong>{{ stage.name }}</strong></p></td>
            </tr>
            <tbody :key="'hipotetik_' + stage.id">
              <template v-for="(impact, idx) in stage.data">
                <tr :key="'impact_'+ impact.id" class="title" animated>
                  <td style="width:30px;">{{ (idx + 1) }}.</td>
                  <td>{{ impact.komponen }}</td>
                  <td>{{ impact.initial_study_plan }}</td>
                  <td>{{ impact.rona_awal }}</td>
                  <td>
                    <span v-if="(impact.change_type_name) && (impact.change_type_name !== '')">{{ impact.change_type_name }}</span>
                    <span v-else style="color: red; font-weight:bold;">*...*</span>

                    {{ impact.rona_awal }} <span style="text-decoration: underline;">akibat</span> {{ impact.komponen }}</td>
                  <td>
                    <template v-if="impact.pie.length > 0">
                      <template v-for="(pie, index) in pieParams">
                        <div :key="'pie_'+impact.id+'_'+pie.id" class="div-fka formA">
                          <el-popover
                            v-if="(pie.description !== null ) && (pie.description !== '')"
                            placement="top-start"
                            width="350"
                            trigger="hover"
                          >
                            <p style="word-break: break-word !important; text-align:left !important;">{{ pie.description }}</p>
                            <p slot="reference" :key="'po_'+ pie.id + '_'+ impact.id" style="font-weight:bold; cursor: pointer;">{{ pie.name }}</p>
                          </el-popover>
                          <p v-else :key="'po_'+ pie.id + '_'+ impact.id" style="font-weight:bold;">{{ pie.name }}</p>

                          <p v-if="impact.pie">
                            <span v-if="impact.pie[index].text && (impact.pie[index].text !=='')">{{ impact.pie[index].text }}</span>
                            <span v-else style="color: red; font-weight: bold;">&mdash;</span></p>
                        </div>
                      </template>
                    </template>
                  </td>
                  <td>
                    <p> {{ impact.is_hypothetical_significant ? 'DPH' : ('DTPH' + (impact.is_managed ? ' dikelola' : ' tidak dikelola' ) ) }} </p>

                  </td>
                  <td>
                    {{ impact.is_hypothetical_significant ? impact.study_location : '' }}
                  </td>
                  <td>
                    <span v-if="impact.is_hypothetical_significant && impact.study_length_year"> {{ impact.study_length_year }} tahun </span>
                    <span v-if="impact.is_hypothetical_significant && impact.study_length_month"> {{ impact.study_length_month }} bulan </span>
                  </td>
                </tr>
              </template>
            </tbody>
          </template>
        </table>
      </div>

      <div slot="footer" class="dialog-footer">
        <el-button type="primary" @click="preview = false">Tutup</el-button>
      </div>
    </el-dialog>

  </div>

</template>
<script>
import Resource from '@/api/resource';
const pieResource = new Resource('pie-entries');

export default {
  name: 'DampakHipotetikPreview',
  props: {
    impacts: {
      type: Array,
      default: function(){
        return [];
      },
    },
    mode: {
      type: Number,
      default: 0,
    },
    stages: {
      type: Array,
      default: function(){
        return [];
      },
    },
    pieParams: {
      type: Array,
      default: function() {
        return [];
      },
    },
  },
  data(){
    return {
      preview: false,
      loading: false,
      data: [],
    };
  },
  computed: {
    height(){
      return window.innerHeight * 0.65;
    },
  },
  watch: {
    impacts: function(val){
      // console.log('preview: ', val);
    },
    stages: function(val){
    },
  },
  methods: {
    doPreview() {
      this.preview = true;
      const ids = [];
      this.impacts.map(e => {
        if (!('pie' in e)){
          ids.push(e.id);
        }
      });

      if (ids.length > 0) {
        this.getData(ids);
      } else {
        this.processData();
      }
    },
    async getData(ids){
      this.loading = true;
      await pieResource.list({
        id_impact_identification: ids,
        mode: this.mode,
      })
        .then((res) => {
          ids.map(id => {
            const index = this.impacts.findIndex(e => e.id === id);

            const pies = [];
            let temp = [];

            if (this.mode === 0){
              temp = res.filter(r => r.id_impact_identification === id);
            } else {
              temp = res.filter(r => r.id_impact_identification_clone === id);
            }
            temp.map(t => {
              pies.push({
                id: t.id,
                id_impact_identification: id,
                id_pie_param: t.id_pie_param,
                text: t.text,
              });
            });
            this.impacts[index].hasChanges = false;
            this.impacts[index].pie = pies;
          });
          // console.log('after retrieving: ', this.impacts);
          this.processData();
        })
        .finally(() => {
          this.loading = false;
        });

      // console.log('noPies in: ', ids);
    },
    processData(){
      this.data = [];
      this.stages.map(s => {
        const stage = {
          id: s.id,
          name: s.name,
          data: this.dataByStage(s.id),
        };
        this.data.push(stage);
      });
    },
    dataByStage(stage){
      return this.impacts.filter(e => e.project_stage === stage);
    },

  },
};
</script>
<style lang="scss" scoped>

table th, table td {word-break: normal !important; padding:.5em; line-height:1.2em; border: 1px solid #eee;}
table td { vertical-align: top !important;}

div.div-fka {
  padding: 0.5em;
  margin-bottom: 0.6em;
  background-color: #fafafa;
}

div.dpdph-preview-table{
  overflow-y: scroll;

   table {
      text-align: left;
      position: relative;

      th, td {
        word-break: normal !important;
      }

      th {
        padding: 1em auto;
        background: #216221;
        position: sticky;
        top: 0;
        text-align: center;
        color: white;
      }
      td.title p {
        font-size: 1.1em;
      }

    }
}
</style>
