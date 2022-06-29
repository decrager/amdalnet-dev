<template>
  <div style="margin-bottom: 20px">
    <div class="filter-container">
      <el-row type="flex" class="row-bg" justify="space-between">
        <el-button
          v-if="isInitiator"
          class="filter-item"
          type="primary"
          icon="el-icon-plus"
          @click="showCreateFeedback()"
        >
          Tambah SPT Baru
        </el-button>
      </el-row>
    </div>
    <el-table
      :key="showFeedback"
      v-loading="loading"
      :data="feedbacks"
      fit
      highlight-current-row
      :header-cell-style="{ background: '#3AB06F', color: 'white' }"
    >
      <el-table-column type="expand" class="row-detail">
        <template slot-scope="scope">
          <div class="post">
            <div class="entity-block" />
            <span class="action pull-right">
              <el-button
                href="#"
                type="text"
                icon="el-icon-view"
                @click="showIdentity(scope.row.id)"
              >
                View Identity
              </el-button>
            </span>
            <p><b>Kekhawatiran:</b></p>
            <div class="description" v-html="scope.row.concern" />
            <p style="margin-top: 10px"><b>Harapan:</b></p>
            <div class="description" v-html="scope.row.expectation" />
            <p v-if="!isInitiator" style="margin-top: 10px">
              <b>
                Kondisi Lingkungan di Dalam dan Sekitar Lokasi Tapak Proyek:
              </b>
            </p>
            <div
              v-if="!isInitiator"
              class="description"
              v-html="scope.row.environment_condition"
            />
            <p v-if="!isInitiator" style="margin-top: 10px">
              <b>Nilai Lokal yang Berpotensi akan Terkena Dampak:</b>
            </p>
            <div
              v-if="!isInitiator"
              class="description"
              v-html="scope.row.local_impact"
            />
          </div>
        </template>
      </el-table-column>
      <el-table-column label="No." width="54px">
        <template slot-scope="scope">
          <span>{{ scope.$index + 1 }}</span>
        </template>
      </el-table-column>
      <el-table-column
        align="left"
        label="Tanggal"
        width="200"
        prop="created_at"
        sortable
      >
        <template slot-scope="scope">
          <span>{{
            scope.row.created_at | parseTime('{y}-{m}-{d} {h}:{i}')
          }}</span>
        </template>
      </el-table-column>
      <el-table-column align="left" label="Nama" prop="name" sortable />
      <el-table-column
        align="left"
        label="Peran"
        width="260"
        prop="responder_type_name"
        sortable
      />
      <el-table-column
        align="left"
        label="Rating"
        width="150"
        prop="rating"
        sortable
      >
        <template slot-scope="scope">
          <el-rate
            v-model="scope.row.rating"
            :colors="['#99A9BF', '#F7BA2A', '#F7BA2A', '#F7BA2A', '#FF9900']"
            :max="5"
            :disabled="disableRating"
            style="margin-top: 8px"
            @change="onChangeForm(scope.row.id, $event)"
          />
        </template>
      </el-table-column>
      <el-table-column
        v-if="!isInitiator"
        align="left"
        label="Relevansi"
        width="130"
        prop="is_relevant"
        sortable
      >
        <template slot-scope="scope">
          <el-select
            v-if="scope.row.responder_type_id === 2"
            v-model="scope.row.is_relevant"
            placeholder="Select"
            style="width: 100%"
            @change="onChangeForm(scope.row.id, $event)"
          >
            <el-option
              v-for="item in relevantChoices"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
          <span v-else>Relevan</span>
        </template>
      </el-table-column>
    </el-table>
    <div v-if="!isInitiator" style="text-align: right; margin-top: 10px">
      <el-button :loading="loadingSave" type="primary" @click="updateRelevance">
        Simpan
      </el-button>
    </div>
    <IdentityDialog
      :data="identityDialogData"
      :photo="identityDialogImg"
      :show="showIdDialog"
      @handleCloseDialog="handleCloseIdentityDialog"
    />
    <CreateFeedback
      v-if="isInitiator"
      :feedback="feedback"
      :show="showFeedback"
      :announcement-id="announcementId"
      @handleCloseDialog="handleCloseFeedbackDialog"
    />
  </div>
</template>

<script>
import Resource from '@/api/resource';
import { mapGetters } from 'vuex';
import IdentityDialog from './IdentityDialog.vue';
import CreateFeedback from '@/views/home/components/CreateFeedback.vue';
const feedbackResource = new Resource('feedbacks');
const responderTypeResource = new Resource('responder-types');

