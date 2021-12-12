<template>
  <div>
    <el-row>
      <el-col span="8">
        <!-- Kegiatan Utama-->
        <table>
          <thead>
            <tr>
              <th>No.</th>
              <th>Kegiatan Utama</th>
            </tr>
          </thead>
          <tbody class="entries">
            <template v-for="(act, id) in activities.kegiatanUtama">
              <tr :key="'main_activity_'+ (id + 1)" class="activities">
                <td>{{ id + 1 }}.</td>
                <td>{{ act.name }}</td>
              </tr>
            </template>
          </tbody>
        </table>
        <!-- Kegiatan Pendukung-->
        <table>
          <thead>
            <tr>
              <th>No.</th>
              <th>Kegiatan Pendukung</th>
            </tr>
          </thead>
          <tbody class="entries">
            <template v-for="(act, id) in activities.kegiatanPendukung">
              <tr :key="'supporting_activity_'+ (id + 1)" class="activities">
                <td>{{ id + 1 }}.</td>
                <td>{{ act }}</td>
              </tr>
            </template>
          </tbody>
        </table>    </el-col>
      <el-col span="16">
        <!-- Komponen Lingkungan  -->
        <table>
          <thead>
            <tr>
              <th rowspan="2">Komponen Kegiatan</th>
              <th colspan="5">Komponen Lingkungan</th>
            </tr>
            <tr>
              <th>Geofisik Kimia</th>
              <th>Biologi</th>
              <th>Sosial Budaya</th>
              <th>Kesehatan Masyarakat</th>
              <th>Lainnya</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td />
            </tr>
          </tbody>
        </table>
      </el-col>
    </el-row>
  </div>
</template>
<script>

export default {
  name: 'TahapPelingkupan',
  props: {
    activities: {
      type: [],
      default: function(){
        return {};
      },
    },
  },
};
</script>
<style scoped>

</style>
