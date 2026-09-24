<template>
  <q-page class="flex flex-center">
    <div class="column items-center">
      <add-new-book />
      <book-page />
    </div>
  </q-page>
</template>

<script setup lang="ts">
import BookPage from '../components/Books/BookPage.vue';
import AddNewBook from '../components/Books/AddNewBook.vue';
</script>
