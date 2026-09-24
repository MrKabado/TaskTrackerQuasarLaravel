<template>
  <q-page class="q-pa-lg">

    <div class="text-h4">
      Laravel + Vue + Quasar
    </div>

    <q-btn
      class="q-mt-md"
      color="primary"
      label="Test Backend"
      @click="testApi"
    />

    <q-btn
      class="q-mt-md q-ml-md"
      color="primary"
      label="Test Backend 2"
      @click="testApi2"
    />

    <div class="q-mt-md">
      {{ message }}
    </div>

    <div class="q-mt-md">
      {{ message2 }}
    </div>

  </q-page>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'

const message = ref('');
const message2 = ref('');

async function testApi() {
  const response = await axios.get('/api/hello')

  message.value = response.data.message
}

async function testApi2() {
  const response = await axios.get('/api/introduce')

  message2.value = response.data.message
}
</script>