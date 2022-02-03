<template>
  <div style="padding: 2em;">
    <div class="radio">
      Urutkan:&nbsp;&nbsp;
      <el-radio-group v-model="reverse">
        <el-radio :label="true">Terdahulu</el-radio>
        <el-radio :label="false">Terbaru</el-radio>
      </el-radio-group>
    </div>

    <el-timeline :reverse="reverse" style="margin-top: 3em;">
      <el-timeline-item
        v-for="(activity, index) in activities"
        :key="index"
        :timestamp="activity.timestamp"
      >
        {{ activity.content }}
      </el-timeline-item>
    </el-timeline>
  </div>
</template>
<script>
export default {
  name: 'ProjectTimeline',
  data() {
    return {
      reverse: true,
      activities: [{
        content: 'Completed',
        timestamp: '2018-04-15',
      }, {
        content: 'Approved',
        timestamp: '2018-04-13',
      }, {
        content: 'Reviewed',
        timestamp: '2018-04-13',
      }, {
        content: 'Submitted',
        timestamp: '2018-04-11',
      }],
    };
  },
};
</script>
