<template>
  <div>
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Tentang {{ user.role }}</span>
      </div>
      <div class="user-detail">
        <el-skeleton :rows="10" animated />
      </div>
    </el-card>
  </div>
</template>
<script>
export default {
  name: 'UserInformation',
  data() {
    return {
      user: {
        role: 'Penyusun',
        data: [
          { label: 'Nama Penyusun', value: 'Budi Hanafi' },
          { label: 'Nama LPJP', value: 'PT EOS Consultants' },
          { label: 'No. Sertifikat', value: '1230123400987' },
          { label: 'Email', value: 'penyusun1@amdalnet.dev' },
          { label: 'Tanggal Ditetapkan', value: '2021-11-15' },
          { label: 'Tanggal Berlaku', value: '2021-11-30' },
        ],
      },
    };
  },
};
</script>
<style scoped>

div.user-detail{
  padding: 3em;
}

</style>
