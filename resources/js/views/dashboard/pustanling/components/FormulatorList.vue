<template>
  <div>
    <el-input
      v-model="search"
      suffix-icon="el-icon search"
      placeholder="Cari Penyusun..."
      style="margin-bottom: 0.8em"
      @input="inputSearch"
    >
      <el-button slot="append" icon="el-icon-search" @click="handleSearch" />
    </el-input>
    <el-card class="box-card formulir-list">
      <div slot="header" class="clearfix">
        <span>Daftar Penyusun</span>
      </div>
      <div v-loading="loading" class="user-summary-cards formulir-list">
        <el-card v-for="formulator in data" :key="formulator.id">
          <el-row :gutter="32">
            <!-- <el-col :md="16" :sm="16"> -->
            <el-col :md="24" :sm="24">
              <div style="display: flex">
                <div style="margin-right: 15px">
                  <el-upload
                    class="avatar-uploader"
                    action="#"
                    :disabled="true"
                  >
                    <!-- eslint-disable-next-line vue/html-self-closing -->
                    <img
                      :src="
                        formulator.user !== null
                          ? formulator.user.avatar
                          : 'no-avatar.png'
                      "
                      class="avatar"
                    />
                  </el-upload>
                </div>
                <div style="display: flex">
                  <div style="margin-right: 10px">
                    <h4 style="margin-top: 5px; margin-bottom: 0">
                      {{ formulator.name }}
                    </h4>
                    <p style="font-size: 0.7em; margin-top: 15px">
                      <b>{{
                        formulator.lpjp !== null ? formulator.lpjp.name : '-'
                      }}</b>
                    </p>
                    <p style="font-size: 0.7em; margin-top: 15px">
                      <b>{{ formulator.membership_status }}</b>
                    </p>
                  </div>
                  <div>
                    <el-button
                      :type="formulator.status | statusFilter"
                      round
                      size="mini"
                      style="cursor: default"
                    >
                      {{ formulator.status }}
                    </el-button>
                  </div>
                </div>
              </div>
            </el-col>
            <!-- <el-col :md="8" :sm="8" align="right">
              <el-button type="primary">Profil</el-button>
            </el-col> -->
          </el-row>
        </el-card>
      </div>
    </el-card>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'FormulatorList',
  filters: {
    statusFilter(status) {
      const statusMap = {
        Aktif: 'success',
        NonAktif: 'danger',
      };
      return statusMap[status];
    },
  },
  data() {
    return {
      data: [],
      search: '',
      loading: false,
      timeoutId: null,
      offset: 0,
    };
  },
  created() {
    this.getData();
  },
  mounted() {
    // Detect when scrolled to bottom.
    const listElm = document.querySelector(
      '.box-card.formulir-list > .el-card__body'
    );
    listElm.addEventListener('scroll', (e) => {
      if (listElm.scrollTop + listElm.clientHeight >= listElm.scrollHeight) {
        this.offset += 10;
        this.getData();
      }
    });
  },
  methods: {
    getData() {
      this.loading = true;
      axios
        .get(
          `/api/dashboard/formulator?offset=${this.offset}&search=${this.search}`
        )
        .then((data) => {
          const list = data.data.map((x) => {
            x.membership_status = x.membership_status
              ? x.membership_status
              : '-';
            x.status = this.calculateStatus(x.date_start, x.date_end);
            return x;
          });

          if (this.offset === 0) {
            this.data = list;
          } else {
            this.data = [...this.data, ...list];
          }

          this.loading = false;
        })
        // eslint-disable-next-line handle-callback-err
        .catch((err) => {
          this.loading = false;
        });
    },
    calculateStatus(awal, akhir) {
      const tglAwal = new Date(awal);
      const tglAkhir = new Date(akhir);
      if (
        new Date().getTime() >= tglAwal.getTime() &&
        new Date().getTime() <= tglAkhir.getTime()
      ) {
        return 'Aktif';
      } else {
        return 'NonAktif';
      }
    },
    async handleSearch() {
      this.offset = 0;
      await this.getData();
      const listElm = document.querySelector(
        '.box-card.formulir-list > .el-card__body'
      );
      listElm.scrollTo({ top: 0 });
      this.search = '';
    },
    inputSearch(val) {
      this.offset = 0;
      if (this.timeoutId) {
        clearTimeout(this.timeoutId);
      }
      // eslint-disable-next-line space-before-function-paren
      this.timeoutId = setTimeout(async () => {
        await this.getData();
        const listElm = document.querySelector(
          '.box-card.formulir-list > .el-card__body'
        );
        listElm.scrollTo({ top: 0 });
      }, 500);
    },
  },
};
</script>

<style lang="scss" scoped>
.user-summary-cards {
  .el-card {
    height: 130px;
    margin: 0 0 15px 0;
    padding: 0;
    overflow: hidden;
    overflow: -moz-hidden-unscrollable;
    border-radius: 22px;

    span {
      display: block;
      text-align: center;
    }

    .title {
      font-weight: bold;
    }
    .value {
      margin-top: 0.8em;
      font-size: 250%;
    }
    .smaller {
      font-size: 86%;
    }
  }
}
</style>
