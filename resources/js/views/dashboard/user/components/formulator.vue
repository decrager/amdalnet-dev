<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Tentang Penyusun</span>
      </div>
      <el-skeleton v-if="isLoading" rows="10" animated />
      <template v-else-if="user !== null">
        <div class="user-image">
          <el-row>
            <el-col :span="8">
              <img :src="avatar" style="width: 150px; height:150px; background: #eee; border-radius: 500px;">
            </el-col>
            <el-col :span="16" style="padding-top: 2em; font-weight:bold; font-size:1.3em;">
              {{ user.name || null }}
            </el-col>
          </el-row>
        </div>
        <div class="user-detail">
          <el-row>
            <span class="label">Nama</span>
            <span class="value">{{ user.name || null }}</span>
          </el-row>
          <!-- <el-row>
            <span class="label">Nama LPJP</span>
            <span class="value">{{ user.expertise }}</span>
          </el-row> -->
          <el-row>
            <span v-if="user.cert_no !== null" class="label">No. Sertifikat</span>
            <span v-if="user.cert_no == null">
              <span class="value">{{ null }}</span>
            </span>
            <span v-else>
              <span class="value">{{ user.cert_no }}</span>
            </span>
          </el-row>
          <el-row>
            <span class="label">Email</span>
            <span class="value">{{ user.email || null }}</span>
          </el-row>
          <el-row>
            <span v-if="user.date_start !== null" class="label">Tanggal Ditetapkan</span>
            <span v-if="user.date_start == null">
              <span class="value">{{ null }}</span>
            </span>
            <span v-else>
              <span class="value">{{ user.date_start.split(' ')[0] }}</span>
            </span>
          </el-row>
          <el-row>
            <span v-if="user.date_end !== null" class="label">Berlaku Hingga</span>
            <span v-if="user.date_end == null">
              <span v-if="user.date_end == null " class="value">{{ null }}</span>
            </span>
            <span v-else>
              <span class="value">{{ user.date_end.split(' ')[0] }}</span>
            </span>
          </el-row>
        </div>
      </template>

    </el-card>
  </div>
</template>
<script>
export default {
  name: 'FormulatorInformation',
  props: {
    user: {
      type: Object,
      default: null,
    },
    avatar: {
      type: String,
      default: '',
    },
    isLoading: {
      type: Boolean,
      default: true,
    },
  },
  mounted() {
  },
};
</script>
<style scoped>
</style>
