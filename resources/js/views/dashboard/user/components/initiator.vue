<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Tentang Pemrakarsa</span>
      </div>
      <el-skeleton v-if="isLoading" rows="10" animated />
      <template v-else-if="user !== null">
        <div class="user-image">
          <el-row>
            <el-col :span="8">
              <img :src="avatar" style="width: 150px; height:150px; background: #eee; border-radius: 500px;">
            </el-col>
            <el-col :span="16" style="padding-top: 2em; font-weight:bold; font-size:1.3em;">
              {{ user.name }}
            </el-col>
          </el-row>
        </div>
        <div class="user-detail">
          <el-row>
            <span class="label">Nama</span>
            <span class="value">{{ user.name }}</span>
          </el-row>
          <el-row>
            <span class="label">Penanggung Jawab</span>
            <span class="value">{{ user.pic }}</span>
          </el-row>
          <el-row>
            <span class="label">Alamat</span>
            <span class="value">{{ user.address }}</span>
          </el-row>
          <el-row>
            <span class="label">No. Telpon</span>
            <span class="value">{{ user.phone }}</span>
          </el-row>
          <el-row>
            <span class="label">Email</span>
            <span class="value">{{ user.email }}</span>
          </el-row>
          <!--
          <el-row>
            <span class="label">Email</span>
            <span class="value">{{ user.email }}</span>
          </el-row>
          -->
        </div>
      </template>

    </el-card>
  </div>
</template>
<script>
export default {
  name: 'InitiatorInformation',
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
};
</script>
