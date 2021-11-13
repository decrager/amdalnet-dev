<template>
  <feedback-form :is-edit="false" />
</template>

<script>
import FeedbackForm from './components/Form';

export default {
  name: 'CreateFeedbackForm',
  components: { FeedbackForm },
};
</script>