export default {
  name: 'FeedbackList',
  components: { IdentityDialog, CreateFeedback },
  props: {
    disableRating: {
      type: Boolean,
      // eslint-disable-next-line space-before-function-paren
      default: function () {
        return false;
      },
    },
  },
  data() {
    return {
      feedbacks: [],
      loading: true,
      announcementId: 0,
      feedback: {},
      relevantChoices: [],
      selectedFeedback: {},
      identityDialogData: [],
      identityDialogImg: '',
      showIdDialog: false,
      showFeedback: false,
      loadingSave: false,
      updatedSPT: [],
    };
  },
  computed: {
    ...mapGetters({
      userInfo: 'user',
    }),
    isInitiator() {
      return this.userInfo.roles.includes('initiator');
    },
    isExaminer() {
      return this.$store.getters.roles.includes('examiner');
    },
  },
  mounted() {
    this.relevantChoices = [
      { value: true, label: 'Relevan' },
      { value: false, label: 'Tidak Relevan' },
    ];
    // event handler
    // this.$bus.$on('updateFeedbackList', event => {
    //   this.getFeedbacks(this.id);
    // });
    this.getFeedbacks();
  },
  methods: {
    async getFeedbacks(search = null) {
      const id = parseInt(this.$route.params && this.$route.params.id);
      this.announcementId = id;
      // filter by project ID
      this.loading = true;
      const { data } = await feedbackResource.list({
        announcement_id: id,
        deleted: false,
        search,
      });
      const responderTypes = await responderTypeResource.list({});
      const responder_types = responderTypes.data;
      data.map((item) => {
        const key = item.responder_type_id;
        var responder_type_name = '';
        responder_types.map((item) => {
          if (item.id === key) {
            responder_type_name = item.name;
          }
        });
        item.responder_type_name = responder_type_name;
        item.is_relevant_str = item.is_relevant ? 'Relevan' : 'Tidak Relevan';
      });
      this.feedbacks = data;
      this.loading = false;
    },
    showCreateFeedback() {
      this.showFeedback = true;
      this.showIdDialog = false;
    },
    handleCloseFeedbackDialog() {
      this.showFeedback = false;
      this.showIdDialog = false;
      this.getFeedbacks();
    },
    handleCloseIdentityDialog() {
      this.showFeedback = false;
      this.showIdDialog = false;
    },
    showIdentity(feedbackId) {
      const toShow = this.feedbacks.find((f) => f.id === feedbackId);
      this.selectedFeedback = toShow;
      this.identityDialogImg = toShow.photo_filepath;
      this.identityDialogData = [
        {
          param: 'Nama',
          value: this.selectedFeedback.name,
        },
        {
          param: 'NIK',
          value: this.selectedFeedback.id_card_number,
        },
        {
          param: 'No. Telepon',
          value: this.selectedFeedback.phone,
        },
        {
          param: 'Email',
          value: this.selectedFeedback.email,
        },
      ];
      this.showIdDialog = true;
      this.showFeedback = false;
    },
    async onChangeForm(feedbackId, event) {
      // const toUpdate = this.feedbacks.find(f => f.id === feedbackId);
      // feedbackResource
      //   .update(feedbackId, toUpdate)
      //   .then(response => {
      //     const message = 'SPT \'' + response.data.name + '\' berhasil diupdate';
      //     this.$message({
      //       message: message,
      //       type: 'success',
      //       duration: 5 * 1000,
      //     });
      //   })
      //   .catch(error => {
      //     console.log(error);
      //   });
      const idx = this.updatedSPT.findIndex((x) => x.id === feedbackId);
      if (idx === -1) {
        this.updatedSPT.push({ id: feedbackId, is_relevant: event });
      } else {
        this.updatedSPT[idx].is_relevant = event;
      }
    },
    async updateRelevance() {
      if (this.updatedSPT.length === 0) {
        this.$message({
          message: 'Data berhasil disimpan',
          type: 'success',
          duration: 5 * 1000,
        });
      } else {
        this.loadingSave = true;
        await feedbackResource.store({
          relevance: 'true',
          updatedspt: this.updatedSPT,
        });
        this.getFeedbacks();
        this.loadingSave = false;
        this.updatedSPT = [];
        this.$message({
          message: 'Data berhasil disimpan',
          type: 'success',
          duration: 5 * 1000,
        });
      }
    },
  },
};
</script>
