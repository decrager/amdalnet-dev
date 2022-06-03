<template>
  <div style="padding: 2em;" :loading="loading">
    <template v-if="activities.length > 0">
      <div class="radio">
        <span style="display:inline-block;font-size:0.9em; margin-right:1em;">Urutkan:</span>
        <el-radio-group v-model="reverse">
          <el-radio :label="true">Terdahulu</el-radio>
          <el-radio :label="false">Terbaru</el-radio>
        </el-radio-group>
      </div>

      <el-timeline :reverse="reverse" style="margin-top: 3em;">
        <el-timeline-item
          v-for="(activity, index) in activities"
          :key="index"
          :timestamp="activity.datetime"
          size="large"
          :type="(index === 0) ? 'primary': (index === (activities.length - 1) ? 'info' : 'default' )"
          placement="top"
        >
          <!--
            :type="(activity.value.marking)? 'primary': ((activity.event === 'created')? 'info' : 'default')"
             <div v-if="activity.event === 'created'">
            <p>{{ activity.value.project_title }}</b> dibuat oleh <i>{{ activity.username }}</i>.</p>
          </div>
          <div v-else-if="activity.value.marking">
            <p>{{ activity.value.marking }}, oleh <i>{{ activity.username }}</i></p>
          </div>
          <div v-else-if="activity.value.type_formulator_team">
            <p>Tim Penyusun ditetapkan oleh <i>{{ activity.username }}</i></p>
          </div>
          <div v-else>
            <p>{{ activity.content }}, oleh <i>{{ activity.username }}</i></p>
          </div> -->
          <div>
            <p>{{ activity.label }}</p>
          </div>
        </el-timeline-item>
      </el-timeline>
    </template>
    <template v-else>
      <div>
        <p>Data tidak ditemukan</p>
      </div>
    </template>
  </div>
</template>
<script>
import Resource from '@/api/resource';
const timelineResource = new Resource('timeline');
export default {
  name: 'ProjectTimeline',
  props: {
    id: {
      type: Number,
      default: 0,
    },
  },
  data() {
    return {
      // id: 0,
      reverse: false,
      activities: [],
      loading: false,
    };
  },
  mounted(){
    // this.id = this.project_id = this.$route.params && this.$route.params.id;
    this.getData();
  },
  methods: {
    async getData() {
      this.activities = [];
      this.loading = true;
      await timelineResource.list({ id: this.id })
        .then((res) => {
          this.activities = this.process(res);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    process(data){
      return data;
      /*
      const res = [];

      data.map((e) => {
        const val = JSON.parse(e.new_values);
        if ((e.event === 'created') || ((e.event === 'updated') && (val.marking))){
          const processed = {
            datetime: e.datetime,
            username: e.user_name,
            event: e.event,
            content: e.new_values,
            value: val,
          };
          res.push(processed);
        }
      });
      return res;*/
    },
  },
};
</script>
