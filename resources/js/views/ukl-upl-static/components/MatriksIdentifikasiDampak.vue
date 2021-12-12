<template>
  <div class="app-container">
    <table class="title">
      <thead>
        <tr>
          <th rowspan="2">Komponen Lingkungan</th>
          <th colspan="6">Geofisik Kimia</th>
          <th colspan="3">Biologi</th>
          <th colspan="3">Sosial Ekonomi Budaya</th>
          <th colspan="2">Kesehatan Masyarakat</th>
        </tr>
        <tr>
          <th>Kualitas Udara</th>
          <th>Geologi</th>
          <th>Air Permukaan</th>
          <th>Kondisi Tanah</th>
          <th>Iklim</th>
          <th>Kualitas Air Sungai</th>
          <th>Flora</th>
          <th>Fauna</th>
          <th>Plankton dan Benthos</th>
          <th>Situs Arkeolog</th>
          <th>Demografi</th>
          <th>Pendapatan Masyarakat</th>
          <th>Fasilitas Kesehatan</th>
          <th>Kesehatan Masyarakat</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="(stage, id) in stages">
          <tr :key="'mid_stages_'+ (id+1)">
            <td style="font-weight: bold; " colspan="15">{{ stage }}</td>
          </tr>

          <template v-for="(item, idx) in data[id]">
            <tr :key="'mid_stages_'+ (id+1) + '_item_'+ idx">
              <td>{{ item.kl }}</td>
              <template v-for="(dp, di) in item.dampak">
                <td :key="'mid_stages_'+ (id+1) + '_item_'+ idx + '_dampak_'+ di" class="input-checkbox">
                  <el-checkbox v-model="item.dampak[di]" aria-readonly="true" />
                </td>
              </template>
            </tr>

          </template>
        </template>
      </tbody>
    </table>
  </div>
</template>
<script>
export default {
  name: 'MatriksIdentifikasiDampak',
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
      data: [
        [
          { kl: 'Pembebasan Lahan', dampak: [true, true, false, true, true, true, true, true, true, true, true, true, true, true] },
        ],
        [
          { kl: 'Penerimaan Tenaga Kerja', dampak: [false, false, false, true, true, false, false, false, false, false, false, true, true, true] },
          { kl: 'Penyiapan Tapak', dampak: [false, false, false, true, true, false, false, false, false, false, false, true, true, true] },
        ],
        [
          { kl: 'Pembangunan Gedung', dampak: [false, false, false, true, true, false, false, false, false, false, false, true, true, true] },
        ],
        [
          { kl: 'Pelepasan Tenaga Kerja', dampak: [false, false, false, true, true, false, false, false, false, false, false, true, true, true] },
        ],
      ],
    };
  },
};
</script>
<style scoped>
td.input-checkbox {text-align: center;}
</style>
