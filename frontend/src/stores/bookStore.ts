import {defineStore} from 'pinia';
import axios from 'axios';

interface Book {
  id: number;
  title: string;
  author: string;
  status: string;
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
        const response = await axios.get('http://127.0.0.1:8000/api/books');
        console.log(response.data);
        this.books = response.data;
      } catch (error) {
        console.error(error);
      }
    },
    async fetchBookStatuses() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/books/status-options')
        console.log(response.data);
        this.statusOptions = response.data
      } catch (error) {
        console.error(error);
      }
    },
    async createBook(bookData: {title: string, author: string, status: string}){
      try {
        const response = await axios.post('http://127.0.0.1:8000/api/books', bookData,);
        this.books.push(response.data);
      } catch (error) {
        console.error(error);
      }
    },
  },
},)
