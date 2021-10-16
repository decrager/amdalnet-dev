<template>
  <feedback-detail :is-edit="false" />
</template>

<script>
import FeedbackDetail from './components/FeedbackDetail';

export default {
  name: 'CreateFeedbackForm',
  components: { FeedbackDetail },
};
</script>
