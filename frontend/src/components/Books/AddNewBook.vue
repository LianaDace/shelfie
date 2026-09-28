<template>
  <q-input v-model="newBook.title" label="Title" />

  <q-input v-model="newBook.author" label="Author" />

  <q-btn-dropdown
    :label="newBook.status || 'Select Status'"
    outline
    aria-haspopup="menu"
  >
    <q-list role="menu">
      <q-item
        v-for="option in bookStore.statusOptions"
        :key="option.value"
        v-close-popup
        clickable
        @click="newBook.status = option.value"
      >
        <q-item-section>
          <q-item-label>{{ option.label }}</q-item-label>
        </q-item-section>
      </q-item>
    </q-list>
  </q-btn-dropdown>
  <!--  <q-input v-model="newBook.status" label="Status" />-->

  <q-btn label="Add Book" @click="addNewBook" />
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useBookStore } from '@/stores/bookStore';

const bookStore = useBookStore();

const newBook = ref(
  {
  title: '',
  author: '',
  status: '',
});


function addNewBook() {
  void bookStore.createBook(newBook.value);
  newBook.value = { title: '', author: '', status: '' };
}

onMounted(() => {
  void bookStore.fetchBookStatuses();
});
</script>
