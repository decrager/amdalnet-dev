<template>
  <el-card class="box-card" style="min-height: 395px">
    <div slot="header" class="clearfix">
      <span>Tentang LPJP</span>
    </div>
    <el-skeleton v-if="isLoading" :rows="10" animated />
    <template v-else-if="user !== null">
      <div class="user-image">
        <el-row>
          <el-col :span="8">
            <!-- eslint-disable-next-line vue/html-self-closing -->
            <img
              :src="avatar"
              style="
                width: 150px;
                height: 150px;
                background: #eee;
                border-radius: 500px;
              "
            />
          </el-col>
          <el-col :span="16" style="padding-top: 2em">
            <div
              style="
                font-size: 1.5em;
                font-weight: bold;
                border-bottom: 1px solid #999;
              "
            >
              {{ user.name }}
            </div>
          </el-col>
        </el-row>
      </div>
      <div class="user-detail">
        <el-row>
          <span class="label">Nama LPJP</span>
          <span class="value">{{ user.name }}</span>
        </el-row>
        <el-row>
          <span class="label">Nama Ketua</span>
          <span class="value">{{ user.pic }}</span>
        </el-row>
        <el-row>
          <span class="label">Alamat</span>
          <span class="value">{{ user.address }}</span>
        </el-row>
        <el-row>
          <span class="label">Email</span>
          <span class="value">{{ user.email }}</span>
        </el-row>

        <el-row>
          <span class="label">No Registrasi</span>
          <span class="value">{{ user.reg_no }}</span>
        </el-row>
        <el-row>
          <span class="label">Tanggal Berlaku</span>
          <span class="value">
            {{ user.date_start.split(' ')[0] }} &mdash;
            {{ user.date_end.split(' ')[0] }}
          </span>
        </el-row>
      </div>
    </template>
  </el-card>
</template>
<script>
export default {
  name: 'LpjpInformation',
  props: {
    user: {
      type: Object,
      default: () => {},
    },
    isLoading: {
      type: Boolean,
      default: true,
    },
    avatar: {
      type: String,
      default: '',
    },
  },
};
</script>
