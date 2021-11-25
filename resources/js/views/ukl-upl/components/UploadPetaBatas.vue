<template>
  <el-form label-position="top" label-width="100px">
    <!-- ekologis -->
    <el-form-item label="Peta Batas Ekologis" :required="required">
      <el-col :span="10" style="margin-right:1em;">
        <form id="fEkologis" enctype="multipart/form-data">
          <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
            <legend style="margin:0 2em;">Versi SHP</legend>

            <el-col :span="4" style="margin-left:1em;">
              <input data-map-type="ekologis" data-file-type="shp" type="file" name="petaEkologisSHP">
              <input type="hidden" name="attachment_type" value="ekologis">
              <input type="hidden" name="file_type" value="shp">

            </el-col>
          </fieldset>
        </form> <button @click="uploadForm('fEkologisSHP')">Unggah</button>
      </el-col>
      <el-col :span="10" style="margin-right:1em;">
        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi PDF</legend>

          <el-col :span="4" style="margin-left:1em;">
            <input ref="maps[1]" data-map-type="ekologis" data-file-type="pdf" type="file" placeholder="Versi PDF" accept="application/pdf" @change="writeFileName($event)">
          </el-col>
        </fieldset>
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
// const unggahMaps = new Resource('upload-map');

export default {
  name: 'UploadPetaBatas',
  data() {
    return {
      idProject: 0,
      currentMaps: [],
      petaEkologisPDF: null,
      petaSosialPDF: null,
      petaStudiPDF: null,
      petaEkologisSHP: null,
      petaSosialSHP: null,
      petaStudiSHP: null,

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
      this.process(files);
    },
    process(files){
      // const data = [];
      files.map((pma) => {

      });
    },
    writeFileName(event){
      console.log(this.petaEkologisPDF);
    },
    uploadForm(fName){
      const myForm = document.getElementById(fName);
      var formData = new FormData(myForm);
      formData.append('id_project', this.idProject);
      console.log(formData);

      /*
      // add file to multipart
      const formData = new FormData();
      formData.append('file', file.raw);

      userResource
        .updateMultipart(this.user.id, formData)
        .then(response => {
          this.updating = false;
          this.$message({
            message: 'Berhasil Mengganti Gambar Profil',
            type: 'success',
            duration: 5 * 1000,
          });
        })
        .catch(error => {
          console.log(error);
          this.updating = false;
        });    */
    },
  },
};
</script>
