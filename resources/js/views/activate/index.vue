<template>
  <div>
    <el-row type="flex" justify="center">
      <!-- <el-col :span="8" /> -->
      <el-col :span="8">
        <h1>Selamat Akun Anda Sudah Aktif</h1>
        <h3>Anda akan diarahkan Ke halaman utama dalam beberapa saat....</h3>
      </el-col>
      <!-- <el-col :span="8" /> -->
    </el-row>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const userResource = new Resource('activateUser');

export default {
  name: 'Activate',
  async created() {
    await this.activateUser();
  },
  methods: {
    async activateUser() {
      await userResource
        .updateMultipart(this.$route.params.id, {})
        .then((response) => {
          setTimeout(this.$router.push('/login'), 5000);
        })
        .catch((error) => {
          console.log(error);
          this.$router.push('/login');
        });
    },
  },
};
</script>
