<template>
  <el-button
    type="warning"
    size="small"
    icon="el-icon-download"
    @click="handleDownload()"
  >
    Unduh
  </el-button>
</template>

<script>
import Resource from '@/api/resource';
import axios from 'axios';
const skklResource = new Resource('skkl');

export default {
  name: 'SkklFinal',
  data() {
    return {
      loading: false,
      fileUrl: null,
      userKey: null,
      idProject: this.$route.params.id,
    };
  },
  created() {
    this.getData();
  },
  methods: {
    async getData() {
      const data = await skklResource.list({
        idProject: this.idProject,
        skklOss: 'true',
      });
      console.log('data = ' + JSON.stringify(data));
      if ('file_url' in data && 'user_key' in data) {
        this.fileUrl = data.file_url;
        this.userKey = data.user_key;
      }
    },
    async handleDownload() {
      if (this.fileUrl !== null && this.userKey !== null) {
        axios({
          url: this.fileUrl,
          method: 'GET',
          responseType: 'blob',
          headers: {
            user_key: this.userKey,
          },
        }).then((response) => {
          console.log('response.data: ' + JSON.stringify(response.data));
          console.log('response.headers: ' + JSON.stringify(response.headers));
          const cd = response.headers['content-disposition'];
          console.log('cd = ' + cd);
          const fileName = cd.split('filename=')[1].replaceAll('"', '');
          console.log('fileName = ' + fileName);
          var fileURL = window.URL.createObjectURL(new Blob([response.data]));
          var fileLink = document.createElement('a');
          fileLink.href = fileURL;
          fileLink.setAttribute(
            'download',
            `${fileName}`
          );
          document.body.appendChild(fileLink);
          fileLink.click();
          this.loading = false;
        });
      } else {
        this.$message({
          message: 'URL file tidak ada.',
          type: 'error',
          duration: 5 * 1000,
        });
      }
    },
  },
};
</script>
