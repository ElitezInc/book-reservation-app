import { configureStore } from "@reduxjs/toolkit";
import booksReducer from "./Feature/booksSlice";

export default configureStore({
    reducer: {
        books: booksReducer,
    },
});
