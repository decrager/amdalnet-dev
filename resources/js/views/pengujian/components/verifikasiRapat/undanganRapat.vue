<template>
  <div>
    <h4 align="center">UNDANGAN RAPAT</h4>
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
    <h5>Daftar Undangan</h5>
    <el-table
      :data="daftarUndangan"
      fit
      highlight-current-row
      :header-cell-style="{ background: '#3AB06F', color: 'white' }"
    >
      <el-table-column width="30px">
        <template slot-scope="scope">
          <span>{{ scope.$index + 1 }}</span>
        </template>
      </el-table-column>

      <el-table-column label="Peran">
        <template slot-scope="scope">
          <el-select
            v-model="scope.row.peran"
            placeholder="Pilih Kelengkapan"
            style="width: 100%"
          >
            <el-option
              v-for="item in peran"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </template>
      </el-table-column>

      <el-table-column label="Nama Anggota">
        <template slot-scope="scope">
          <el-input v-model="scope.row.namaAnggota" />
        </template>
      </el-table-column>

      <el-table-column label="E-mail">
        <template slot-scope="scope">
          <span>{{ scope.row.email }}</span>
        </template>
      </el-table-column>
    </el-table>
    <div style="margin-top: 10px">
      <el-button icon="el-icon-plus" circle @click.prevent="addTableRow" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'UndanganRapat',
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
      daftarUndangan: [
        {
          peran: 'Ketua TUK',
          namaAnggota: 'Ketua TUK',
          email: 'ketuatuk@gmail.com',
        },
        {
          peran: 'Sekretaris TUK',
          namaAnggota: 'Sekretaris TUK',
          email: 'sekretaristuk@gmail.com',
        },
        {
          peran: 'Anggota TUK',
          namaAnggota: 'Anggota TUK',
          email: 'anggotatuk@gmail.com',
        },
      ],
      peran: [
        {
          label: 'Ketua TUK',
          value: 'Ketua TUK',
        },
        {
          label: 'Sekretaris TUK',
          value: 'Sekretaris TUK',
        },
        {
          label: 'Anggota TUK',
          value: 'Anggota TUK',
        },
        {
          label: 'Tenaga Ahli',
          value: 'Masyarakat',
        },
      ],
    };
  },
  methods: {
    addTableRow() {
      this.daftarUndangan.push({
        peran: '',
        namaAnggota: '',
        email: '',
      });
    },
  },
};
</script>
