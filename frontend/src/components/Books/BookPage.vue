<template>
  <q-page>
    <p>Store currentPage: {{ bookStore.currentPage }}</p>

    <q-input
      v-model="bookStore.searchTerm"
      label="Search for book"
      @update:model-value="onSearchChange()"
    />

    <q-select
      v-model="bookStore.pageSize"
      :options="[5, 10, 20, 50]"
      label="Per page"
      @update:model-value="onPageSizeChange"
    />
    <q-pagination
      v-model="bookStore.currentPage"
      :max="bookStore.totalPages"
      @update:model-value="onPageChange"
    />
    <q-list v-if="bookStore.books.length > 0">
      <q-item v-for="book in bookStore.books" :key="book.id">
        <q-item-section>
          <q-item-label>
            <RouterLink :to="{ name: 'book-details', params: { id: book.id } }">{{
              book.title
            }}</RouterLink>
          </q-item-label>
          <q-item-label caption> {{ book.author }} - {{ book.status }} </q-item-label>
          <q-btn @click="deleteBook(book.id)">Delete</q-btn>
        </q-item-section>
      </q-item>
    </q-list>
    <p v-else>
      Nothing Here. Add a new book.
    </p>
  </q-page>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useBookStore } from '@/stores/bookStore';

const bookStore = useBookStore();
let debounceTimer: ReturnType<typeof setTimeout>;

onMounted(async () => {
  await bookStore.fetchBooks();
});

function onSearchChange() {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    bookStore.currentPage = 1;
    void bookStore.fetchBooks();
    console.log(bookStore.books);
  }, 300);
}

function onPageSizeChange() {
  bookStore.currentPage = 1;
  void bookStore.fetchBooks();
}
function onPageChange() {
  void bookStore.fetchBooks();
}

function deleteBook(id: number) {
  void bookStore.deleteBook(id)
}
</script>
