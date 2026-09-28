import {defineStore} from 'pinia';
import axios from 'axios';
import type { UnwrapRef } from 'vue';

interface Book {
  id: number;
  title: string;
  author: string;
  status: string;
  rating: number;
}

interface StatusOption {
  label: number;
  value: string;
}

export const useBookStore = defineStore('books', {
  state: () => ({
    books: [] as Book[],
    statusOptions: [] as StatusOption[],
  }),
  actions: {
    async fetchBooks() {
      try {
        const response = await axios.get('https://127.0.0.1:8000/api/books');
        console.log(response.data);
        this.books = response.data;
      } catch (error) {
        console.error(error);
      }
    },
    async fetchBookStatuses() {
      try {
        const response = await axios.get('https://127.0.0.1:8000/api/books/status-options');
        console.log(response.data);
        this.statusOptions = response.data;
      } catch (error) {
        console.error(error);
      }
    },
    async createBook(bookData: UnwrapRef<{ title: string; author: string; status: string }>) {
      try {
        const response = await axios.post('https://127.0.0.1:8000/api/books', bookData);
        this.books.push(response.data);
      } catch (error) {
        console.error(error);
      }
    },
    async updateBook(id: number, bookData: Omit<Book, 'id'>) {
      try {
        const response = await axios.put(`https://127.0.0.1:8000/api/books/edit/${id}`, bookData);
        console.log(response.data);
        const index = this.books.findIndex((book) => book.id === id);
        if (index !== -1) {
          this.books[index] = response.data;
        }
      } catch (error) {
        console.error(error);
      }
    },
  },
});
