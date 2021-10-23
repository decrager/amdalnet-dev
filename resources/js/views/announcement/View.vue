<template>
  <announcement-detail
    :show-project-detail="true"
    :show-feedback-list="true"
    :show-public-consultation="true"
  />
</template>

<script>
import AnnouncementDetail from './components/Detail';

export default {
  name: 'ViewForm',
  components: { AnnouncementDetail },
};
</script>
