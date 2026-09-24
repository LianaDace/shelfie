<template>
  <q-page>
    <q-list>
      <q-item v-for="book in bookStore.books" :key="book.id">
        <q-item-section>
          <q-item-label>
            {{ book.title }}
          </q-item-label>
          <q-item-label caption>
            {{ book.author }} - {{book.status}}
          </q-item-label>
        </q-item-section>
      </q-item>
    </q-list>
  </q-page>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useBookStore } from '@/stores/bookStore';

const bookStore = useBookStore();

onMounted(async () => {
  await bookStore.fetchBooks();
});
</script>
