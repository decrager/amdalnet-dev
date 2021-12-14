<template>
  <div>
    <el-button
      type="success"
      size="small"
      icon="el-icon-check"
      @click="handleSaveForm()"
    > Simpan Perubahan </el-button>
    <div style="overflow: scroll;">
      <table style="width:100%;">
        <thead>
          <tr>
            <th rowspan="2">No.</th>
            <th rowspan="2">Sumber Dampak</th>
            <th rowspan="2">Jenis Dampak</th>
            <th rowspan="2">Besaran Dampak</th>
            <th colspan="3">Standar Pengelolaan Lingkungan Hidup</th>
            <th rowspan="2">Institusi Pengelolaan Lingkungan Hidup</th>
            <th rowspan="2">Keterangan</th>
          </tr>
          <tr>
            <th>Bentuk</th>
            <th>Lokasi</th>
            <th>Periode</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="stage in data">
            <tr :key="mtype +'_stages_'+ stage.stage.id">
              <td style="font-weight: bold; " colspan="9" class="header">&nbsp;&nbsp;{{ stage.stage.name }}</td>
            </tr>
            <template v-for="(dampak, idx) in stage.dampak">
              <tr :key="mtype +'_stages_'+ stage.stage.id + '_' + idx">
                <td>{{ idx + 1 }}</td>
                <td>{{ dampak.sumber }}</td>
                <td>{{ dampak.jenis }} {{ dampak.rona_awal }} akibat {{ dampak.sumber }}</td>
                <td>{{ dampak.besaran }}</td>
                <td>
                  <template v-for="(bentuk, bi) in dampak.standar_pengelolaan.bentuk">
                    <div :key="mtype + '_bentuk_' + stage.stage.id + '_' + idx + '_' + bi" class="item-bentuk">
                      {{ bi + 1 }}. {{ bentuk }}
                    </div>
                  </template>
                  <el-button icon="el-icon-plus" circle style="margin-top:3em;display:block;" />
                </td>
                <td>
                  <el-input
                    v-model="dampak.standar_pengelolaan.lokasi"
                    type="textarea"
                    :rows="2"
                    placeholder="Please input"
                  />
                </td>
                <td>{{ dampak.standar_pengelolaan.periode }}</td>
                <td>
                  Pelaksana: {{ dampak.institusi.pelaksana }} <br>
                  Pengawas: {{ dampak.institusi.pengawas }} <br>
                  Penerima Laporan: {{ dampak.institusi.penerima_laporan }} <br>
                </td>
                <td>
                  <el-input
                    v-model="dampak.keterangan"
                    type="textarea"
                    :rows="2"
                    placeholder="Please input"
                  />
                </td>
              </tr>
            </template>
          </template>
        </tbody>
      </table>
    </div>

  </div>
</template>
<script>
export default {
  name: 'MatriksUklUpl',
  props: {
    mtype: {
      type: String,
      default: function(){
        return 'ukl';
      },
    },
    data: {
      type: Array,
      default: function(){
        return {};
      },
    },
  },
  methods: {
    handleSaveForm(){
      return;
    },
  },
};
</script>
<style scoped>

div.item-bentuk {
  padding: .5em;
  border-radius: .5em;
  border: 1px solid #e0e0e0;
}
</style>
