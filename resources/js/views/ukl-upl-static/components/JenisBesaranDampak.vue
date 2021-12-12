<template>
  <div>
    <el-button
      type="success"
      size="small"
      icon="el-icon-check"
      @click="handleSaveForm()"
    > Simpan Perubahan </el-button>

    <table class="table-dampak">
      <thead>
        <tr>
          <th>Komponen Dampak</th>
          <th>Komponen Rona Lingkungan Awal</th>
          <th style="width:200px;">&nbsp;</th>
          <th>Sumber Dampak</th>
          <th>Besaran Dampak</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="(stage, id) in data">
          <tr :key="'jbd_stages_'+ (id+1)">
            <td style="font-weight: bold; " colspan="5" class="header">{{ stage.stage }}</td>
          </tr>

          <template v-for="(item, idx) in stage.dampak">
            <tr :key="'jbd_stages_'+ (id+1) + '_item_'+ idx">
              <td>
                <el-select v-model="stage.dampak[idx].change" placeholder="Select">
                  <el-option
                    v-for="op in options"
                    :key="op + '_'+id+'_'+idx"
                    :label="op"
                    :value="op"
                  />
                </el-select>
              </td>
              <td>{{ item.rona_awal }}</td>
              <td style="text-align:center;">akibat</td>
              <td>{{ item.sumber }}</td>
              <td>
                <el-input
                  v-model="stage.dampak[idx].besaran"
                  type="textarea"
                  :rows="3"
                  placeholder="Please input"
                />
              </td>
            </tr>

          </template>
        </template>
      </tbody>
    </table>
  </div>
</template>
<script>
export default {
  name: 'JenisBesaranDampak',
  props: {
    stages: {
      type: Array,
      default: function(){
        return {};
      },
    },
  },
  data() {
    return {
      options: ['Perubahan', 'Peningkatan', 'Penurunan', 'Gangguan', 'Lainnya'],
      data: [
        {
          stage: 'Pra Konstruksi',
          dampak: [
            {
              change: 'Perubahan',
              rona_awal: 'Arus Lalu Lintas',
              sumber: 'Pembebasan Lahan',
              besaran: null,
            },
            {
              change: 'Perubahan',
              rona_awal: 'Terumbu Karang',
              sumber: 'Pengamanan Perairan',
              besaran: null,
            },
            {
              change: 'Penurunan',
              rona_awal: 'Air Limbah',
              sumber: 'Sosialisasi',
              besaran: null,
            },
          ],
        },
        {
          stage: 'Konstruksi',
          dampak: [
            {
              change: 'Penurunan',
              rona_awal: 'Pendapatan',
              sumber: 'Mobilisasi Peralatan dan Material',
              besaran: null,
            },
            {
              change: 'Peningkatan',
              rona_awal: 'Keresahan Masyarakat',
              sumber: 'Penerimaan Tenaga Kerja',
              besaran: null,
            },
          ],

        },
        {
          stage: 'Operasi',
          dampak: [
            {
              change: 'Penurunan',
              rona_awal: 'Kesehatan Masyarajat',
              sumber: 'Operasional Unit/Fasilitas Utama',
              besaran: null,
            },
          ],
        },
        {
          stage: 'Pasca Operasi',
          dampak: [],
        },
      ],
    };
  },
  methods: {
    handleSaveForm() {
    },
  },
};
</script>
<style scoped>
table.table-dampak td:not(.header) {
    text-align: center;
    vertical-align: top;
}
</style>
