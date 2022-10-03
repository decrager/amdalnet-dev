<template>
  <div class="form-container" style="margin: 24px">
    <el-card>
      <h3>Daftar Tim Penyusun Kegiatan</h3>
      <div role="alert" class="el-alert el-alert--info is-light" style="margin-top: 10px; margin-bottom: 10px">
        <div class="el-alert__content">
          <p class="el-alert__description">
            <b>Penyusunan UKL-UPL:</b>
            <ul>
              <li>Dapat dilakukan oleh pemrakarsa secara mandiri</li>
              <li>Setiap penyusunan UKL-UPL (termasuk apabila dilakukan oleh pemrakasa sendiri), wajib membuat akun penyusun di amdalnet melalui <span style="color: blue" @click="logout">link</span> berikut</li>
              <li>Apabila penyusun sudah memiliki akun, silahkan cari nama penyusun pada tombol pencarian dibawah</li>
              <li>Untuk penyusunan UKL-UPL tidak harus dari KTPA atau ATPA</li>
            </ul>
          </p>
        </div>
      </div>
      <el-row :gutter="32">
        <el-col :sm="24" :md="12">
          <el-row :gutter="32">
            <el-col :sm="12" :md="20">
              <el-select
                v-model="selectedFormulator"
                filterable
                remote
                reserve-keyword
                placeholder="Cari Penyusun..."
                :remote-method="remoteMethod"
                :loading="loadingSearchFormulator"
                style="width: 100%"
              >
                <el-option
                  v-for="item in formulators"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-col>
            <el-col :sm="12" :md="2">
              <el-button type="primary" @click.prevent="handleAdd">
                Tambahkan
              </el-button>
            </el-col>
          </el-row>
        </el-col>
        <!-- <el-col :sm="24" :md="12" align="right">
          <el-button type="primary" @click="handleAddNew">
            Tambah Baru
          </el-button>
        </el-col> -->
      </el-row>
      <el-row :gutter="32" style="margin-top: 20px">
        <el-col :sm="24" :md="24">
          <el-table
            v-loading="loading"
            :data="members"
            fit
            :header-cell-style="{ background: '#3AB06F', color: 'white' }"
          >
            <el-table-column label="No." width="54px" align="center">
              <template slot-scope="scope">
                <span>{{ scope.$index + 1 }}</span>
              </template>
            </el-table-column>

            <el-table-column label="Nama" align="center">
              <template slot-scope="scope">
                <span>{{ scope.row.name }}</span>
              </template>
            </el-table-column>

            <el-table-column label="Email" align="center">
              <template slot-scope="scope">
                <span>{{ scope.row.email }}</span>
              </template>
            </el-table-column>

            <el-table-column label="Nomor Registrasi" align="center">
              <template slot-scope="scope">
                <span>{{ scope.row.reg_no }}</span>
              </template>
            </el-table-column>

            <el-table-column label="Sertifikat" align="center">
              <template slot-scope="scope">
                <span>{{ scope.row.membership_status }}</span>
              </template>
            </el-table-column>

            <el-table-column label="Keahlian" align="center">
              <template slot-scope="scope">
                <span>{{ scope.row.expertise }}</span>
              </template>
            </el-table-column>

            <el-table-column label="File" align="center">
              <template slot-scope="scope">
                <el-button
                  v-if="scope.row.cv_file"
                  type="text"
                  size="medium"
                  icon="el-icon-download"
                  style="color: blue"
                  @click.prevent="download(scope.row.cv_file)"
                >
                  CV
                </el-button>
                <el-button
                  v-if="scope.row.cert_file"
                  type="text"
                  size="medium"
                  icon="el-icon-download"
                  style="color: blue"
                  @click.prevent="download(scope.row.cert_file)"
                >
                  Sertifikat
                </el-button>
              </template>
            </el-table-column>

            <el-table-column label="" width="80px" align="center">
              <template slot-scope="scope">
                <el-button
                  type="danger"
                  size="mini"
                  icon="el-icon-close"
                  @click.prevent="
                    handleDelete(scope.row.id, scope.row.id_formulator)
                  "
                />
              </template>
            </el-table-column>
          </el-table>
        </el-col>
        <el-col :sm="24" :md="24" align="right" style="margin-top: 15px">
          <el-button
            :loading="loadingSubmit || loading"
            type="warning"
            @click="handleSubmit"
          >
            Simpan
          </el-button>
        </el-col>
      </el-row>
    </el-card>
  </div>
</template>

<script>
import Resource from '@/api/resource';
const formulatorResource = new Resource('formulators');
const formulatorTeamsResource = new Resource('formulator-teams');

export default {
  data() {
    return {
      formulators: [],
      members: [],
      selectedFormulator: {},
      loading: false,
      loadingSubmit: false,
      loadingSearchFormulator: false,
      deletedMembers: [],
    };
  },
  created() {
    this.getTimPenyusun();
  },
  methods: {
    async getTimPenyusun() {
      this.loading = true;
      const timPenyusun = await formulatorTeamsResource.list({
        type: 'tim-penyusun',
        idProject: this.$route.params.id,
      });
      this.members = timPenyusun;
      this.loading = false;
    },
    handleAdd() {
      if (this.selectedFormulator) {
        const members = this.formulators.find(
          (x) => x.id === this.selectedFormulator
        );
        this.members.push({
          name: members.name,
          id: null,
          id_formulator: members.id,
          expertise: members.expertise,
          cv_file: members.cv_file,
          email: members.email,
          reg_no: members.reg_no,
          membership_status: members.membership_status,
          cert_file: members.cert_file,
        });
        this.formulators = [];
        this.selectedFormulator = null;
      }
    },
    async remoteMethod(query) {
      if (query !== '') {
        this.loadingSearchFormulator = true;
        const formulators = await formulatorResource.list({
          uklUpl: 'true',
          search: query,
        });

        const idExist = this.members.map((x) => x.id_formulator);
        if (idExist.length > 0) {
          this.formulators = formulators.filter((x) => {
            return !idExist.includes(x.id);
          });
        } else {
          this.formulators = formulators;
        }

        this.loadingSearchFormulator = false;
      } else {
        this.formulators = [];
      }
    },
    handleDelete(id, id_formulator) {
      this.members = this.members.filter(
        (x) => x.id_formulator !== id_formulator
      );

      if (id) {
        this.deletedMembers.push(id);
      }
    },
    async handleSubmit() {
      this.loadingSubmit = true;

      const members = this.members
        .filter((x) => x.id === null)
        .map((y) => y.id_formulator);

      await formulatorTeamsResource.store({
        type: 'uklupl',
        idProject: this.$route.params.id,
        members,
        deletedMembers: this.deletedMembers,
      });

      this.$message({
        message: 'Data berhasil disimpan',
        type: 'success',
        duration: 5 * 1000,
      });

      await this.getTimPenyusun();

      this.loadingSubmit = false;
    },
    logout() {
      this.$store.dispatch('user/logout').then(() => {
        // this.$store.dispatch('user/setPage', 'register')
        this.$router.push({ path: '/login' });
      });
    },
    download(url) {
      window.open(url, '_blank').focus();
    },
    // handleAddNew() {
    //   this.$router.push({
    //     name: 'createPenyusunByInitiator',
    //     params: { id: this.$route.params.id },
    //   });
    // },
  },
};
</script>
