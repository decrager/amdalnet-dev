<template>
  <div>
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
          <div class="email-column">
            <span style="display: block">{{ scope.row.email }}</span>
            <el-button
              type="text"
              icon="el-icon-close"
              style="display: block"
              @click.prevent="deleteRow(scope.row.id)"
            />
          </div>
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
  name: 'DaftarHadir',
  data() {
    return {
      daftarUndangan: [
        {
          id: 1,
          peran: 'Ketua TUK',
          namaAnggota: 'Ketua TUK',
          email: 'ketuatuk@gmail.com',
        },
        {
          id: 2,
          peran: 'Sekretaris TUK',
          namaAnggota: 'Sekretaris TUK',
          email: 'sekretaristuk@gmail.com',
        },
        {
          id: 3,
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
        id:
          this.peran.length > 0 ? this.peran[this.peran.length - 1].id + 1 : 1,
        peran: '',
        namaAnggota: '',
        email: '',
      });
    },
    deleteRow(id) {
      const newInvitation = this.daftarUndangan.filter((daf) => daf.id !== id);
      this.daftarUndangan = [...newInvitation];
    },
  },
};
</script>

<style scoped>
.email-column {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
</style>
