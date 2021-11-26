<template>
  <el-form label-position="top" label-width="100px">
    <!-- ekologis -->
    <el-form-item label="Peta Batas Ekologis" :required="required">
      <el-col :span="10" style="margin-right:1em;">

        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi SHP {{ petaEkologisSHP }}</legend>
          <form @submit.prevent="handleSubmit">
            <input ref="peSHP" type="file" class="form-control-file" multiple @change="onChangeFiles(1)">
            <button type="submit">Unggah</button>
          </form>
        </fieldset>

      </el-col>
      <el-col :span="10" style="margin-right:1em;">
        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi PDF {{ petaEkologisPDF }}</legend>
          <form @submit.prevent="handleSubmit">
            <input ref="pePDF" type="file" class="form-control-file" multiple @change="onChangeFiles(2)">
            <button type="submit">Unggah</button>
          </form>
        </fieldset>
      </el-col>
    </el-form-item>

    <el-form-item label="Peta Batas Sosial" :required="required">
      <el-col :span="10" style="margin-right:1em;">
        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi SHP {{ petaSosialSHP }}</legend>

          <form @submit.prevent="handleSubmit">
            <input ref="psSHP" type="file" class="form-control-file" multiple @change="onChangeFiles(3)">
            <button type="submit">Unggah</button>
          </form>
        </fieldset>
      </el-col>

      <el-col :span="10" style="margin-right:1em;">
        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi PDF {{ petaSosialPDF }}</legend>

          <form @submit.prevent="handleSubmit">
            <input ref="psPDF" type="file" class="form-control-file" multiple @change="onChangeFiles(4)">
            <button type="submit">Unggah</button>
          </form>
        </fieldset>
      </el-col>
    </el-form-item>

    <el-form-item label="Peta Batas Wilayah Studi" :required="required">
      <el-col :span="10" style="margin-right:1em;">
        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi SHP {{ petaStudiSHP }}</legend>

          <form @submit.prevent="handleSubmit">
            <input ref="pwSHP" type="file" class="form-control-file" multiple @change="onChangeFiles(5)">
            <button type="submit">Unggah</button>
          </form>
        </fieldset>
      </el-col>

      <el-col :span="10" style="margin-right:1em;">
        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">Versi PDF {{ petaStudiPDF }}</legend>

          <form @submit.prevent="handleSubmit">
            <input ref="pwPDF" type="file" class="form-control-file" multiple @change="onChangeFiles(6)">
            <button type="submit">Unggah</button>
          </form>
        </fieldset>
      </el-col>

    </el-form-item>

    <!--
    <el-row style="margin: 1em 0;">
      <el-col :span="12">
        <el-button size="medium" type="warning">Unggah Peta</el-button>
      </el-col>
      <el-col :span="12" style="text-align: right;">
        <el-button size="medium" type="danger">Batal</el-button>
        <el-button size="medium" type="primary">Simpan</el-button>
      </el-col>
    </el-row> -->
  </el-form>

</template>
<style scoped>
 legend {line-height: 1.2em;}
</style>
<script>
import Resource from '@/api/resource';
import request from '@/utils/request';
const uploadMaps = new Resource('project-map');
const unggahMaps = new Resource('upload-map');

export default {
  name: 'UploadPetaBatas',
  data() {
    return {
      idProject: 0,
      currentMaps: [],
      petaEkologisPDF: '',
      petaSosialPDF: '',
      petaStudiPDF: '',
      petaEkologisSHP: '',
      petaSosialSHP: '',
      petaStudiSHP: '',
      files: '',
      index: 0,
      param: [],

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
      this.process(files.data);
    },
    process(files){
      console.log(files);
      files.forEach((e) => {
        switch (e.attachment_type){
          case 'ecology':
            if (e.file_type === 'SHP') {
              this.petaEkologisSHP = e.original_filename;
            } else {
              this.petaEkologisPDF = e.original_filename;
            }
            break;
          case 'social':
            if (e.file_type === 'SHP') {
              this.petaSosialSHP = e.original_filename;
            } else {
              this.petaSosialPDF = e.original_filename;
            }
            break;
          case 'study':
            if (e.file_type === 'SHP') {
              this.petaStudiSHP = e.original_filename;
            } else {
              this.petaStudiPDF = e.original_filename;
            }
            break;
        }
      });
    },
    uploadForm(fName){
      const myForm = document.getElementById(fName);
      var formData = new FormData(myForm);
      formData.append('id_project', this.idProject);
      // console.log(formData);

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
    handleSubmit(){
      console.log('submitting files....');
      console.log(this.files, this.param);

      const formData = new FormData();
      formData.append('id_project', this.idProject);
      formData.append('file', this.files[0]);
      Object.entries(this.param).forEach(([key, value]) => {
        formData.append(key, value);
      });
      /*
  		for(var pair of formData.entries()) {
    		console.log(pair[0]+ ', '+ pair[1]);
   		}

     request({
        url: '/api/upload-map',
        method: 'post',
        data: formData,
        //params: { _method: 'PUT' },
      }).then((r) => console.log(r));*/
      unggahMaps.store(formData).then(console.log);

      // console.log(res);
    },
    async doSubmit(load){
      console.log(load);
      return request(load);
    },
    onChangeFiles(index){
      this.files = '';
      this.index = index;
      this.param = [];
      switch (this.index) {
        case 1: // ekologis SHP
          this.files = this.$refs.peSHP.files;
          this.param['attachment_type'] = 'ecology';
          this.param['file_type'] = 'SHP';
          break;
        case 2:
          // ekologis PDF
          this.files = this.$refs.pePDF.files;
          this.param['attachment_type'] = 'ecology';
          this.param['file_type'] = 'PDF';
          break;
        case 3:
          this.files = this.$refs.psSHP.files;
          this.param['attachment_type'] = 'social';
          this.param['file_type'] = 'SHP';
          // sosial SHP
          break;
        case 4:
          this.files = this.$refs.psPDF.files;
          this.param['attachment_type'] = 'social';
          this.param['file_type'] = 'PDF';

          // sosial PDF
          break;
        case 5:
          this.files = this.$refs.pwSHP.files;
          this.param['attachment_type'] = 'study';
          this.param['file_type'] = 'SHP';
          // studi SHP
          break;
        case 6:
          this.files = this.$refs.pwPDF.files;
          this.param['attachment_type'] = 'study';
          this.param['file_type'] = 'PDF';
          // studi PDF
          break;
        default:
      }
    },
    handleUpload(file, fileList, index){
      // console.log([file, fileList, index]);

    },
  },
};
</script>
