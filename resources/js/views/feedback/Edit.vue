<template>
  <feedback-detail :is-edit="true" />
</template>

<script>
import FeedbackDetail from './components/FeedbackDetail';

export default {
  name: 'EditFeedbackForm',
  components: { FeedbackDetail },
};
</script>
