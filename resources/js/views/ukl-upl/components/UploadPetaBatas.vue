<template>
  <el-form label-position="top" label-width="100px">
    <el-form-item label="Peta Batas Ekologis" :required="required">
      <el-col :span="6" style="margin-right:1em;">
        <el-row :gutter="5" style="border:1px solid #aaaaaa; border-radius: 0.3em; width:100%; padding: .5em;">
          <el-col :span="17"><el-input placeholder="Versi SHP" /></el-col>
          <el-col :span="4" style="margin-left:1em;">
            <el-upload class="upload-demo">
              <el-button size="small" type="info">browse</el-button>
            </el-upload>
          </el-col>
        </el-row>
      </el-col>

      <el-col :span="6" style="margin-right:1em;">
        <el-row :gutter="5" style="border:1px solid #aaaaaa; border-radius: 0.3em; width:100%; padding: .5em;">
          <el-col :span="17"><el-input placeholder="Versi PDF" /></el-col>
          <el-col :span="4" style="margin-left:1em;">
            <el-upload>
              <el-button size="small" type="info">browse</el-button>
            </el-upload>
          </el-col>
        </el-row>
      </el-col>
    </el-form-item>
    <el-form-item label="Peta Batas Sosial" :required="required">
      <el-col :span="6" style="margin-right:1em;">
        <el-row :gutter="5" style="border:1px solid #aaaaaa; border-radius: 0.3em; width:100%; padding: .5em;">
          <el-col :span="17"><el-input placeholder="Versi SHP" /></el-col>
          <el-col :span="4" style="margin-left:1em;">
            <el-upload>
              <el-button size="small" type="info">browse</el-button>
            </el-upload>
          </el-col>
        </el-row>
      </el-col>

      <el-col :span="6" style="margin-right:1em;">
        <el-row :gutter="5" style="border:1px solid #aaaaaa; border-radius: 0.3em; width:100%; padding: .5em;">
          <el-col :span="17"><el-input placeholder="Versi PDF" /></el-col>
          <el-col :span="4" style="margin-left:1em;">
            <el-upload>
              <el-button size="small" type="info">browse</el-button>
            </el-upload>
          </el-col>
        </el-row>
      </el-col>
    </el-form-item>

    <el-form-item label="Peta Batas Wilayah Studi" :required="required">
      <el-col :span="6" style="margin-right:1em;">
        <el-row :gutter="5" style="border:1px solid #aaaaaa; border-radius: 0.3em; width:100%; padding: .5em;">
          <el-col :span="17"><el-input placeholder="Versi SHP" /></el-col>
          <el-col :span="4" style="margin-left:1em;">
            <el-upload>
              <el-button size="small" type="info">browse</el-button>
            </el-upload>
          </el-col>
        </el-row>
      </el-col>

      <el-col :span="6" style="margin-right:1em;">
        <el-row :gutter="5" style="border:1px solid #aaaaaa; border-radius: 0.3em; width:100%; padding: .5em;">
          <el-col :span="17"><el-input placeholder="Versi PDF" /></el-col>
          <el-col :span="4" style="margin-left:1em;">
            <el-upload>
              <el-button size="small" type="info">browse</el-button>
            </el-upload>
          </el-col>
        </el-row>
      </el-col>
    </el-form-item>

    <el-row style="margin: 1em 0;">
      <el-col :span="12">
        <el-button size="medium" type="warning">Unggah Peta</el-button>
      </el-col>
      <el-col :span="12" style="text-align: right;">
        <el-button size="medium" type="danger">Batal</el-button>
        <el-button size="medium" type="primary">Simpan</el-button>
      </el-col>
    </el-row>
  </el-form>
</template>
<script>
import Resource from '@/api/resource';
const uploadMaps = new Resource('project-map');

export default {
  name: 'UploadPetaBatas',
  data() {
    return {
      idProject: 0,
      currentMaps: [],
    };
  },
  mounted() {
    this.idProject = parseInt(this.$route.params && this.$route.params.id);
    this.getData();
  },
  methods: {
    async getData(){
      const files = await uploadMaps.list({
        id_project: this.idProject,
      });

      console.log('upload maps');
      console.log(files);
      this.process(files);
    },
    process(files){
      // const data = [];
      files.map((pma) => {

      });
    },
  },
};
</script>
