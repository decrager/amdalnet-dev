<template>
  <announcement-detail :is-edit="false" />
</template>

<script>
import AnnouncementDetail from './components/AnnouncementDetail';

export default {
  name: 'ViewForm',
  components: { AnnouncementDetail },
};
</script>
