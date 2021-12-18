<template>
  <div>
    <el-form label-position="top" label-width="100px">
      <el-form-item label="Surat Pernyataan Pengelolaan Lingkungan Hidup" :required="required">
        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">
            <!-- <div v-if="petaEkologisSHP != ''" class="current">tersimpan: <span style="color: green" @click="download(idPES)"><strong>{{ petaEkologisSHP }}<i class="el-icon-circle-check" /></strong></span>
         &nbsp;<i class="el-icon-delete"></i>
        </div>-->
          </legend>
          <form @submit.prevent="handleSubmit">
            <input ref="spplh" type="file" class="form-control-file" @change="onChangeFiles()">
            <!-- <button type="submit">Unggah</button> -->
          </form>
        </fieldset>
      </el-form-item>
      <el-form-item label="Dokumen Persetujuan Teknis" :required="required">
        <fieldset style="border:1px solid #e0e0e0; border-radius: 0.3em; width:100%; padding: .5em;">
          <legend style="margin:0 2em;">
            <!-- <div v-if="petaEkologisSHP != ''" class="current">tersimpan: <span style="color: green" @click="download(idPES)"><strong>{{ petaEkologisSHP }}<i class="el-icon-circle-check" /></strong></span>
         &nbsp;<i class="el-icon-delete"></i>
        </div>-->
          </legend>
          <form @submit.prevent="handleSubmit">
            <input ref="dpt" type="file" class="form-control-file" @change="onChangeFiles()">
            <!-- <button type="submit">Unggah</button> -->
          </form>
        </fieldset>
      </el-form-item>
    </el-form>
  </div>
</template>
<script>
export default {
  name: 'DokumenPendukung',
  data() {
    return {
      required: true,
    };
  },
  methods: {
    onChangeFile(){

    },
    handleSubmit(){

    },
  },
};
</script>
