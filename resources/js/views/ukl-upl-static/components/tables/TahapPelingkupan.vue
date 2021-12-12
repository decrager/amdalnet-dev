<template>
  <div>
    <el-row>
      <el-col span="6">
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
      <el-col span="18">
        <!-- Komponen Lingkungan  -->
        <table style="width:100%;">
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
              <td>
                <!-- komponen kegiatan -->
                <el-button icon="el-icon-plus" circle style="margin-top:3em;display:block;" @click="kKDialogueVisible = true" />
              </td>
              <td>
                <el-button icon="el-icon-plus" circle style="margin-top:3em;display:block;" @click="kLGKDialogueVisible = true" />
              </td>
              <td>
                <el-button icon="el-icon-plus" circle style="margin-top:3em;display:block;" @click="kLBDialogueVisible = true" />
              </td>
              <td>
                <el-button icon="el-icon-plus" circle style="margin-top:3em;display:block;" @click="kLSBDialogueVisible = true" />
              </td>
              <td>
                <el-button icon="el-icon-plus" circle style="margin-top:3em;display:block;" @click="kLKMDialogueVisible = true" />
              </td>
              <td>
                <el-button icon="el-icon-plus" circle style="margin-top:3em;display:block;" @click="kLLDialogueVisible = true" />
              </td>

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
