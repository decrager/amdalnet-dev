<template>
  <el-form label-position="top" label-width="200px" style="max-width: 100%">
    <el-row :gutter="32">
      <el-col :sm="12" :md="12">
        <el-form-item label="Tanggal Rapat">
          <el-date-picker
            v-model="invitation.tanggalRapat"
            type="date"
            placeholder="Pilih tanggal"
            style="width: 100%"
          />
        </el-form-item>
      </el-col>
      <el-col :sm="12" :md="12">
        <el-form-item label="Pemrakarsa">
          <el-input v-model="invitation.Pemrakarsa" />
        </el-form-item>
      </el-col>
      <el-col :sm="12" :md="12">
        <el-form-item label="Waktu Rapat">
          <el-time-picker
            v-model="invitation.waktuRapat"
            placeholder="Waktu Rapat"
            format="HH:mm"
            style="width: 100%"
          />
        </el-form-item>
      </el-col>
      <el-col :sm="12" :md="12">
        <el-form-item label="Penanggung Jawab">
          <el-input v-model="invitation.penanggungJawab" />
        </el-form-item>
      </el-col>
      <el-col :sm="12" :md="12">
        <el-form-item label="Tempat Rapat">
          <el-input v-model="invitation.tempatRapat" />
        </el-form-item>
      </el-col>
      <el-col :sm="12" :md="12">
        <el-form-item label="Jabatan">
          <el-input v-model="invitation.jabatan" />
        </el-form-item>
      </el-col>
      <el-col :sm="12" :md="12">
        <el-form-item label="Tim Uji Kelayakan">
          <el-select
            v-model="invitation.timUjiKelayakan"
            placeholder="Pilih Tim"
            style="width: 100%"
          >
            <el-option
              v-for="item in timUjiKelayakan"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
      </el-col>
      <el-col :sm="12" :md="12">
        <el-form-item label="Nama Usaha/Kegiatan">
          <el-input v-model="invitation.namaUsaha" />
        </el-form-item>
      </el-col>
    </el-row>
  </el-form>
</template>

<script>
export default {
  name: 'FormBerita',
  data() {
    return {
      invitation: {
        waktuRapat: new Date(),
      },
      timUjiKelayakan: [
        {
          label: 'TUK Pusat 1',
          value: 1,
        },
        {
          label: 'TUK Pusat 2',
          value: 2,
        },
      ],
    };
  },
};
</script>
