<script setup>
import Layout from '@/components/layouts/main.vue';
import PageHeader from '@/components/layouts/page-header.vue';

const title = 'Dashboard';
</script>

<template>
  <Layout>
    <PageHeader :title="title" />
  </Layout>
</template>
