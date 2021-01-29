<template>
  <div>
    <div v-if="shouldShowBackToContentBlockPage">
      <router-link
          class="inline-flex items-center text-info no-underline"
          :to="{path: '/resources/content-block-resources'}"
      >
        <icon type="menu" width="24" height="24" view-box="0 0 24 24" /> <span>Список блоков</span>
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  props: ['resourceName'],

  computed: {
    shouldShowBackToContentBlockPage() {
      return this.$route.params.resourceName === 'content-block-item-resources';
    }
  }
};
</script>
