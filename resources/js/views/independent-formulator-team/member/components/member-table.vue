<template>
  <el-table
    :data="list"
    fit
    highlight-current-row
    :header-cell-style="{ background: '#3AB06F', color: 'white' }"
  >
    <el-table-column label="No." width="54px">
      <template slot-scope="scope">
        <span>{{ scope.$index + 1 }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Nama">
      <template slot-scope="scope">
        <span>{{ scope.row.name }}</span>
      </template>
    </el-table-column>

    <el-table-column label="Jabatan">
      <template slot-scope="scope">
        <el-select
          v-model="scope.row.position"
          placeholder="Pilih Jabatan"
          style="width: 100%"
        >
          <el-option
            v-for="item in position"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </template>
    </el-table-column>

    <el-table-column label="Keahlian">
      <template slot-scope="scope">
        <span>{{ scope.row.expertise }}</span>
      </template>
    </el-table-column>

    <el-table-column label="File">
      <template slot-scope="scope">
        <el-button
          type="text"
          size="medium"
          icon="el-icon-download"
          style="color: blue"
          @click.prevent="download(scope.row.file)"
        >
          CV
        </el-button>
      </template>
    </el-table-column>

    <el-table-column label="Aksi">
      <template slot-scope="scope">
        <template>
          <el-button
            type="text"
            href="#"
            icon="el-icon-delete"
            @click.prevent="handleDelete(scope.row.num)"
          >
            Hapus
          </el-button>
        </template>
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
export default {
  name: 'MembersTable',
  props: {
    list: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      position: [
        {
          value: 'Ketua',
          label: 'Ketua',
        },
        {
          value: 'Anggota',
          label: 'Anggota',
        },
      ],
    };
  },
  methods: {
    download(url) {
      window.open(url, '_blank').focus();
    },
    handleDelete(num) {
      this.$emit('handleDelete', { num });
    },
  },
};
</script>
