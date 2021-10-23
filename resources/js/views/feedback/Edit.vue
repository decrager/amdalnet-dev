<template>
  <feedback-form :is-edit="true" />
</template>

<script>
import FeedbackForm from './components/Form';

export default {
  name: 'EditFeedbackForm',
  components: { FeedbackForm },
};
</script>
